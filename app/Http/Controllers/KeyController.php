<?php

namespace App\Http\Controllers;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Requests\KeyListRequest;
use App\Http\Requests\KeyOrderRequest;
use App\Http\Resources\KeyResource;
use App\Http\Resources\KeyShortResource;
use App\Http\Services\KeyService;
use App\Models\Key;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    public function __construct(
        private readonly KeyService $keyService,
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
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Telegram ID пользователя",
     *         required=true,
     *         @OA\Schema(type="string")
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
    public function index(KeyListRequest $request)
    {
        $data = $request->validated();
        $keys = $this->keyService->listKeys($data);
        return KeyShortResource::collection($keys);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/keys/{keyId}",
     *     tags={"Keys"},
     *     summary="Детали ключа",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Telegram ID пользователя",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
    public function show(int $keyId, Request $request)
    {
        $key = $this->keyService->showKey($keyId);
        return KeyResource::make($key);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/keys/{keyId}/config",
     *     tags={"Keys"},
     *     summary="Получение конфига ключа",
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
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function config(int $keyId, Request $request)
    {
        return $this->keyService->getConfig($keyId);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/keys/checkout",
     *     tags={"Keys"},
     *     summary="Создание заказа на покупку ключа",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/KeyOrderRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="url", type="string", example="https://payment.example.com/checkout/123")
     *         ),
     *     )
     * )
     */
    public function buy(KeyOrderRequest $request)
    {
        $dto = KeyOrderDTO::fromRequest($request->validated());
        return $this->keyService->buyKey($dto);
    }
}
