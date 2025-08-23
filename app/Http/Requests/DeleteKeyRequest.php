<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(schema="DeleteKeyRequest", description="Запрос на удаление ключа", properties={
 *     @OA\Property(property="telegram_id", type="string", description="Telegram ID пользователя"),
 * })
 */
class DeleteKeyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|string|exists:users,telegram_id',
        ];
    }
}
