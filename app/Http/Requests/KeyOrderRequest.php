<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeyOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'region_id' => 'required|integer|exists:regions,id',
            // 'period_id' => 'required|integer|exists:periods,id',
            'region_id' => 'required|integer',
            'period_id' => 'required|integer',
        ];
    }
}
