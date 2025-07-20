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
            'region_name' => $this->region->name,
            'region_flag' => $this->region->flag,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
