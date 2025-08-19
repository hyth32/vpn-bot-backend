<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(schema="KeyOrderRequest", type="object", required={"user_id", "region_id", "period_id"},
 *     @OA\Property(property="telegram_id", type="string", description="Telegram ID пользователя"),
 *     @OA\Property(property="region_id", type="integer", description="ID региона"),
 *     @OA\Property(property="period_id", type="integer", description="ID периода"),
 *     @OA\Property(property="quantity", type="integer", description="Количество ключей (от 1 до 5)", example=1, minimum=1, maximum=5)
 * ),
 */
class KeyOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|string|exists:users,telegram_id',
            'region_id' => 'required|integer|exists:regions,id',
            'period_id' => 'required|integer|exists:periods,id',
            'quantity' => 'integer|min:1|max:5',
        ];
    }
}
