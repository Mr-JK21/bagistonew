<?php

namespace Webkul\Marketplace\ProductTypes;

use Webkul\Checkout\Facades\Cart;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Product\Contracts\ProductInventoryIndex;
use Webkul\Product\Type\Simple as BaseSimple;

class Simple extends BaseSimple
{
    /**
     * Returns product inventory index of current channel.
     *
     * @return ProductInventoryIndex
     */
    public function getInventoryIndex()
    {
        $indices = $this->product->inventory_indices->filter(function ($index) {
            return $index->channel_id === core()->getCurrentChannel()->id;
        });

        $sellerId = Cart::getCurrentProductSellerId();

        if (! $sellerId) {
            $sellerProduct = app(ProductRepository::class)->findOneWhere([
                'product_id' => $this->product->id,
                'is_owner'   => 1,
            ]);

            if (
                $sellerProduct?->is_approved
                && $sellerProduct?->seller?->is_approved
            ) {
                $vendorId = $sellerProduct->marketplace_seller_id;
            } else {
                $vendorId = 0;
            }
        } else {
            $vendorId = $sellerId;
        }

        return $indices->firstWhere('vendor_id', $vendorId);
    }

    /**
     * Compare options.
     *
     * @param  array  $options1
     * @param  array  $options2
     * @return bool
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->product->id != $options2['product_id']) {
            return false;
        } else {
            if (
                isset($options1['parent_id'])
                && isset($options2['parent_id'])
            ) {
                return $options1['parent_id'] == $options2['parent_id'];
            } elseif (
                isset($options2['seller_info'])
                && ! isset($options1['seller_info'])
            ) {
                return false;
            } elseif (
                isset($options1['seller_info'])
                && ! isset($options21['seller_info'])
            ) {
                return false;
            } elseif (
                isset($options1['parent_id'])
                && ! isset($options2['parent_id'])
            ) {
                return false;
            } elseif (
                isset($options2['parent_id'])
                && ! isset($options1['parent_id'])
            ) {
                return false;
            }
        }

        return true;
    }
}
