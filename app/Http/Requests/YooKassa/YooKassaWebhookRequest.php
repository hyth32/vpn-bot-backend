<?php

namespace App\Http\Requests\YooKassa;

use Illuminate\Foundation\Http\FormRequest;

class YooKassaWebhookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'event' => 'required|string',
        ];
    }
}
