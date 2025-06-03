<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Marketplace\Contracts\Communication as CommunicationContract;
use Webkul\Marketplace\Enums\SenderType;

class Communication extends Model implements CommunicationContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mp_communications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'marketplace_seller_id',
        'subject',
        'is_blocked',
    ];

    /**
     * Get unread count for the seller.
     */
    public function getSellerUnreadCountAttribute()
    {
        return $this->messages()
            ->where('sender_type', SenderType::ADMIN->value)
            ->where('is_read', 0)
            ->count();
    }

    /**
     * Get unread count for the admin.
     */
    public function getAdminUnreadCountAttribute()
    {
        return $this->messages()
            ->where('sender_type', SenderType::SELLER->value)
            ->where('is_read', 0)
            ->count();
    }

    /**
     * Get the messages for the communication.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(CommunicationMessageProxy::modelClass(), 'mp_communication_id');
    }

    /**
     * Get the seller that owns the message.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'marketplace_seller_id');
    }
}
