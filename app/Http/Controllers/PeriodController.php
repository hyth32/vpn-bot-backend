<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeriodResource;
use App\Http\Services\PeriodService;

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
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Region"),
     *             ),
     *         )
     *     )
     * )
     */
    public function index()
    {
        $periods = $this->service->listPeriods();
        return ['periods' => PeriodResource::collection($periods)];
    }
}
