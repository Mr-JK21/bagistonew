<?php

namespace Webkul\Marketplace\Helpers\Indexers;

use Webkul\Product\Helpers\Indexers\ElasticSearch;
use Webkul\Product\Jobs\ElasticSearch\DeleteIndex as DeleteElasticSearchIndexJob;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class Product
{
    /**
     * Batch size for indexing
     */
    public const BATCH_SIZE = 100;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ElasticSearch $elasticSearch,
        protected ProductRepository $productRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository
    ) {}

    /**
     * Reindex the products
     *
     * @param  \Webkul\Marketplace\Contracts\Seller  $seller
     * @param  bool  $reIndex
     * @return void
     */
    public function createOrRemoveIndices($seller = null, $reIndex = true)
    {
        while (true) {
            $paginator = $this->productRepository->scopeQuery(function ($query) use ($seller) {
                return $query
                    ->join('marketplace_products', 'products.id', '=', 'marketplace_products.product_id')
                    ->select('products.*')
                    ->distinct()
                    ->when($seller, function ($query) use ($seller) {
                        return $query->where('marketplace_products.marketplace_seller_id', $seller->id);
                    })
                    ->where('marketplace_products.is_owner', 1);
            })
                ->with([
                    'channels',
                    'categories',
                    'inventories',
                    'super_attributes',
                    'variants',
                    'variants.channels',
                    'attribute_family',
                    'attribute_values',
                    'variants.attribute_family',
                    'variants.attribute_values',
                    'price_indices',
                    'variants.price_indices',
                    'inventory_indices',
                    'variants.inventory_indices',
                ])
                ->cursorPaginate(self::BATCH_SIZE);

            $products = $paginator->items();

            if ($reIndex) {
                $this->elasticSearch->reindexBatch($products);
            } else {
                foreach ($products as $product) {
                    $this->deleteIndex($product);
                }
            }

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }

        request()->query->remove('cursor');
    }

    /**
     * Delete the product index
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function deleteIndex($product)
    {
        $productIds = $this->getAllRelatedProductIds($product);

        DeleteElasticSearchIndexJob::dispatch($productIds);
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllRelatedProductIds($product)
    {
        $productIds = [$product->id];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $productIds[] = $product->parent_id;
            }

            $productIds = array_merge(
                $productIds,
                $this->getParentBundleProductIds($product),
                $this->getParentGroupProductIds($product)
            );
        } elseif ($product->type == 'configurable') {
            $productIds = [
                ...$product->variants->pluck('id')->toArray(),
                ...$productIds,
            ];
        }

        return $productIds;
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentBundleProductIds($product)
    {
        $bundleOptionProducts = $this->productBundleOptionProductRepository->findWhere([
            'product_id' => $product->id,
        ]);

        $productIds = [];

        foreach ($bundleOptionProducts as $bundleOptionProduct) {
            $productIds[] = $bundleOptionProduct->bundle_option->product_id;
        }

        return $productIds;
    }

    /**
     * Returns parents group product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentGroupProductIds($product)
    {
        $groupedOptionProducts = $this->productGroupedProductRepository->findWhere([
            'associated_product_id' => $product->id,
        ]);

        return $groupedOptionProducts->pluck('product_id')->toArray();
    }
}
