<?php

namespace Webkul\Marketplace\Http\Resources\Communication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CommunicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray(Request $request)
    {
        $date = Carbon::parse($this->updated_at);

        if ($date->isYesterday()) {
            $timeStamp = $date->diffForHumans();
        } elseif ($date->isBefore(Carbon::yesterday())) {
            $timeStamp = $date->format('Y-m-d');
        } else {
            $timeStamp = $date->format('h:i A');
        }

        return [
            'id'                    => $this->id,
            'marketplace_seller_id' => $this->marketplace_seller_id,
            'subject'               => $this->subject,
            'timestamp'             => $timeStamp,
            'is_blocked'            => $this->is_blocked,
            'seller_unread_count'   => $this->seller_unread_count,
            'admin_unread_count'    => $this->admin_unread_count,
            'messages'              => CommunicationMessageResource::collection($this->messages),
        ];
    }
}
