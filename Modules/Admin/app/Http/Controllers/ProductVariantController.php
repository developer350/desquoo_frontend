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
                    $q->select('id', 'product_id', 'product_variant_id', 'attribute_id', 'attribute_value_id')->with(['attribute' => function ($subQuery) {
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
                    $frameColourIndex = -1;

                    // Collect all attributes text to evaluate condition
                    $allAttrsText = '';
                    foreach ($row->variantAttributes as $variant) {
                        $allAttrsText .= ' ' . ($variant->attribute?->name ?? '') . ': ' . ($variant->attributeValue?->value ?? '');
                    }
                    $allAttrsLower = strtolower($allAttrsText);

                    // Determine variant color based on condition
                    // 1200mm × 600mm -> White
                    // 1500mm × 750mm -> Black
                    // 1800mm × 750mm -> Wooden
                    $displayColor = null;
                    if (str_contains($allAttrsLower, '1800')) {
                        $displayColor = 'Wooden';
                    } elseif (str_contains($allAttrsLower, '1500')) {
                        $displayColor = 'Black';
                    } elseif (str_contains($allAttrsLower, '1200') || str_contains($allAttrsLower, '200mm')) {
                        $displayColor = 'White';
                    }

                    foreach ($row->variantAttributes as $variant) {
                        $attrName = ucfirst($variant->attribute?->name ?? '');
                        $attrVal = $variant->attributeValue?->value ?? '';
                        $line = "<strong>" .  $attrName . ": </strong>" . e($attrVal);

                        $isFrameColor = str_contains(strtolower($attrName), 'frame colour') || str_contains(strtolower($attrName), 'frame color');
                        $isAnyColor = str_contains(strtolower($attrName), 'colour') || str_contains(strtolower($attrName), 'color');

                        if ($isFrameColor) {
                            $frameColourIndex = count($name);
                        } elseif ($isAnyColor && $frameColourIndex === -1) {
                            $frameColourIndex = count($name);
                        }

                        // If size condition didn't match, fall back to attribute color
                        if (!$displayColor && $isAnyColor && !empty($attrVal)) {
                            $displayColor = $attrVal;
                        }

                        $name[] = $line;
                    }

                    // Render the color label badge below Frame Colour: (or below the last attribute if no Frame Colour)
                    if ($displayColor) {
                        $swatch = $this->getColorSwatchCss($displayColor);
                        $badge = '<br><span class="badge bg-light text-dark border mt-1 d-inline-flex align-items-center" style="font-size: 11px; padding: 4px 8px; font-weight: 600;">
                            <span class="rounded-circle d-inline-block me-1 border shadow-sm" style="width: 11px; height: 11px; ' . $swatch . ' flex-shrink: 0;"></span>
                            ' . e($displayColor) . '
                        </span>';

                        $targetIndex = ($frameColourIndex >= 0) ? $frameColourIndex : (count($name) - 1);
                        if ($targetIndex >= 0 && isset($name[$targetIndex])) {
                            $name[$targetIndex] .= $badge;
                        } elseif (empty($name)) {
                            $name[] = $badge;
                        }
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
                    $btn .= '<button type="button" class="btn btn-info btn-sm text-white duplicate-variant-btn mr-1" data-url="' . route('products.variants.duplicate', ['product' => base64_encode($product->id), 'variant' => base64_encode($row->id)]) . '" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Duplicate" style="margin-right: 3px;"><i class="fas fa-copy"></i></button>';
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

    /**
     * Duplicate the specified resource.
     */
    public function duplicate($productId, $variantId)
    {
        DB::beginTransaction();

        try {
            $product = Product::select('id', 'sku')->findOrFail(base64_decode($productId));
            $sourceVariant = ProductVariant::with(['variantAttributes', 'gallery'])->findOrFail(base64_decode($variantId));

            // Generate unique SKU
            $baseSku = $sourceVariant->sku ?: $this->productUtility->generateSkuForProductVariant($product);
            $newSku = $baseSku . '-COPY';
            $counter = 1;
            while (ProductVariant::where('sku', $newSku)->exists()) {
                $newSku = $baseSku . '-COPY-' . $counter++;
            }

            $newVariant = ProductVariant::create([
                'product_id' => $sourceVariant->product_id,
                'variant_name' => $sourceVariant->variant_name,
                'sku' => $newSku,
                'short_description' => $sourceVariant->short_description,
                'features' => $sourceVariant->features,
                'dimensions' => $sourceVariant->dimensions,
                'warranty_shipping' => $sourceVariant->warranty_shipping,
                'materials_certifications' => $sourceVariant->materials_certifications,
                'image_alt_text' => $sourceVariant->image_alt_text,
                'price' => $sourceVariant->price,
                'discount_amount' => $sourceVariant->discount_amount,
                'discount_percentage' => $sourceVariant->discount_percentage,
                'offer_price' => $sourceVariant->offer_price,
                'stock' => $sourceVariant->stock,
                'status' => $sourceVariant->status,
            ]);

            foreach ($sourceVariant->variantAttributes as $attr) {
                ProductVariantAttribute::create([
                    'product_id' => $newVariant->product_id,
                    'product_variant_id' => $newVariant->id,
                    'attribute_id' => $attr->attribute_id,
                    'attribute_value_id' => $attr->attribute_value_id,
                ]);
            }

            foreach (['image', 'desc_image', '3d', 'qr'] as $col) {
                $media = $sourceVariant->getFirstMedia($col);
                if ($media) {
                    $media->copy($newVariant, $col);
                }
            }

            foreach ($sourceVariant->gallery as $galleryItem) {
                $newGallery = $newVariant->gallery()->create([
                    'product_id' => $galleryItem->product_id,
                    'title' => $galleryItem->title,
                    'alt_text' => $galleryItem->alt_text,
                    'type' => $galleryItem->type,
                    'video_url' => $galleryItem->video_url,
                    'sort_order' => $galleryItem->sort_order,
                ]);

                foreach (['image', 'video', 'video_thumbnail_image', 'video_url_thumbnail_image'] as $col) {
                    $galMedia = $galleryItem->getFirstMedia($col);
                    if ($galMedia) {
                        $galMedia->copy($newGallery, $col);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product variant duplicated successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while duplicating the variant.',
            ]);
        }
    }

    protected function getColorSwatchCss($color)
    {
        if (blank($color)) {
            return '';
        }

        $color = trim($color);
        $lower = strtolower($color);

        if (preg_match('/^#([a-f0-9]{3}){1,2}$/i', $color)) {
            return 'background: ' . $color . ';';
        }

        $map = [
            'wooden' => 'background: linear-gradient(135deg, #c49a6c 0%, #8c5828 100%);',
            'wood' => 'background: linear-gradient(135deg, #c49a6c 0%, #8c5828 100%);',
            'oak' => 'background: linear-gradient(135deg, #d2a679 0%, #996633 100%);',
            'walnut' => 'background: linear-gradient(135deg, #6b4423 0%, #4a2e16 100%);',
            'teak' => 'background: linear-gradient(135deg, #b06500 0%, #783f04 100%);',
            'natural wood' => 'background: linear-gradient(135deg, #dfba8c 0%, #ab7d49 100%);',
            'bamboo' => 'background: linear-gradient(135deg, #e3c588 0%, #b89758 100%);',
            'white' => 'background-color: #ffffff; border-color: #d1d5db !important;',
            'marble white' => 'background-color: #F2F0E6; border-color: #d8d5c8 !important;',
            'black' => 'background-color: #111827;',
            'matte black' => 'background-color: #1f2937;',
            'grey' => 'background-color: #6b7280;',
            'gray' => 'background-color: #6b7280;',
            'silver' => 'background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);',
            'brown' => 'background-color: #78350f;',
            'beige' => 'background-color: #f5f5dc;',
            'red' => 'background-color: #ef4444;',
            'blue' => 'background-color: #3b82f6;',
            'navy' => 'background-color: #1e3a8a;',
            'green' => 'background-color: #10b981;',
            'yellow' => 'background-color: #f59e0b;',
            'orange' => 'background-color: #f97316;',
            'gold' => 'background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);',
        ];

        foreach ($map as $key => $css) {
            if (str_contains($lower, $key)) {
                return $css;
            }
        }

        return 'background-color: ' . htmlspecialchars($color, ENT_QUOTES) . ';';
    }
}
