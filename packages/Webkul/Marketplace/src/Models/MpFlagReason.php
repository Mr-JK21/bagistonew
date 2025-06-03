<?php

namespace Webkul\Marketplace\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Marketplace\Contracts\MpFlagReason as MpFlagReasonContract;
use Webkul\Marketplace\Enums\FlagReasonType;

class MpFlagReason extends TranslatableModel implements MpFlagReasonContract
{
    /**
     * The translatable attributes of the model.
     *
     * @var string
     */
    public $translatedAttributes = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_name',
        'status',
        'type',
    ];

    /**
     * Get the seller flag reasons.
     */
    public function scopeSellerType($query)
    {
        return $query->where([
            'status' => 1,
            'type'   => FlagReasonType::SELLER->value,
        ]);
    }

    /**
     * Get the product flag reasons.
     */
    public function scopeProductType($query)
    {
        return $query->where([
            'status' => 1,
            'type'   => FlagReasonType::PRODUCT->value,
        ]);
    }
}
