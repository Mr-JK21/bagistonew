<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Marketplace\Contracts\SellerFlag as SellerFlagContract;

class SellerFlag extends Model implements SellerFlagContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mp_seller_flags';

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
}
