<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Marketplace\Contracts\ProductReview as ReviewContract;
use Webkul\Product\Models\ProductReviewProxy as BaseProductReviewProxy;

class ProductReview extends Model implements ReviewContract
{
    /**
     * The table associated with the model.
     *
     * @var array
     */
    protected $table = 'marketplace_product_reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_review_id',
        'marketplace_seller_id',
        'customer_id',
    ];

    /**
     * Timestamps disabled.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Get the seller that belongs to the review.
     */
    public function seller()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Get the customer that belongs to the review.
     */
    public function review()
    {
        return $this->belongsTo(BaseProductReviewProxy::modelClass(), 'product_review_id');
    }
}
