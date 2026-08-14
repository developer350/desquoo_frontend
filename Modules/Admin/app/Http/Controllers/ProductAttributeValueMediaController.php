<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttributeValueMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductAttributeValueMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($product)
    {
        $product = Product::select('id', 'name')
            ->with(['attributeValuesByAttribute.attribute'])
            ->findOrFail(base64_decode($product));

        $productAttributeValueMedias = ProductAttributeValueMedia::with('media')->where('product_id', $product->id)->get();

        $attributes = $product->attributeValuesByAttribute->groupBy('attribute_id')->map(function ($items) {
            $attribute = $items->first()->attribute;
            return [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'is_main_attribute' => $attribute->is_main_attribute,
                'values' => $items
            ];
        })->values();

        return view('admin::product.attribute-value-media.index', compact('product', 'productAttributeValueMedias', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $product)
    {
        DB::beginTransaction();

        try {

            $attributeValueIds = $request->attribute_value_id;

            $is_default = $request->is_default;

            foreach ($attributeValueIds as $attributeValueId) {
                $productAttributeValueMedia = ProductAttributeValueMedia::where('product_id', base64_decode($product))->where('attribute_value_id', $attributeValueId)->first();
                //check the attribute value is default or not
                $isDefault = false;
                foreach ($is_default as $key => $value) {
                    if ($value == $attributeValueId) {
                        $isDefault = true;
                    }
                }

                if ($productAttributeValueMedia) {
                    $productAttributeValueMedia->update([
                        'title' => $request->title[$attributeValueId] ?? null,
                        'description' => $request->description[$attributeValueId] ?? null,
                        'height' => $request->height[$attributeValueId] ?? null,
                        'width' => $request->width[$attributeValueId] ?? null,
                        'depth' => $request->depth[$attributeValueId] ?? null,
                        'price' => $request->price[$attributeValueId] ?? null,
                        'sort_order' => $request->sort_order[$attributeValueId] ?? null,
                        'is_default' => $isDefault,
                    ]);

                    $productAttributeValueMedia->save();

                    $productAttributeValueMedia->uploadMedia($request->image[$attributeValueId], 'image');
                } else {
                    $newItem = ProductAttributeValueMedia::create([
                        'product_id' => base64_decode($product),
                        'attribute_value_id' => $attributeValueId,
                        'title' => $request->title[$attributeValueId] ?? null,
                        'description' => $request->description[$attributeValueId] ?? null,
                        'height' => $request->height[$attributeValueId] ?? null,
                        'width' => $request->width[$attributeValueId] ?? null,
                        'depth' => $request->depth[$attributeValueId] ?? null,
                        'price' => $request->price[$attributeValueId] ?? null,
                        'sort_order' => $request->sort_order[$attributeValueId] ?? null,
                        'is_default' => $isDefault,
                    ]);

                    $newItem->uploadMedia($request->image[$attributeValueId], 'image');
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product attribute value media updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
