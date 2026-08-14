<?php

namespace Modules\Admin\Utils;

use App\Models\AppSettings;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductUtility
{
    public function generateProductSku($string)
    {
        return app('appSettings')->get('catalog.sku_prefix')->value . str_pad($string, 4, '0', STR_PAD_LEFT);
    }

    public function generateVariationCombinations($attributes, $productId)
    {
        $optionValues = collect($attributes)
            ->filter(
                fn($option) => $option['name']
            )
            ->mapWithKeys(
                fn($option) => [$option['name'] => array_map(
                    fn($value) =>
                    $value['value'],
                    $option['values']
                )]
            )->toArray();

        $permutations = $this->permutate($optionValues); // Generate all permutations

        if (count($optionValues) == 1) {
            $newPermutations = [];
            foreach ($permutations as $p) {
                $newPermutations[] = [
                    array_key_first($optionValues) => $p,
                ];
            }
            $permutations = $newPermutations;
        }

        $variantPermutations = [];
        foreach ($permutations as $p) {
            $combinationName = [];
            foreach ($p as $attributeName  => $attributeValue) {
                $attribute = array_search($attributeName, array_column($attributes, 'name'));
                if ($attribute !== false) {
                    $attributeValue = array_search($attributeValue, array_column($attributes[$attribute]['values'], 'value'));
                    if ($attributeValue !== false) {
                        $combinationName[] = $attributes[$attribute]['values'][$attributeValue]['id'];
                    }
                }
            }

            $sku = '';
            $price = 0;
            $offerPrice = null;
            $variantName = implode(',', $combinationName);
            $variantId = null;
            $stock = 0;
            if ($productId) {
                // check already exists
                $variant = ProductVariant::where('product_id', $productId)->where('variant_name', $variantName)->first();
                if ($variant) {
                    $sku = $variant->sku;
                    $price = $variant->price;
                    $offerPrice = $variant->offer_price;
                    $variantId = $variant->id;
                    $stock = $variant->stock;
                }
            }

            $variantPermutations[] = [
                'values' => $p,
                'combination_name' => $variantName,
                'product_variant_id' => $variantId,
                'sku' => $sku,
                'price' => $price,
                'offer_price' => $offerPrice,
                'stock' => $stock
            ];
        }

        return $variantPermutations;
    }

    public function permutate($setTuples, $isRecursiveStep = false)
    {
        $countTuples = count($setTuples);

        if ($countTuples === 1) {
            return reset($setTuples);
        }

        if ($countTuples === 0) {
            return [];
        }

        foreach ($setTuples as $tuple) {
            if (! is_array($tuple)) {
                throw new \InvalidArgumentException('The set builder requires a single array of one or more array sets.');
            }
        }

        $keys = array_keys($setTuples);
        $a = array_shift($setTuples);
        $k = array_shift($keys);

        $b = self::permutate($setTuples, true);

        $result = [];

        foreach ($a as $valueA) {
            if ($valueA) {
                foreach ($b as $valueB) {
                    if ($isRecursiveStep) {
                        $result[] = array_merge([$valueA], (array) $valueB);
                    } else {
                        $result[] = [$k => $valueA] + array_combine($keys, (array) $valueB);
                    }
                }
            }
        }

        return $result;
    }

    protected function buildVariantPayload(Product $product, string $sku, $rawPrice, $rawOfferPrice, $rawStock, ?string $variantName = null): array
    {
        $discount = $this->calculateDiscountDetails($rawPrice, $rawOfferPrice);

        return [
            'product_id' => $product->id,
            'variant_name' => $variantName,
            'sku' => $sku,
            'price' => (float) ($rawPrice ?? 0),
            'discount_amount' => $discount['discount_amount'],
            'discount_percentage' => $discount['discount_percentage'],
            'offer_price' => $discount['offer_price'],
            'stock' => (int) ($rawStock ?? 0),
        ];
    }

    public function generateProductByType($product, $request, $isFromEdit = false)
    {
        match ($product->type) {
            'single'   => $this->generateSingleProduct($product, $request),
            'variable' => $this->generateProductVariants($product, $request, $isFromEdit),
            default    => throw new \InvalidArgumentException("Unknown product type: {$product->type}"),
        };
    }

    public function generateSingleProduct($product, $request)
    {
        // For single products, ensure no variable variants remain
        $product->variants->each->delete();

        $sku = $product->sku; // single variant uses product SKU
        $payload = $this->buildVariantPayload(
            $product,
            $sku,
            $request->price,
            $request->offer_price,
            $request->stock,
            null // variant_name = null for single variant
        );

        // Upsert the single variant
        $product->singleVariant()->updateOrCreate([], $payload);
    }

    public function generateProductVariants($product, $request, $isFromEdit)
    {
        // If the product previously had a singleVariant, remove it.
        if ($product->singleVariant()->exists()) {
            $product->singleVariant()->delete();
        }

        // Collect request data into arrays for easier iteration
        $combinations = (array) $request->combination_name;
        $combinationIds = (array) ($request->combination_id ?? []);
        $variantSkus = (array) ($request->product_variant_sku ?? []);
        $prices = (array) ($request->price ?? []);
        $offerPrices = (array) ($request->offer_price ?? []);
        $stocks = (array) ($request->stock ?? []);

        $keptVariantIds = [];

        if ($isFromEdit) {
            // On edit: clear any cart/wishlist entries tied to old variants
            $this->deleteProductVariantFromCartAndWishlist($product->id);
        }

        foreach ($combinations as $idx => $combination) {
            // Prepare variant input values for this row
            $sku = !empty($variantSkus[$idx]) ? $variantSkus[$idx] : $this->generateSkuForProductVariant($product);
            $price = $prices[$idx] ?? 0;
            $offerPrice = $offerPrices[$idx] ?? null;
            $stock = $stocks[$idx] ?? 0;

            // Build normalized variant payload (handles price, discount, etc.)
            $payload = $this->buildVariantPayload(
                $product,
                $sku,
                $price,
                $offerPrice,
                $stock,
                (string) $combination // variant_name
            );

            // Either update an existing variant (if editing) or create a new one
            if ($isFromEdit && !empty($combinationIds[$idx])) {
                $variant = ProductVariant::find($combinationIds[$idx]);
                if ($variant)
                    $variant->update($payload);
                else
                    $variant = $product->variants()->create($payload);
            } else {
                $variant = $product->variants()->create($payload);
            }

            $keptVariantIds[] = $variant->id;

            // Sync attribute values for this variant
            $ids = collect(array_filter(explode(',', (string) $combination)))
                ->mapWithKeys(function ($id) use ($product) {
                    $og = AttributeValue::select('id', 'attribute_id')->find($id);
                    if ($og) {
                        return [
                            $og->id => [
                                'product_id' => $product->id,
                                'attribute_id' => $og->attribute_id,
                            ]
                        ];
                    }
                    return [];
                })
                ->all();

            $variant->attributeValues()->sync($ids);
        }

        // On edit: delete any variants that were not re-sent in this request
        if ($isFromEdit) {
            $product->variants()
                ->whereNotIn('id', $keptVariantIds)
                ->delete();
        }
    }

    public function generateSkuForProductVariant($product): string
    {
        $baseSku = $product->sku;

        // Get all variant SKUs for this product (including trashed ones)
        $lastNumber = ProductVariant::withTrashed()
            ->where('product_id', $product->id)
            ->where('sku', 'like', $baseSku . '-%')
            ->pluck('sku')
            ->map(function ($sku) use ($baseSku) {
                return (int) str_replace($baseSku . '-', '', $sku);
            })
            ->max();

        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        return $baseSku . '-' . $nextNumber;
    }

    public function deleteProductVariantFromCartAndWishlist($id)
    {
        $sessionId = session()->getId();
        $userId = Auth::guard('web')->id();

        // delete the product from cart and wishlist from sessions
        $sessions = DB::table('sessions')->get();

        foreach ($sessions as $session) {
            $data = unserialize(base64_decode($session->payload));
            $modified = false;
            $productId = $id;
            // Check if session has data we need to modify
            // if (!empty($data)) {
            //     if (isset($data['cart'])) {

            //         if (is_array($data['cart']) && in_array($productId, $data['cart'])) {
            //             // Remove the product ID from the array
            //             $data['cart'] = Cart::when($userId, function ($q) use ($userId) {
            //                 $q->where('customer_id', $userId);
            //             })->when($userId == null, function ($q) use ($sessionId) {
            //                 $q->where('session_id', $sessionId);
            //             })->whereHas('product', function ($q) {
            //                 $q->active();
            //             })->pluck('product_id')->toArray();
            //             $modified = true;
            //         }
            //     }

            //     if ($modified) {
            //         $encodedPayload = base64_encode(serialize($data));
            //         DB::table('sessions')
            //             ->where('id', $session->id)
            //             ->update(['payload' => $encodedPayload]);

            //         // 1. Store the original session ID
            //         $originalSessionId = session()->getId();

            //         // 2. Switch to the target session
            //         session()->setId($session->id);
            //         session()->start(); // Ensure the session starts
            //         session()->forget(['cart']);
            //         session()->save(); // Save the session after modification

            //         // 3. Restore the original session
            //         session()->setId($originalSessionId);
            //         session()->start(); // Restart the original session
            //     }
            // }
        }
    }

    public function calculateDiscountDetails($price = null, $offerPrice = null): array
    {
        $price = (float) ($price ?? 0);
        $offerPrice = $offerPrice !== null ? (float) $offerPrice : null;

        // Normalize invalid offer price
        if ($offerPrice === null || $offerPrice < 0 || $offerPrice >= $price) {
            $offerPrice = null;
        }

        $discountAmount = $offerPrice !== null ? max(0, $price - $offerPrice) : null;
        $discountPercentage = ($discountAmount !== null && $price > 0)
            ? round(($discountAmount / $price) * 100, 2)
            : null;

        return [
            'discount_amount' => $discountAmount,
            'discount_percentage' => $discountPercentage,
            'offer_price' => $offerPrice,
        ];
    }
}
