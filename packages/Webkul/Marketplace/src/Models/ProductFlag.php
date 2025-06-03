<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Marketplace\Contracts\ProductFlag as ProductFlagContract;

class ProductFlag extends Model implements ProductFlagContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mp_product_flags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'reason',
        'customer_id',
        'marketplace_seller_id',
        'marketplace_product_id',
    ];

    /**
     * Get the customer that owns the flag.
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Get the seller that owns the flag.
     */
    public function seller()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the product that owns the flag.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass(), 'marketplace_product_id');
    }
}
