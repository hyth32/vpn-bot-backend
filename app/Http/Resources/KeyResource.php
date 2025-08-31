<?php

namespace App\Http\Resources;

use App\Http\Repositories\PriceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $region = $this->order->region;
        $period = $this->order->period;

        $price = (new PriceRepository)->getPrice($region->id, $period->id);

        return [
            'id' => $this->id,
            'name' => $this->config_name,
            'region_name' => $this->order->region->name,
            'expiration_date' => $this->expiration_date,
            'amount' => $price,
        ];
    }
}
