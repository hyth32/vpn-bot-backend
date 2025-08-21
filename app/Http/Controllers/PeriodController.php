<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeriodListRequest;
use App\Http\Resources\PeriodResource;
use App\Http\Services\PeriodService;
use App\Models\User;

class PeriodController
{
    public function __construct(
        private PeriodService $service,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/periods",
     *     tags={"Periods"},
     *     summary="Список периодов",
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
     *                 @OA\Items(ref="#/components/schemas/Region"),
     *             ),
     *         )
     *     )
     * )
     */
    public function index(PeriodListRequest $request)
    {
        $telegramId = $request->validated()['telegram_id'];
        $user = User::where('telegram_id', $telegramId)->first();
        $periods = $this->service->listPeriods($user);
        return PeriodResource::collection($periods);
    }
}
