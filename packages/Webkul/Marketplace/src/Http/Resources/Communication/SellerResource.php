<?php

namespace Webkul\Marketplace\Http\Resources\Communication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'shop_title'    => $this->shop_title,
            'logo_url'      => $this->logo_url,
            'communication' => new CommunicationResource($this->communication),
        ];
    }
}
