<?php

namespace Webkul\Marketplace\Observers;

use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\ProductReviewRepository;
use Webkul\Product\Models\ProductReview;

class ProductReviewObserver
{
    /**
     * Handle the ProductReview "created" event.
     */
    public function created(ProductReview $productReview): void
    {
        $sellerProduct = app(ProductRepository::class)->findOneWhere([
            'product_id' => $productReview->product_id,
            'is_owner'   => 1,
        ]);

        if (! $sellerProduct) {
            return;
        }

        app(ProductReviewRepository::class)->create([
            'product_id'            => $sellerProduct->product_id,
            'product_review_id'     => $productReview->id,
            'marketplace_seller_id' => $sellerProduct->marketplace_seller_id,
            'customer_id'           => $productReview->customer_id,
        ]);
    }
}
