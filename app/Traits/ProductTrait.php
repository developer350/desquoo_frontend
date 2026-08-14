<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\ProductVariantAttribute;

trait ProductTrait
{
    public function getBaseQuery()
    {
        return Product::query()
            ->select('products.id', 'products.name', 'products.slug', 'products.type', 'products.is_best_seller', 'products.is_manage_stock', 'products.status', 'products.category_id')
            ->where(['products.status' => 1])
            ->with([
                'firstVariant' => function ($query) {
                    $query
                        ->select('product_variants.id', 'product_variants.product_id', 'product_variants.price', 'product_variants.offer_price', 'product_variants.stock', 'product_variants.status')
                        ->orderByRaw(
                            "
                        CASE
                            WHEN product_variants.stock > 0 THEN 1
                            ELSE 2
                        END,
                        COALESCE(product_variants.offer_price, product_variants.price) ASC
                    ",
                        )
                        ->where('product_variants.status', 1)
                        ->orderBy('product_variants.id');
                },
                'productDefaultAttributeValues',
                'media',
            ]);
    }

    public function getSelectedAttributes($product)
    {
        $selectedAttributes = ProductVariantAttribute::select('attribute_id', 'attribute_value_id', 'product_variant_id', 'product_id')
            ->with([
                'attribute',
                'attributeValue' => function ($q) {
                    $q->with('media');
                },
            ])
            ->whereHas('productVariant', function ($q) {
                $q->where('status', 1);
            })
            ->where('product_id', $product->id)
            ->get()
            ->groupBy('attribute_id')
            ->map(function ($items) use ($product) {
                $firstItem = $items->first();
                $attribute = $firstItem->attribute;

                // Get unique attribute values without additional queries
                $values = $items->map(function ($item) use ($attribute, $product) {
                    return [
                        'id' => $item->attributeValue->id,
                        'value' => $item->attributeValue->value,
                        'icon' => $item->attributeValue->icon_value,
                    ];
                })->unique('id')->values();

                return [
                    'attribute' => $attribute,
                    'values' => $values,
                ];
            })
            ->values();

        return $selectedAttributes
            ->sortBy(function ($item) {
                return $item['attribute']->sort_order;
            })
            ->values();
    }
}
