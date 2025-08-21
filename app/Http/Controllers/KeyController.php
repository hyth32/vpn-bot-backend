<?php

namespace App\Http\Controllers;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\Key\FreeKeyRequest;
use App\Http\Requests\Key\GetConfigRequest;
use App\Http\Requests\Key\ShowKeyRequest;
use App\Http\Requests\KeyListRequest;
use App\Http\Requests\KeyOrderRequest;
use App\Http\Resources\KeyResource;
use App\Http\Resources\KeyShortResource;
use App\Http\Services\KeyService;

class KeyController extends Controller
{
    public function __construct(
        private KeyService $keyService,
        private UserRepository $userRepository,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/keys",
     *     tags={"Keys"},
     *     summary="Список ключей пользователя",
     *     @OA\Parameter(
     *         name="telegram_id",
     *         in="query",
     *         description="Telegram ID пользователя",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
    public function index(KeyListRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepository->findByTelegramId($data['telegram_id']);
        $keys = $this->keyService->listKeys($user->id, $data['offset'], $data['limit']);
        return KeyShortResource::collection($keys);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/keys/{keyId}",
     *     tags={"Keys"},
     *     summary="Детали ключа",
     *     @OA\Parameter(
     *         name="telegram_id",
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
     *                 @OA\Items(ref="#/components/schemas/Key"),
     *             ),
     *         )
     *     )
     * )
     */
    public function show(int $keyId, ShowKeyRequest $request)
    {
        $telegramId = $request->validated()['telegram_id'];
        $this->checkAccess($telegramId, $keyId);

        $key = $this->keyService->showKey($keyId);
        return KeyResource::make($key);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/keys/{keyId}/config",
     *     tags={"Keys"},
     *     summary="Получение конфига ключа",
     *     @OA\Parameter(
     *         name="telegram_id",
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
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function config(int $keyId, GetConfigRequest $request)
    {
        $telegramId = $request->validated()['telegram_id'];
        $this->checkAccess($telegramId, $keyId);

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
     *             @OA\Property(property="order_data", type="object",
     *                 @OA\Property(property="region_name", type="string", description="Название региона"),
     *                 @OA\Property(property="period_name", type="string", description="Название периода"),
     *                 @OA\Property(property="quantity", type="integer", description="Количество ключей"),
     *                 @OA\Property(property="amount", type="integer", description="Стоимость покупки"),
     *                 @OA\Property(property="payment_link", type="string", description="Ссылка на оплату"),
     *             ),
     *         ),
     *     )
     * )
     */
    public function buy(KeyOrderRequest $request)
    {
        $dto = KeyOrderDTO::fromRequest($request->validated());
        return ['order_data' => $this->keyService->buyKey($dto)];
    }

    /**
     * @OA\Post(
     *     path="/api/v1/keys/free-key",
     *     tags={"Keys"},
     *     summary="Получение бесплатного ключа",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FreeKeyRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="config", type="string", description="Конфиг"),
     *         ),
     *     )
     * )
     */
    public function freeKey(FreeKeyRequest $request)
    {
        $data = $request->validated();
        $telegramId = $data['telegram_id'];
        $regionId = $data['region_id'];
        $user = $this->userRepository->findByTelegramId($telegramId);

        if ($user->isFreeKeyUsed()) {
            abort(403, 'Бесплатный ключ уже использован');
        }

        $dto = new KeyOrderDTO($telegramId, $regionId, 1, 1);
        $config = $this->keyService->acceptPayment($dto);

        $user->setFreeKeyUsed();

        return ['config' => $config];
    }

    /**
     * @OA\Post(
     *     path="/api/v1/keys/accept-payment",
     *     tags={"Keys"},
     *     summary="(ВРЕМЕННО) Подтверждение платежа",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/KeyOrderRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="config", type="string", description="Конфиг"),
     *         ),
     *     )
     * )
     */
    public function acceptPayment(KeyOrderRequest $request)
    {
        $dto = KeyOrderDTO::fromRequest($request->validated());
        return ['config' => $this->keyService->acceptPayment($dto)];
    }

    private function checkAccess(string $telegramId, int $keyId): bool
    {
        $user = $this->userRepository->findByTelegramId($telegramId);
        $access = $user->keys()->where('id', $keyId)->exists();

        if (!$access) {
            abort(403, 'Доступ запрещен');
        }

        return true;
    }
}
