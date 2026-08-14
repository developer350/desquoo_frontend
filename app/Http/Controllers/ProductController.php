<?php

namespace App\Http\Controllers;

use App\Helpers\FrontendHelpers;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use ProductTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = $this->getProducts($request);
            if ($products->isEmpty() && $request->page > 1) {
                return;
            }

            return view('products.partials.listing', compact('products'))->render();
        }

        $pageDetails = FrontendHelpers::getPageDetails('products');
        $categories = $this->getParentCategories();
        $products = $this->getProducts($request);

        return view('products.index', compact('pageDetails', 'categories', 'products'));
    }

    public function getProducts($request)
    {
        return $this->getBaseQuery()
            ->when(! empty($request->categories), fn ($q) => $q->whereIn('products.category_id', $request->categories))
            ->when($request->bestseller, fn ($q) => $q->where('products.is_best_seller', 1))
            ->paginate(12);
    }

    public function getParentCategories()
    {
        return Cache::rememberForever('parentCategories', function () {
            return Category::whereNull('parent_id')->active()->get();
        });
    }

    public function productDetail($slug)
    {
        $product = Product::active()->where('slug', $slug)->with([
            'addons.media',
            'addons.category:id,name,slug',
            'addons.firstVariant',
            'productFeatures' => function ($query) {
                $query->with('media')->active()->sort();
            },
            'firstVariant' => function ($query) {
                $query->with('media', 'gallery.media', 'attributeValues')->orderByRaw(
                    'CASE
                        WHEN product_variants.stock > 0 THEN 1
                            ELSE 2
                        END,
                    COALESCE(product_variants.offer_price, product_variants.price) ASC
                        ',
                )
                    ->where('product_variants.status', 1)
                    ->orderBy('product_variants.id');
            },
            'bulkOrders' => function ($query) {
                $query->sort()->active();
            },
            'gallery.media',
            'reviews' => function ($query) {
                $query->where('status', 1)->limit(3)->latest();
            },
            'highlightReviews' => function ($query) {
                $query->where('status', 1)->with('media');
            },
        ])->withMedia()
            ->withAvg(['reviews' => function ($query) {
                $query->where('status', 1);
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 1);
            }],
            )
            ->first();
        if (! $product) {
            return abort(404);
        }

        $product->setAttribute('star_percentages_cached', $product->star_rating_percentages);

        $selectedAttributes = $this->getSelectedAttributes($product);

        $relatedProducts = $product->related_products != null ? $this->getBaseQuery()->whereIn('products.id', $product->related_products)->inRandomOrder()->get() : collect();

        $supportSectionCms = app('supportSectionCms');

        // user can review the product
        if (auth()->check()) {
            $purchased = Auth::user()->orders()->where('status', 'delivered')
                ->whereHas('orderItems', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })->exists();

            // check if the user has purchased the product
            $product->setAttribute('can_review', $product->reviews()->where('user_id', auth()->id())->count() == 0 && $purchased);
        }

        $siteSettings = app('siteSettings');

        return view('products.detail', compact('product', 'selectedAttributes', 'relatedProducts', 'supportSectionCms', 'siteSettings'));
    }

    public function getProductVariant(Request $request)
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
                    ->with(['gallery.media', 'media']);
            }, 'gallery.media', 'media'])
            ->first();

        if (! $product || $product->variants->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $product->firstVariant = $product->variants->first();

        $imageHtml = view('products.partials.detail.image', compact('product'))->render();
        $descriptionHtml = view('products.partials.detail.description', compact('product'))->render();
        $actionsHtml = view('products.partials.detail.actions', compact('product'))->render();

        return response()->json([
            'status' => true,
            'message' => 'Product variant found.',
            'imageHtml' => $imageHtml,
            'descriptionHtml' => $descriptionHtml,
            'actionsHtml' => $actionsHtml,
            'product' => $product,
        ]);
    }

    public function getVariantGlb(Request $request)
    {
        $product = Product::select('id', 'image_alt_text')->where('id', $request->product_id)->first();
        if (! $product) {
            return response()->json(['status' => false, 'message' => 'Product not found.'], 404);
        }

        $attributeValues = $request->attributeValues;

        $variant = ProductVariant::where('product_id', $product->id)
            ->where(function ($query) use ($attributeValues) {
                foreach ($attributeValues as $value) {
                    $query->whereRaw('FIND_IN_SET(?, variant_name)', [$value]);
                }
            })
            ->first();

        if (! $variant) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $glb = $variant->three_d_value ?? $product->three_d_value;
        if ($glb != null) {
            return response()->json(['status' => true, 'message' => 'Product variant found.', 'data' => [
                'isGlb' => true,
                'url' => $glb,
            ]]);
        } else {
            $img = $variant->image_value ?? $product->image_value;

            return response()->json(['status' => true, 'message' => 'Product variant found.', 'data' => [
                'isGlb' => false,
                'url' => $img,
                'alt_text' => $variant->image_alt_text ?? $product->image_alt_text,
            ]]);
        }
    }

    public function getVariantDescription(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        if (! $product) {
            return response()->json(['status' => false, 'message' => 'Product not found.'], 404);
        }

        $attributeValues = $request->attributeValues;

        $variant = ProductVariant::with('media')->where('product_id', $product->id)
            ->where(function ($query) use ($attributeValues) {
                foreach ($attributeValues as $value) {
                    $query->whereRaw('FIND_IN_SET(?, variant_name)', [$value]);
                }
            })
            ->first();

        if (! $variant) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $product->firstVariant = $variant;

        $description_image = $product->firstVariant->desc_image_value ?? $product->desc_image_value;

        $description = view('custom.partials.product-description', compact('product'))->render();

        return response()->json(['status' => true, 'message' => 'Product variant found.', 'description_image' => $description_image, 'description' => $description]);
    }

    public function getVariantInfo(Request $request)
    {
        $product = Product::select('id')->where('id', $request->product_id)->first();
        if (! $product) {
            return response()->json(['status' => false, 'message' => 'Product not found.'], 404);
        }

        $attributeValues = $request->selectedAttributeValues; // array

        $variant = ProductVariant::with('media')->where('product_id', $product->id)
            ->where(function ($query) use ($attributeValues) {
                foreach ($attributeValues as $value) {
                    $query->whereRaw('FIND_IN_SET(?, variant_name)', [$value]);
                }
            })
            ->first();

        if (! $variant) {
            return response()->json(['status' => false, 'message' => 'Product variant not found.'], 404);
        }

        $product->firstVariant = $variant;

        $price = $product->firstVariant->last_price;
        $image = $product->firstVariant->image_value ?? $product->image_value;

        return response()->json(['status' => true, 'message' => 'Product variant found.', 'price' => $price, 'image' => $image]);
    }

    public function categoryDetail($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (! $category) {
            abort(404);
        }

        $categories = $this->getParentCategories();
        $products = $this->getBaseQuery()->where('category_id', $category->id)->paginate(12);

        return view('products.category', compact('categories', 'products', 'category'));
    }

    public function getCategory(Request $request)
    {
        $category = Category::find($request->category);
        if (! $category) {
            return response()->json(['status' => false, 'message' => 'Category not found.'], 404);
        }

        $category->append(['banner_value', 'banner_alt_text_value', 'banner_mobile_value']);

        return response()->json(['status' => true, 'message' => 'Category found.', 'data' => $category]);
    }

    public function subCategoryDetail($slug, $subcategory)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();

        $subCategory = Category::where('slug', $subcategory)->with('parent.children')->active()->firstOrFail();

        $products = $this->getBaseQuery()->where('category_id', $subCategory->id)->paginate(12);

        return view('products.subcategory', compact('subCategory', 'products'));
    }

    public function getSubCategories($parent_id)
    {
        return Category::where('parent_id', $parent_id)->get();
    }

    public function searchProducts(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'category_id', 'image_alt_text')
            ->withMedia('image')
            ->active()
            ->activeCategory()
            ->with('category:id,name')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('sku', 'like', '%' . $request->search . '%')
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->sort()
            ->limit(5)
            ->get();

        return view('partials.search', compact('products'))->render();
    }
}
