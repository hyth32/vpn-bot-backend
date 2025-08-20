<?php

namespace App\Http\Requests;

class PriceListRequest extends PaginationRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'region_id' => 'required|integer|exists:regions,id',
            'period_id' => 'required|integer|exists:periods,id',
        ];
    }
}
