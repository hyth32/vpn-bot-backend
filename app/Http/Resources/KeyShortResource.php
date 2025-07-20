<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyShortResource extends JsonResource
{
    public static $wrap = 'keys';

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'region_name' => $this->region->name,
            'region_flag' => $this->region->flag,
        ];
    }
}
