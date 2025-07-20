<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'period_id' => $this->period->id,
            'period_name' => $this->period->name,
            'price' => $this->amount,
        ];
    }
}
