<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\PclFaq;
use App\Models\PclMindfulEngineering;
use App\Models\PclModel;
use App\Models\PclProductivity;
use App\Models\Product;
use App\Models\ProductAttributeValueMedia;
use App\Models\ProductCustomLanding;
use App\Models\ProductVariant;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomPageController extends Controller
{
    use ProductTrait;

    public $userId;

    public $sessionId;

    public function __construct()
    {
        //check if login or not
        if (!Auth::check()) {
            $this->userId = null;
        } else {
            $this->userId = Auth::id();
        }

        $this->sessionId = session()->getId();
    }

    public function index($slug)
    {
        $cms = ProductCustomLanding::withMedia()->where('slug', $slug)->first();

        if (!$cms || !$cms->status) {
            abort(404);
        }

        $productivities = PclProductivity::withMedia()->where('product_custom_landing_id', $cms->id)->active()->sort()->get();
        $faqs = PclFaq::where('product_custom_landing_id', $cms->id)->active()->sort()->get();
        $mindfulEngineerings = PclMindfulEngineering::withMedia()->where('product_custom_landing_id', $cms->id)->active()->sort()->get();
        $models = PclModel::withMedia()->where('product_custom_landing_id', $cms->id)->active()->sort()->get();

        $product = Product::active()->where('id', $cms->product_id)->with([
            'addons.media',
            'addons.category:id,name,slug',
            'addons.firstVariant',
            'firstVariant' => function ($query) {
                $query->with('media', 'gallery.media', 'attributeValues')->orderByRaw(
                    "CASE
                        WHEN product_variants.stock > 0 THEN 1
                            ELSE 2
                        END,
                    COALESCE(product_variants.offer_price, product_variants.price) ASC
                        ",
                )
                    ->where('product_variants.status', 1)
                    ->orderBy('product_variants.id');
            },
            'bulkOrders' => function ($query) {
                $query->sort()->active();
            },
            'gallery.media',
        ])->withMedia()->first();
        if (!$product) {
            return abort(404);
        }

        $selectedAttributes = $this->getSelectedAttributes($product);

        $relatedProducts = $product->related_products != null ? $this->getBaseQuery()->whereIn('products.id', $product->related_products)->inRandomOrder()->get() : collect();

        $productAttributeValueMedias = ProductAttributeValueMedia::with('media', 'attributeValue.media', 'attribute')->where('product_id', $product->id)->get();

        $isHaveStepOne = false;
        foreach ($productAttributeValueMedias as $productAttributeValueMedia) {
            if ($productAttributeValueMedia->attribute->is_main_attribute) {
                $isHaveStepOne = true;
                break;
            }
        }

        $isHaveStepThree = $product->addons->count() > 0;

        // Group by attribute
        $groupedByAttribute = $productAttributeValueMedias->groupBy(function ($media) {
            return $media->attribute->id ?? null;
        })->map(function ($attributeGroup) {
            $firstItem = $attributeGroup->first();
            return [
                'attribute' => $firstItem->attribute,
                'values' => $attributeGroup->sortBy('sort_order')->groupBy(function ($media) {
                    return $media->attributeValue->id ?? null;
                })->flatMap(function ($valueGroup) {
                    // Returns each ProductAttributeValueMedia as a separate item
                    return $valueGroup->map(function ($item) {
                        return [
                            'attribute_value' => $item->attributeValue,
                            'product_attribute_value_media' => $item  // Single object
                        ];
                    });
                })->values()
            ];
        })->values();

        $addedAddons = $this->getAddedAddonCartProduct($product);

        $currentSelectedVariantName = $product->name . ' with ' . $this->getVariantName($product->firstVariant, $productAttributeValueMedias);

        return view('custom.index', compact('cms', 'faqs', 'productivities', 'mindfulEngineerings', 'models', 'product', 'selectedAttributes', 'relatedProducts', 'productAttributeValueMedias', 'isHaveStepOne', 'isHaveStepThree', 'groupedByAttribute', 'addedAddons', 'currentSelectedVariantName'));
    }

    public function getAddedAddonCartProduct($product)
    {
        $carts = Cart::when($this->userId != null, function ($q) {
            $q->where('user_id', $this->userId);
        })
            ->when($this->userId == null, function ($q) {
                $q->where('session_id', $this->sessionId);
            })
            ->whereHas('product', function ($q) {
                $q->where('is_addon', 1);
            })
            ->pluck('product_id')->toArray();

        return $product->addons->filter(function ($addon) use ($carts) {
            return in_array($addon->id, $carts);
        });
    }

    public function getVariantName($productVariant, $productAttributeValueMedias)
    {
        return $productVariant->attributeValues->map(function ($attributeValue) use ($productAttributeValueMedias) {
            foreach ($productAttributeValueMedias as $productAttributeValueMedia) {
                if ($productAttributeValueMedia->attributeValue->id == $attributeValue->id) {
                    return $productAttributeValueMedia->title;
                }
            }
            return $attributeValue->name;
        })->implode(', ');
    }

    public function getStep3html($request)
    {
        $product = Product::active()->where('id', $request->currentProductId)->with([
            'addons.media',
            'addons.category:id,name,slug',
            'addons.firstVariant',
        ])->withMedia()->first();

        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found.'], 404);
        }

        $attributeValues = $request->selectedAttributeValues;

        $variant = ProductVariant::with('media')->where('product_id', $product->id)
            ->where(function ($query) use ($attributeValues) {
                foreach ($attributeValues as $value) {
                    $query->whereRaw("FIND_IN_SET(?, variant_name)", [$value]);
                }
            })
            ->first();

        if (!$variant) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }
        $productAttributeValueMedias = ProductAttributeValueMedia::with('media', 'attributeValue.media', 'attribute')->where('product_id', $product->id)->get();

        $product->firstVariant = $variant;

        $addedAddons = $this->getAddedAddonCartProduct($product);

        $currentSelectedVariantName = $product->name . ' with ' . $this->getVariantName($product->firstVariant, $productAttributeValueMedias);

        return view('modals.stepper3', compact('product', 'addedAddons', 'currentSelectedVariantName'))->render();
    }
}
