<?php

namespace App\Http\Controllers;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\DeleteKeyRequest;
use App\Http\Requests\Key\FreeKeyRequest;
use App\Http\Requests\Key\GetConfigRequest;
use App\Http\Requests\Key\RenewKeyRequest;
use App\Http\Requests\Key\ShowKeyRequest;
use App\Http\Requests\KeyListRequest;
use App\Http\Requests\KeyOrderRequest;
use App\Http\Resources\KeyResource;
use App\Http\Resources\KeyShortResource;
use App\Http\Services\KeyService;
use Illuminate\Support\Facades\Response;

class KeyController extends Controller
{
    public function __construct(
        private KeyService $service,
        private UserRepository $userRepository,
        private KeyRepository $repository,
        private OrderRepository $orderRepository,
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
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/KeyShort"),
     *             ),
     *         )
     *     )
     * )
     */
    public function index(KeyListRequest $request)
    {
        $data = $request->validated();
        $userId = $this->userRepository->getIdFromTelegramId($data['telegram_id']);
        $response = $this->service->listKeys($userId, $data['offset'], $data['limit']);

        return [
            'data' => [
                'total' => $response['total'],
                'keys' => KeyShortResource::collection($response['keys']),
            ]
        ];
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

        $key = $this->service->showKey($keyId);
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

        $parsedConfig = $this->service->getConfig($keyId);

        $configName = $this->repository->getConfigName($keyId);

        return Response::make($parsedConfig, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$configName.conf\"",
        ]);
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
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/KeyResponseDto")
     *             ),
     *         )
     *     )
     * )
     */
    public function buy(KeyOrderRequest $request): array
    {
        $dto = KeyOrderDTO::fromRequest($request->validated());
        $response = $this->service->buyKey($dto);
        return $response->toArray();
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

        if ($this->userRepository->hasUsedFreeKey($telegramId)) {
            abort(403, 'Бесплатный ключ уже использован');
        }

        $dto = new KeyOrderDTO($telegramId, $regionId, 1, 1);
        return $this->service->getFreeKey($dto);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/keys/{keyId}/renew",
     *     tags={"Keys"},
     *     summary="Продление ключа",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RenewKeyRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/KeyResponseDto")
     *             ),
     *         )
     *     )
     * )
     */
    public function renew(int $keyId, RenewKeyRequest $request)
    {
        $data = $request->validated();
        $telegramId = $data['telegram_id'];

        $this->checkAccess($telegramId, $keyId);

        $isExpired = $this->repository->isKeyExpired($keyId);
        if (!$isExpired) {
            abort(400, 'Срок действия ключа еще не истек');
        }

        return $this->service->renewKey($telegramId, $keyId);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/keys/{keyId}",
     *     tags={"Keys"},
     *     summary="Удаление ключа",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RenewKeyRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function delete(int $keyId, DeleteKeyRequest $request)
    {
        $telegramId = $request->validated()['telegram_id'];
        $this->checkAccess($telegramId, $keyId);

        return $this->service->deleteKey($keyId);
    }

    private function checkAccess(string $telegramId, int $keyId): bool
    {
        $userId = $this->userRepository->getIdFromTelegramId($telegramId);
        $access = $this->orderRepository->keyExistsByUserId($userId, $keyId);

        if (!$access) {
            abort(403, 'Доступ запрещен');
        }

        return true;
    }
}
