<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Contracts\CommunicationMessage as CommunicationMessageContract;

class CommunicationMessage extends Model implements CommunicationMessageContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mp_communication_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mp_communication_id',
        'sender_type',
        'text',
        'attachment',
        'attachment_name',
        'attachment_type',
        'is_read',
    ];

    /**
     * Get attachment url attribute.
     */
    public function getAttachmentUrlAttribute()
    {
        if (! $this->attachment) {
            return;
        }

        return Storage::url($this->attachment);
    }

    /**
     * Get the communication that owns the message.
     */
    public function communication(): BelongsTo
    {
        return $this->belongsTo(CommunicationProxy::modelClass(), 'mp_communication_id');
    }
}
