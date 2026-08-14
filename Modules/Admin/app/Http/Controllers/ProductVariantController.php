<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\ProductVariantRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Arr;
use Modules\Admin\Utils\ProductUtility;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    protected $productUtility;

    public function __construct(ProductUtility $productUtility)
    {
        $this->productUtility = $productUtility;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $productId)
    {
        $product = Product::findOrFail(base64_decode($productId));

        if ($request->ajax()) {
            $data = ProductVariant::query()
                ->select('product_variants.*')->with(['variantAttributes' => function ($q) {
                    $q->select('product_id', 'product_variant_id', 'attribute_id', 'attribute_value_id')->with(['attribute' => function ($subQuery) {
                        $subQuery->select('id', 'name');
                    }, 'attributeValue' => function ($subQuery) {
                        $subQuery->select('id', 'value');
                    }]);
                }])
                ->where('product_id', $product->id)
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('variant', function ($row) {
                    $name = [];
                    foreach ($row->variantAttributes as $variant) {
                        $name[] = "<strong>" .  ucfirst($variant->attribute->name) . ": </strong>" . $variant->attributeValue->value;
                    }
                    return implode('<br>', $name);
                })
                ->filterColumn('variant', function ($query, $keyword) {
                    $query->whereHas('values', function ($query) use ($keyword) {
                        $query->where('value', 'like', '%' . $keyword . '%');
                    });
                })
                ->editColumn('price', fn($row) => $row->price_formatted)
                ->editColumn('offer_price', fn($row) => $row->offer_price_formatted)
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="ProductVariant"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) use ($product) {
                    $btn = '<a href="' . route('products.variants.edit', ['product' => base64_encode($product->id), 'variant' => base64_encode($row->id)]) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="' . route('product-variants.galleries.index', base64_encode($row->id)) . '" class="btn btn-primary btn-sm position-relative mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Gallery" style="margin-right: 3px;"><i class="fas fa-images label-icon"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">' . $row->gallery->count() . '</span></a>';
                    $btn .= '<form action="' . route('products.variants.destroy', ['product' => base64_encode($product->id), 'variant' => base64_encode($row->id)])  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemWithRelated" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['variant', 'status', 'action'])
                ->toJson();
        }
        return view('admin::product.variant.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        $product = Product::with(['attributes' => function ($q) {
            $q->with('values');
        }])->findOrFail(base64_decode($productId));
        return view('admin::product.variant.form', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductVariantRequest $request, $productId)
    {
        $variantValues = Arr::flatten($request->attribute_values);
        $product = Product::select('id', 'sku')->findOrFail(base64_decode($productId));

        DB::beginTransaction();

        try {

            //check if the same combination already exists for the product
            $existingVariant = ProductVariant::where('product_id', $product->id)
                ->whereHas('variantAttributes', function ($query) use ($variantValues) {
                    $query->whereIn('attribute_value_id', $variantValues);
                }, '=', count($variantValues))
                ->first();

            if ($existingVariant) {
                return response()->json(['success' => false, 'message' => 'The selected combination of attributes already exists for this product. Please choose a different combination.']);
            }


            $discount = $this->productUtility->calculateDiscountDetails($request->price, $request->offer_price);
            $productVariant = ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => implode(',', $variantValues),
                'sku' => $request->sku,
                'description' => $request->description,
                'image_alt_text' => $request->image_alt_text,
                'price' => $request->price,
                'discount_amount' => $discount['discount_amount'],
                'discount_percentage' => $discount['discount_percentage'],
                'offer_price' => $discount['offer_price'],
                'stock' => $request->stock,
                'status' => $request->status,
                'short_description' => $request->short_description,
                'features' => $request->features,
                'dimensions' => $request->dimensions,
                'warranty_shipping' => $request->warranty_shipping,
                'materials_certifications' => $request->materials_certifications,
            ]);

            if (blank($request->input('sku'))) {
                $productVariant->sku = $this->productUtility->generateSkuForProductVariant($product);
                $productVariant->save();
            }

            foreach ($variantValues as $attributeValue) {
                $og = AttributeValue::select('id', 'attribute_id')->find($attributeValue);

                ProductVariantAttribute::create([
                    'product_id' => $product->id,
                    'product_variant_id' => $productVariant->id,
                    'attribute_id' => $og->attribute_id,
                    'attribute_value_id' => $og->id
                ]);
            }

            $productVariant->uploadMedia($request, 'image');
            $productVariant->uploadMedia($request, 'desc_image');
            $productVariant->uploadMedia($request, '3d');
            $productVariant->uploadMedia($request, 'qr');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product Variant created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($productId, $variantId)
    {
        $product = Product::findOrFail(base64_decode($productId));
        $productVariant = ProductVariant::with(['variantAttributes' => function ($q) {
            $q->with(['attribute', 'attributeValue']);
        }])->findOrFail(base64_decode($variantId));
        return view('admin::product.variant.form', compact('product', 'productVariant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductVariantRequest $request, $productId, $variantId)
    {
        DB::beginTransaction();

        try {
            $discount = $this->productUtility->calculateDiscountDetails($request->price, $request->offer_price);
            $productVariant = ProductVariant::findOrFail(base64_decode($variantId));
            $productVariant->update([
                'sku' => $request->sku,
                'description' => $request->description,
                'image_alt_text' => $request->image_alt_text,
                'price' => $request->price,
                'discount_amount' => $discount['discount_amount'],
                'discount_percentage' => $discount['discount_percentage'],
                'offer_price' => $discount['offer_price'],
                'stock' => $request->stock,
                'status' => $request->status,
                'short_description' => $request->short_description,
                'features' => $request->features,
                'dimensions' => $request->dimensions,
                'warranty_shipping' => $request->warranty_shipping,
                'materials_certifications' => $request->materials_certifications,
            ]);

            $productVariant->uploadMedia($request, 'image');
            $productVariant->uploadMedia($request, 'desc_image');
            $productVariant->uploadMedia($request, '3d');
            $productVariant->uploadMedia($request, 'qr');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product Variant updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productId, $variantId)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail(base64_decode($productId));
            $productVariant = ProductVariant::findOrFail(base64_decode($variantId));

            Cart::where('product_variant_id', $productVariant->id)->delete();

            $productVariant->delete();

            if (Str::is('variable', $product?->type) && $product->variants()->count() === 0) {
                $product->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Product Variant deleted. Parent product removed as it had no variants left.',
                    'data' => [
                        'redirect' => route('products.index')
                    ]
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product Variant deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'The requested Product Variant does not exist.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }
}
