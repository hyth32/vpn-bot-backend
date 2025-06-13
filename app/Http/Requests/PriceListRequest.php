<?php

namespace App\Http\Requests;

class PriceListRequest extends PaginationRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'region_id' => 'integer|exists:regions,id',
        ];
    }
}
