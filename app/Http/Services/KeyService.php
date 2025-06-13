<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\DTOs\PaginationDTO;
use App\Http\DTOs\PaymentDTO;
use App\Http\Integrations\YooKassaService;

class KeyService
{
    public function __construct(
        private readonly YooKassaService $yooKassaService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/keys",
     *     tags={"Keys"},
     *     summary="Список ключей пользователя",
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Смещение для пагинации",
     *         required=true,
     *         @OA\Schema(type="integer", default=0)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Количество записей на странице",
     *         required=true,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="region_name", type="string", example="US"),
     *                     @OA\Property(property="region_flag", type="string", example="🇺🇸"),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function listKeys(PaginationDTO $pagination)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/keys/{keyId}",
     *     tags={"Keys"},
     *     summary="Детали ключа",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="key", type="string", example="qwe-123-ewq"),
     *                     @OA\Property(property="region_name", type="string", example="US"),
     *                     @OA\Property(property="region_flag", type="string", example="🇺🇸"),
     *                     @OA\Property(
     *                         property="expiration_date",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-03-20T15:30:00Z"
     *                     ),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function getKey(int $keyId)
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/checkout",
     *     tags={"Keys"},
     *     summary="Создание заказа на покупку ключа",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"region_id", "period_id"},
     *             @OA\Property(property="region_id", type="integer", example=1),
     *             @OA\Property(property="period_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="url", type="string", example="https://payment.example.com/checkout/123")
     *         )
     *     )
     * )
     */
    public function buyKey(KeyOrderDTO $dto)
    {
        $paymentObject = PaymentDTO::create(
            amount: 10,
            description: 'test',
        )->toArray();
        
        return $this->yooKassaService->createPayment($paymentObject);
    }
}
