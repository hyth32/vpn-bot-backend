<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(schema="RenewKeyRequest", description="Запрос на продление ключа", properties={
 *     @OA\Property(property="telegram_id", type="string", description="Telegram ID пользователя"),
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
