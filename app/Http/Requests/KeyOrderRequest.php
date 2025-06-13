<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeyOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'region_id' => 'integer|exists:regions,id',
            'period_id' => 'integer|exists:periods,id',
        ];
    }
}
