<?php

namespace Webkul\Marketplace\Http\Resources\Communication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommunicationMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'id'                  => $this->id,
            'mp_communication_id' => $this->mp_communication_id,
            'sender_type'         => $this->sender_type,
            'text'                => $this->text,
            'attachment_url'      => $this->attachment
                ? Storage::url($this->attachment)
                : null,
            'attachment_name'     => $this->attachment_name,
            'attachment_type'     => explode('/', $this->attachment_type)[0],
            'timestamp'           => $this->created_at->format('M d, Y h:i A'),
        ];
    }
}
