<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository as Repository;

class DownloadableLinkPurchasedRepository extends Repository
{
    /**
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function saveLinks($orderItem)
    {
        if (! $this->isValidDownloadableProduct($orderItem)) {
            return;
        }

        parent::deleteWhere(['order_item_id' => $orderItem->id]);

        foreach ($orderItem->additional['links'] as $linkId) {
            if (! $productDownloadableLink = app(ProductDownloadableLinkRepository::class)->find($linkId)) {
                continue;
            }

            parent::create([
                'name'            => $productDownloadableLink->title,
                'product_name'    => $orderItem->name,
                'url'             => $productDownloadableLink->url,
                'file'            => $productDownloadableLink->file,
                'file_name'       => $productDownloadableLink->file_name,
                'type'            => $productDownloadableLink->type,
                'download_bought' => $productDownloadableLink->downloads * $orderItem->qty_ordered,
                'status'          => 'pending',
                'customer_id'     => $orderItem->order->customer_id,
                'order_id'        => $orderItem->order_id,
                'order_item_id'   => $orderItem->id,
            ]);
        }
    }

    /**
     * Return true, if ordered item is valid downloadable product with links
     *
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     */
    private function isValidDownloadableProduct($orderItem): bool
    {
        return stristr($orderItem->type, 'downloadable') !== false
            && isset($orderItem->additional['links'])
            && ! empty($orderItem->additional['seller_info'])
            && isset($orderItem->additional['seller_info']['is_owner'])
            && $orderItem->additional['seller_info']['is_owner'] === 0;
    }
}
