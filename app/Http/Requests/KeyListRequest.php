<?php

namespace App\Http\Requests;

class KeyListRequest extends PaginationRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'telegram_id' => ['required', 'string', 'exists:users,telegram_id'],
        ];
    }
}
