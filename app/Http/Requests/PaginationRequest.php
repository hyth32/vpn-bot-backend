<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    public function rules(): array
    {
        $this->mergeIfMissing([
            'offset' => 0,
            'limit' => config('app.limit_records_on_page'),
        ]);

        return [
            'offset' => 'integer',
            'limit' => 'integer',
        ];
    }
}
