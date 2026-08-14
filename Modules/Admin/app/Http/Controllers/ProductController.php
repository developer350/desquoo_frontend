<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductCustomLanding;
use App\Models\ProductVariantAttribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Admin\Utils\ProductUtility;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $productUtility;

    public function __construct(ProductUtility $productUtility)
    {
        $this->productUtility = $productUtility;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::query()
                ->with([
                    'category:id,name',
                    'singleVariant:id,product_id,price,discount_amount,discount_percentage,offer_price',
                    'variants:id,product_id,price,discount_amount,discount_percentage,offer_price',
                ])
                ->withCount(['productCustomLandings'])
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn ($query) => $query->orderByDesc('id')
                );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    $image = '<img src="'.$row->image_value.'" alt="'.$row->image_alt_text_value.'" class="table-thumbnail me-2" style="width:40px;height:40px;object-fit:cover;">';

                    $name = '<span>'.e($row->name).'</span>';

                    return '<div class="d-flex align-items-center">'.$image.$name.'</div>';
                })
                ->filterColumn('product', function ($query, $keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                })
                ->orderColumn('product', function ($query, $order) {
                    $query->orderBy('name', $order);
                })
                ->editColumn('type', function ($row) {
                    return $row->type_badge;
                })
                ->addColumn('category', function ($row) {
                    return e(optional($row->category)->name ?? '—');
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('category', function ($subQuery) use ($keyword) {
                        $subQuery->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->orderColumn('category', function ($query, $order) {
                    $query->join('categories as c', 'products.category_id', '=', 'c.id')
                        ->orderBy('c.name', $order)
                        ->select('products.*');
                })
                ->addColumn('price', fn ($row) => $row->price_display_value)
                ->orderColumn('price', function ($query, $order) {
                    // Build an expression for the effective price at variant level
                    $effectiveExpr = 'CASE
                        WHEN pv.offer_price IS NOT NULL THEN pv.offer_price
                        WHEN pv.discount_amount IS NOT NULL THEN pv.price - pv.discount_amount
                        WHEN pv.discount_percentage IS NOT NULL THEN pv.price * (1 - pv.discount_percentage/100)
                        ELSE pv.price
                    END';

                    // Subquery that yields min effective price per product across variants
                    $sub = DB::table('product_variants as pv')
                        ->selectRaw('pv.product_id,
                     MIN('.$effectiveExpr.') as min_effective_price,
                     MAX('.$effectiveExpr.') as max_effective_price')
                        ->groupBy('pv.product_id');

                    // join once; order by min price
                    $query->leftJoinSub($sub, 'pv_agg', 'pv_agg.product_id', '=', 'products.id')
                        // for single products, you might prefer singleVariant only; this uses variants table for both types.
                        ->orderByRaw('COALESCE(pv_agg.min_effective_price, 1e18) '.($order === 'desc' ? 'DESC' : 'ASC'))
                        ->select('products.*'); // IMPORTANT: keep only product columns in the select
                })
                ->filterColumn('price', function ($query, $keyword) {
                    // crude numeric search: strip non-digits and compare to floor(effective price)
                    $num = preg_replace('/[^\d.]/', '', (string) $keyword);
                    if ($num === '') {
                        return;
                    }

                    $expr = 'CASE
                                WHEN pv.offer_price IS NOT NULL THEN pv.offer_price
                                WHEN pv.discount_amount IS NOT NULL THEN pv.price - pv.discount_amount
                                WHEN pv.discount_percentage IS NOT NULL THEN pv.price * (1 - pv.discount_percentage/100)
                                ELSE pv.price
                            END';

                    $query->whereExists(function ($sub) use ($expr, $num) {
                        $sub->from('product_variants as pv')
                            ->whereColumn('pv.product_id', 'products.id')
                            ->whereRaw($expr.' like ?', ["%{$num}%"]);
                    });
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="'.$row->sort_order.'"
                        class="form-control w-50 sort-order numeric-input" data-model="Product"
                        data-id="'.base64_encode($row->id).'" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';

                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status'.base64_encode($row->id).'" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="Product"
                            value="'.$row->status.'"
                            data-id="'.base64_encode($row->id).'" name="status"
                            '.$fieldValue.'>
                        <label class="custom-control-label" for="status'.base64_encode($row->id).'">'.$fieldLabel.'</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $id = base64_encode($row->id);
                    $isVar = $row->type === 'variable';
                    $varCnt = $isVar ? $row->variants->count() : 0;
                    $galCnt = $row->gallery->count();

                    $editUrl = route('products.edit', $id);
                    $variantsUrl = $isVar ? route('products.variants.index', $id) : null;
                    $galleryUrl = route('products.galleries.index', $id);
                    $bulkOrderUrl = route('products.bulk-orders.index', $id);
                    $attributeValueMediaUrl = $isVar ? ($row->product_custom_landings_count > 0 ? route('products.attribute-value-medias.index', $id) : null) : null;
                    $deleteUrl = route('products.destroy', $id);

                    // Variants only if product is variable
                    $variantsItem = $isVar ?
                        '<li><a href="'.$variantsUrl.'" class="dropdown-item"><i class="fas fa-layer-group me-2"></i> Variants<span class="badge bg-info float-end">'.$varCnt.'</span></a></li>' : '';
                    $attributesItem = $attributeValueMediaUrl != null ? '<li>
                                        <a href="'.$attributeValueMediaUrl.'" class="dropdown-item">
                                            <i class="fas fa-sliders-h me-2"></i>Custom Page <br> Attribute Value Settings
                                        </a>
                                    </li>' : '';
                    $deleteitem = $attributeValueMediaUrl == null ? '<li>
                                        <form action="'.$deleteUrl.'" method="POST" class="m-0">
                                            '.csrf_field().method_field('DELETE').'
                                            <button type="button"
                                                    class="dropdown-item text-danger delete-btn"
                                                    data-delete-message-type="itemWithRelated">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>' : '';

                    return '<div class="dropdown">
                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="'.$editUrl.'" class="dropdown-item">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    </li>
                                     <li>
                                        <a href="'.$bulkOrderUrl.'" class="dropdown-item">
                                            <i class="fas fa-cart-plus me-2"></i> Bulk Orders
                                        </a>
                                    </li>
                                    '.$variantsItem.'
                                    <li>
                                        <a href="'.$galleryUrl.'" class="dropdown-item">
                                            <i class="fas fa-images me-2"></i> Gallery
                                            <span class="badge bg-info float-end">'.$galCnt.'</span>
                                        </a>
                                    </li>
                                    '.$attributesItem.'
                                    <li><hr class="dropdown-divider"></li>
                                    '.$deleteitem.'
                                </ul>
                            </div>';
                })
                ->rawColumns(['product', 'type', 'sort_order', 'status', 'action'])
                ->toJson();
        }

        return view('admin::product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sort_order = Product::max('sort_order') + 1;

        return view('admin::product.form', compact('sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'is_manage_stock' => $request->has('is_manage_stock') ? 1 : 0,
                'is_addon' => $request->has('is_addon') ? 1 : 0,
            ]);

            // check if the addon is checked, then check if the type is single otherwise throw an exception
            if ($request->is_addon == 1) {
                if ($request->type !== 'single') {
                    return response()->json(['success' => false, 'message' => 'Addon products must be of type single.']);
                }
            }

            $product = Product::create(
                $request->except([
                    'attribute_id',
                    'attribute_values',
                    'combination_name',
                    'combination_id',
                    'product_variant_sku',
                    'price',
                    'offer_price',
                    'stock',
                    'image',
                ])
            );

            if (blank($request->input('sku'))) {
                $product->sku = $this->productUtility->generateProductSku($product->id);
                $product->save();
            }

            $product->addons()->sync($request->input('addons', []));
            $product->productFeatures()->sync($request->input('productFeatures', []));

            $this->productUtility->generateProductByType($product, $request);

            $product->uploadMedia($request, 'image');
            $product->uploadMedia($request, 'desc_image');
            $product->uploadMedia($request, '3d');
            $product->uploadMedia($request, 'qr');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with(['variants.variantAttributes' => function ($q) {
            $q->with(['attribute', 'attributeValue']);
        }])->findOrFail(base64_decode($id));
        $relatedProducts = $product->related_products !== null ? Product::select('id', 'name')->whereIn('id', $product->related_products)->get() : collect();
        $selectedAttributes = ProductVariantAttribute::with(['attribute', 'attributeValue'])
            ->where('product_id', $product->id)
            ->get()
            ->groupBy('attribute_id')
            ->map(fn ($items) => [
                'attribute' => $items->first()->attribute,
                'values' => $items->map(fn ($item) => [
                    'id' => $item->attributeValue->id,
                    'value' => $item->attributeValue->value,
                ])->unique('id')->values(),
            ])
            ->values();

        return view('admin::product.form', compact('product', 'selectedAttributes', 'relatedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'is_manage_stock' => $request->has('is_manage_stock') ? 1 : 0,
                'is_addon' => $request->has('is_addon') ? 1 : 0,
            ]);

            // check if the addon is checked, then check if the type is single otherwise throw an exception
            if ($request->is_addon == 1) {
                if ($request->type !== 'single') {
                    return response()->json(['success' => false, 'message' => 'Addon products must be of type single.']);
                }
            }

            $product = Product::findOrFail(base64_decode($id));
            $product->update(
                $request->except([
                    'attribute_id',
                    'attribute_values',
                    'combination_name',
                    'combination_id',
                    'product_variant_sku',
                    'price',
                    'offer_price',
                    'stock',
                    'image',
                ])
            );

            if (blank($request->input('sku'))) {
                $product->sku = $this->productUtility->generateProductSku($product->id);
                $product->save();
            }

            $product->addons()->sync(array_filter($request->addons ?? []));
            $product->productFeatures()->sync($request->input('productFeatures', []));

            $this->productUtility->generateProductByType($product, $request, true);

            $product->uploadMedia($request, 'image');
            $product->uploadMedia($request, 'desc_image');
            $product->uploadMedia($request, '3d');
            $product->uploadMedia($request, 'qr');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail(base64_decode($id));

            $customLanding = ProductCustomLanding::where('product_id', $product->id)->first();

            if (! $customLanding || $request->has('force_delete')) {
                $customLanding?->delete();
                $product->addons()->detach();
                $product->addonOf()->detach();
                $product->productFeatures()->detach();
                $product->bulkOrders()->delete();
                $product->productAttributeValueMedia()->delete();

                // delete from cart
                Cart::where('product_id', $product->id)->delete();

                $product->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'is_depended' => true,
                    'data' => [
                        'message' => 'Product has custom landing page. confirm action will delete its custom landing page.',
                        'force_delete' => false,
                    ],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Product does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Fetch a paginated list of products.
     */
    public function getProducts(Request $request)
    {
        $perPage = 30;

        $products = Product::query()
            ->select('id', 'name', 'sku')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')->orWhere('sku', 'like', '%'.$request->search.'%'))
            ->when($request->filled('exclude_id'), fn ($q) => $q->whereKeyNot($request->exclude_id))
            ->where('is_addon', 0)
            ->when($request->input('type') === 'variable_with_main_attributes', function ($q) {
                $q->where('type', 'variable')
                    ->whereHas('attributes', function ($q2) {
                        $q2->where('is_main_attribute', 1);
                    });
            })
            ->paginate($perPage);

        return response()->json($products);
    }

    public function getAddons(Request $request)
    {
        $perPage = 30;

        $products = Product::query()
            ->select('id', 'name', 'sku')
            ->where('type', 'single') // Addons must be of type single
            ->where('is_addon', 1) // Only products marked as addons
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')->orWhere('sku', 'like', '%'.$request->search.'%'))
            ->when($request->filled('exclude_id'), fn ($q) => $q->whereKeyNot($request->exclude_id))
            ->paginate($perPage);

        return response()->json($products);
    }

    public function getVariantTemplate(Request $request)
    {
        $product = null;
        $selectedAttributes = collect();
        $variations = collect();

        if ($request->filled('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $variations = $product->variants;

            $selectedAttributes = ProductVariantAttribute::with(['attribute', 'attributeValue'])
                ->where('product_id', $product->id)
                ->get()
                ->groupBy('attribute_id')
                ->map(fn ($items) => [
                    'attribute' => $items->first()->attribute,
                    'values' => $items->map(fn ($item) => [
                        'id' => $item->attributeValue->id,
                        'value' => $item->attributeValue->value,
                    ])->unique('id')->values(),
                ])
                ->values();
        }

        return match ($request->input('type')) {
            'variable' => response()->view('admin::product.partials.variable', compact('selectedAttributes', 'variations')),
            'single' => response()->view('admin::product.partials.single', compact('product')),
            default => abort(404),
        };
    }

    public function generateVariations(Request $request)
    {
        $selectedAttributes = $request->attributes_array;
        $attributesArray = [];
        foreach ($selectedAttributes as $key => $attributeArray) {
            $attribute = Attribute::select('id', 'name')->find($attributeArray['attribute_id']);
            $attributeValues = AttributeValue::select('id', 'value')->whereIn('id', $attributeArray['values'])->get();
            $values = [];
            foreach ($attributeValues as $attributeValue) {
                $values[] = [
                    'id' => $attributeValue->id,
                    'value' => $attributeValue->value,
                ];
            }

            $attributesArray[] = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'values' => $values,
            ];
        }

        $variations = $this->productUtility->generateVariationCombinations($attributesArray, $request->product_id);

        return view('admin::product.partials.variations', compact('variations', 'attributesArray'))->render();
    }

    public function getAttributeRow(Request $request)
    {
        $index = $request->attribute_count;

        return view('admin::product.partials.attribute-row', compact('index'))->render();
    }

    public function getFeatures(Request $request)
    {
        $perPage = 30;
        $features = Feature::when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->paginate($perPage);

        return response()->json($features);
    }
}
