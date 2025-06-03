<?php

namespace Webkul\Marketplace\Helpers;

use Webkul\Marketplace\Contracts\Product;

class Cart
{
    /**
     * Returns additional data for the cart item.
     */
    public function getAdditionalData(Product $product): array
    {
        return [
            'product_id'  => $product->product->id,
            'seller_info' => [
                'product_id' => $product->id,
                'seller_id'  => $product->marketplace_seller_id,
                'is_owner'   => $product->is_owner,
            ],
            'attributes' => [
                'seller_info' => [
                    'attribute_name' => trans('marketplace::app.shop.checkout.cart.sold-by'),
                    'option_label'   => $product->seller->shop_title,
                ],
            ],
        ];
    }

    /**
     * Returns additional data for the cart item.
     */
    public function getAdditionalOptions($product): array
    {
        $data = [];

        foreach ($product->parent->super_attributes as $attribute) {
            $option = $attribute->options()
                ->where('id', $product->{$attribute->code})
                ->first();

            if ($option) {
                $data[$attribute->code] = [
                    'attribute_name' => $attribute->name ?? $attribute->admin_name,
                    'option_id'      => $option->id,
                    'option_label'   => $option->label ?? $option->admin_name,
                ];
            }
        }

        return $data;
    }
}
