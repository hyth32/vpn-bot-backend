<?php

namespace App\Http\DTOs;

/**
 * @OA\Schema(schema="KeyResponseDto", description="Данные покупаемого ключа", properties={
 *     @OA\Property(property="region_name", type="string", description="Название региона"),
 *     @OA\Property(property="period_name", type="string", description="Название периода"),
 *     @OA\Property(property="quantity", type="integer", description="Количество ключей"),
 *     @OA\Property(property="amount", type="integer", description="Сумма"),
 *     @OA\Property(property="payment_link", type="string", description="Ссылка на оплату"),
 * })
 */
class KeyResponseDTO extends BaseDTO
{
    public function __construct(
        public string $region_name,
        public string $period_name,
        public int $quantity,
        public float $amount,
        public string $payment_link,
    ) {}
}
