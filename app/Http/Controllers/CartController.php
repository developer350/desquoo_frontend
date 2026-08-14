<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ProductTrait;

    public $userId;

    public $sessionId;

    public function __construct()
    {
        // check if login or not
        if (!Auth::check()) {
            $this->userId = null;
        } else {
            $this->userId = Auth::id();
        }

        $this->sessionId = session()->getId();
    }

    public function addToCart(Request $request)
    {
        $productId = $request->product_id;
        $productVariantId = $request->variant_id;
        $quantity = $request->quantity ?? 1;

        if ($this->userId) {
            $cart = Cart::where('user_id', $this->userId)->where('product_variant_id', $productVariantId)->first();
        } else {
            $cart = Cart::where('session_id', $this->sessionId)->where('product_variant_id', $productVariantId)->first();
        }

        // check if the product stock is available or not
        $product = Product::select('id', 'type', 'is_manage_stock')->find($productId);

        if ($product->is_manage_stock) {
            // check its stock
            $productVariant = ProductVariant::find($productVariantId);

            if ($cart) {
                $totalQuantity = $cart->quantity + $quantity;
            } else {
                $totalQuantity = $quantity;
            }

            if ($productVariant->stock < $totalQuantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product stock is not available for selected quantity.',
                ]);
            }
        }

        if ($cart) {
            // update the quantity
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
            ]);
        } else {
            $cart = Cart::create([
                'user_id' => $this->userId,
                'session_id' => $this->sessionId,
                'product_id' => $productId,
                'product_variant_id' => $productVariantId,
                'quantity' => $quantity,
            ]);
        }

        $this->sessionCartUpdate();

        $html = $this->getCart();

        if ($request->has('isFrom') && $request->isFrom == 'custom') {
            $customPageController = new CustomPageController;
            $step3html = $customPageController->getStep3html($request);

            return response()->json([
                'status' => true,
                'message' => 'Product added to cart successfully.',
                'cartHtml' => $html,
                'step3Html' => $step3html,
                'cartCount' => session()->get('cart') ? count(session()->get('cart')) : 0,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully.',
            'cartHtml' => $html,
            'cartCount' => session()->get('cart') ? count(session()->get('cart')) : 0,
        ]);
    }

    public function sessionCartUpdate()
    {
        $cart = Cart::when($this->userId != null, function ($q) {
            $q->where('user_id', $this->userId);
        })
            ->when($this->userId == null, function ($q) {
                $q->where('session_id', $this->sessionId);
            })
            ->pluck('product_variant_id')->toArray();
        session()->put('cart', $cart);
    }

    public function updateCart(Request $request)
    {
        $cartId = $request->cart_id;
        $quantity = $request->quantity;

        // check the product and its stock
        $cart = Cart::with('productVariant', 'product')->find($cartId);

        if ($cart->product->is_manage_stock && ($cart->productVariant->stock < $quantity)) {
            return response()->json([
                'status' => false,
                'message' => 'Product stock is not available.',
            ]);
        }

        $cart->update([
            'quantity' => $quantity,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product quantity updated successfully.',
            'summary' => $this->getCartSummary(),
            'summaryCheckout' => $this->getCartSummaryCheckout(),
        ]);
    }

    public function getCartSummary()
    {
        $cart = Cart::when($this->userId != null, callback: function ($q) {
            $q->where('user_id', $this->userId);
        })
            ->when($this->userId == null, function ($q): void {
                $q->where('session_id', $this->sessionId);
            })
            ->with('productVariant')
            ->whereHas('product', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('productVariant', function ($query) {
                $query->where('status', 1);
            })
            ->get();

        $subTotal = $cart->sum(function ($cart) {
            return $cart->productVariant->last_price * $cart->quantity;
        });

        return view('modals.partials.summary', compact('subTotal'))->render();
    }

    public function getCartSummaryCheckout()
    {
        return view('checkout.partials.summary')->render();
    }

    public function removeFromCart(Request $request)
    {
        $cartId = $request->cart_id;

        $cart = Cart::find($cartId);
        $cart->delete();

        $this->sessionCartUpdate();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart successfully.',
            'summary' => $this->getCartSummary(),
            'totalItems' => session()->get('cart') ? count(session()->get('cart')) : 0,
            'summaryCheckout' => $this->getCartSummaryCheckout(),
        ]);
    }

    public function getCart()
    {
        $carts = Cart::when($this->userId != null, function ($q) {
            $q->where('user_id', $this->userId);
        })
            ->when($this->userId == null, function ($q) {
                $q->where('session_id', $this->sessionId);
            })
            ->with([
                'productVariant.attributeValues.media',
                'productVariant.media',
                'product.media',
                'product' => function ($query) {
                    $query->select('id', 'name', 'slug', 'is_addon');
                },
            ])
            ->whereHas('product', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('productVariant', function ($query) {
                $query->where('status', 1);
            })
            ->latest()
            ->get();

        $regularCarts = $carts->filter(function ($cart) {
            return $cart->product && !$cart->product->is_addon;
        });

        $addonCarts = $carts->filter(function ($cart) {
            return $cart->product && $cart->product->is_addon;
        });

        $subTotal = $carts->sum(function ($cart) {
            return $cart->productVariant->last_price * $cart->quantity;
        });

        $totalItems = $carts->count();

        $carts = $regularCarts;

        return view('modals.partials.cart-content', compact('carts', 'addonCarts', 'subTotal', 'totalItems'))->render();
    }

    public function customAddToCart(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $attributeValues = $request->selectedAttributeValues; // array

        $variant = ProductVariant::with('media')->where('product_id', $productId)
            ->where(function ($query) use ($attributeValues) {
                foreach ($attributeValues as $value) {
                    $query->whereRaw('FIND_IN_SET(?, variant_name)', [$value]);
                }
            })
            ->first();

        if (!$variant) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $productVariantId = $variant->id;

        if ($this->userId) {
            $cart = Cart::where('user_id', $this->userId)->where('product_variant_id', $productVariantId)->first();
        } else {
            $cart = Cart::where('session_id', $this->sessionId)->where('product_variant_id', $productVariantId)->first();
        }

        // check if the product stock is available or not
        $product = Product::select('id', 'type', 'is_manage_stock')->find($productId);

        if ($product->is_manage_stock) {
            // check its stock
            $productVariant = ProductVariant::find($productVariantId);

            if ($cart) {
                $totalQuantity = $cart->quantity + $quantity;
            } else {
                $totalQuantity = $quantity;
            }

            if ($productVariant->stock < $totalQuantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product stock is not available for selected quantity.',
                ]);
            }
        }

        if ($cart) {
            // update the quantity
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
            ]);
        } else {
            $cart = Cart::create([
                'user_id' => $this->userId,
                'session_id' => $this->sessionId,
                'product_id' => $productId,
                'product_variant_id' => $productVariantId,
                'quantity' => $quantity,
            ]);
        }

        $this->sessionCartUpdate();

        $html = $this->getCart();

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully.',
            'cartHtml' => $html,
            'cartCount' => session()->get('cart') ? count(session()->get('cart')) : 0,
        ]);
    }

    public function removeAddonFromCart(Request $request)
    {
        $productId = $request->product_id;
        $productVariantId = $request->variant_id;

        if ($this->userId) {
            $cart = Cart::where('user_id', $this->userId)->where('product_variant_id', $productVariantId)->first();
        } else {
            $cart = Cart::where('session_id', $this->sessionId)->where('product_variant_id', $productVariantId)->first();
        }

        if ($cart) {
            $cart->delete();

            $this->sessionCartUpdate();

            $html = $this->getCart();
            $customPageController = new CustomPageController;
            $step3html = $customPageController->getStep3html($request);

            return response()->json([
                'status' => true,
                'message' => 'Product removed from cart successfully.',
                'cartHtml' => $html,
                'step3Html' => $step3html,
                'cartCount' => session()->get('cart') ? count(session()->get('cart')) : 0,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Product not found in cart.',
            ]);
        }
    }

    public function changeVariant(Request $request)
    {
        $productId = $request->product_id;
        $cartId = $request->cart_id;

        $cart = Cart::find($cartId);

        $product = Product::active()->find($productId)->with([
            'addons.media',
            'addons.category:id,name,slug',
            'addons.firstVariant',
            'firstVariant' => function ($query) use ($cart) {
                $query->with('media', 'gallery.media', 'attributeValues')->where('product_variants.id', $cart->product_variant_id)
                    ->where('product_variants.status', 1)
                    ->orderBy('product_variants.id');
            },
        ])->withMedia()->first();
        if (! $product) {
            return abort(404);
        }

        $selectedAttributes = $this->getSelectedAttributes($product);

        $html = view('modals.partials.edit-content', compact('product', 'selectedAttributes', 'cart'))->render();

        return response()->json([
            'status' => true,
            'message' => 'Product variant fetched successfully.',
            'html' => $html,
        ]);
    }

    public function getProductVariantInfo(Request $request)
    {
        $attributeValueIds = $request->input('attribute_values', []);
        sort($attributeValueIds);

        $product = Product::where('id', $request->input('product_id'))
            ->where('status', 1)
            ->whereHas('variants', function ($query) use ($attributeValueIds) {
                $query->where('status', 1)
                    ->whereHas('variantAttributes', function ($q) use ($attributeValueIds) {
                        $q->whereIn('attribute_value_id', $attributeValueIds);
                    }, '=', count($attributeValueIds));
            })
            ->with(['variants' => function ($query) use ($attributeValueIds) {
                $query->where('status', 1)
                    ->whereHas('variantAttributes', function ($q) use ($attributeValueIds) {
                        $q->whereIn('attribute_value_id', $attributeValueIds);
                    }, '=', count($attributeValueIds))
                    ->with(['media']);
            }, 'media'])
            ->first();

        if (! $product || $product->variants->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $product->firstVariant = $product->variants->first();

        $image = $product->firstVariant->image_value ?? $product->image_value;

        return response()->json([
            'status' => true,
            'message' => 'Product variant found.',
            'image' => $image,
            'product' => $product,
        ]);
    }

    public function changeCartVariant(Request $request)
    {
        $cartId = $request->cart_id;

        $attributeValueIds = $request->input('attribute_values', []);
        sort($attributeValueIds);

        $product = Product::where('id', $request->input('product_id'))
            ->where('status', 1)
            ->whereHas('variants', function ($query) use ($attributeValueIds) {
                $query->where('status', 1)
                    ->whereHas('variantAttributes', function ($q) use ($attributeValueIds) {
                        $q->whereIn('attribute_value_id', $attributeValueIds);
                    }, '=', count($attributeValueIds));
            })
            ->with(['variants' => function ($query) use ($attributeValueIds) {
                $query->where('status', 1)
                    ->whereHas('variantAttributes', function ($q) use ($attributeValueIds) {
                        $q->whereIn('attribute_value_id', $attributeValueIds);
                    }, '=', count($attributeValueIds))
                    ->with(['media']);
            }, 'media'])
            ->first();

        if (! $product || $product->variants->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $product->firstVariant = $product->variants->first();

        $cart = Cart::find($cartId);
        $cart->product_variant_id = $product->firstVariant->id;
        $cart->save();

        $this->sessionCartUpdate();

        $html = $this->getCart();

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully.',
            'cartHtml' => $html,
            'cartCount' => session()->get('cart') ? count(session()->get('cart')) : 0,
        ]);
    }
}
