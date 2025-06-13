<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'offset' => 'integer',
            'limit' => 'integer',
        ];
    }
}
