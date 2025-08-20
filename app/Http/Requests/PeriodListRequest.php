<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|string|exists:users,telegram_id',
        ];
    }
}
