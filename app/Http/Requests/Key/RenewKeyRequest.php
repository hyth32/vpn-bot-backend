<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(schema="RenewKeyRequest", description="Запрос на продление ключа", properties={
 *     @OA\Property(property="telegram_id", type="string", description="Telegram ID пользователя"),
 *     @OA\Property(property="key_id", type="integer", description="ID ключа"),
 * })
 */
class RenewKeyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|string|exists:users,telegram_id',
        ];
    }
}
