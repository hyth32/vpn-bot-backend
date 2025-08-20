<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

class GetConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|string|exists:users,telegram_id',
        ];
    }
}
