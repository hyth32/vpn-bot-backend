<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(schema="FreeKeyRequest", description="Запрос на получение бесплатного ключа", properties={
 *     @OA\Property(property="telegram_id", type="string", description="Telegram ID пользователя"),
 *     @OA\Property(property="region_id", type="string", description="ID региона"),
 * })
 */
class FreeKeyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|string|exists:users,telegram_id',
            'region_id' => 'required|integer|exists:regions,id',
        ];
    }
}
