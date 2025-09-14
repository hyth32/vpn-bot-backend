<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyResource extends JsonResource
{
    public ?array $metrics = [];

    public function __construct($resource, ?array $metrics = null)
    {
        parent::__construct($resource);
        $this->metrics = $metrics;
    }

    public function toArray(Request $request): array
    {
        $keyData = [
            'id' => $this->id,
            'name' => $this->config_name,
            'region_name' => $this->order->region->name,
            'expiration_date' => $this->expiration_date,
            'expired' => $this->isExpired(),
        ];

        if ($this->metrics) {
            $keyData['usage'] = $this->metrics['BytesTransmitted'];
        }
        
        return $keyData;
    }
}
