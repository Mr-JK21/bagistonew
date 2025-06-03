<?php

namespace Webkul\Marketplace\Listeners;

use Webkul\Marketplace\Helpers\Indexers\Product as ProductIndexerHelper;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Product\Helpers\Indexers\ElasticSearch as ElasticSearchHelper;
use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ElasticSearchHelper $elasticSearchHelper,
        protected ProductIndexerHelper $productIndexerHelper,
    ) {}

    /**
     * Update product for seller if Seller is owner
     */
    public function afterUpdate($product)
    {
        if (
            (
                request()->get('value') == 1
                && request()->route()->getName() == 'admin.catalog.products.mass_update'
            ) || (
                request()->get('status') == 1
                && request()->route()->getName() == 'admin.catalog.products.update'
            )
        ) {
            $sellerProduct = $this->productRepository->findOneWhere([
                'product_id' => $product->id,
                'is_owner'   => 1,
            ]);

            if ($sellerProduct) {
                $this->productRepository->update([
                    'is_approved' => 1,
                ], $sellerProduct->id);
            }
        }
    }

    /**
     * Update product for seller if Seller is owner
     */
    public function afterSellerProductUpdate($product)
    {
        if ($product->is_owner == 0) {
            return;
        }

        if (
            core()->getConfigData('catalog.products.search.engine') == 'elastic'
            && core()->getConfigData('catalog.products.search.storefront_mode') == 'elastic'
        ) {
            if ($product->is_approved == 1) {
                $this->elasticSearchHelper->reindexBatch([$product->product]);
            } else {
                $this->productIndexerHelper->deleteIndex($product->product);
            }
        }
    }

    /**
     * Update product for seller if Seller is owner
     */
    public function afterAssignProductUpdateOrCreate($product)
    {
        if ($product->is_owner == 1) {
            return;
        }

        $productIds = $this->productIndexerHelper->getAllRelatedProductIds($product->product);

        UpdateCreateInventoryIndexJob::dispatch($productIds);
    }
}
