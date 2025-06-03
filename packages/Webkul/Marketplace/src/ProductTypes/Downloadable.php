<?php

namespace Webkul\Marketplace\ProductTypes;

use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Product\Type\Downloadable as BaseDownloadable;

class Downloadable extends BaseDownloadable
{
    /**
     * Returns additional information for items.
     *
     * @param  array  $data
     * @return array
     */
    public function getAdditionalOptions($data)
    {
        $labels = [];

        if (
            isset($data['seller_info']['is_owner'])
            && $data['seller_info']['is_owner'] == 0
        ) {
            $product = app(ProductRepository::class)->find($data['seller_info']['product_id']);

            foreach ($product->downloadable_links as $link) {
                if (in_array($link->id, $data['links'])) {
                    $labels[] = $link->title;
                }
            }
        } else {
            foreach ($this->product->downloadable_links as $link) {
                if (in_array($link->id, $data['links'])) {
                    $labels[] = $link->title;
                }
            }
        }

        $data['attributes'][0] = [
            'attribute_name' => 'Downloads',
            'option_id'      => 0,
            'option_label'   => implode(', ', $labels),
        ];

        return $data;
    }
}
