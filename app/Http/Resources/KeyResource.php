<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->config_name,
            'region_name' => $this->order->region->name,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
