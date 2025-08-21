<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceListRequest;
use App\Http\Resources\PriceResource;
use App\Http\Services\PriceService;

class PriceController extends Controller
{
    public function __construct(
        private readonly PriceService $priceService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/prices",
     *     tags={"Prices"},
     *     summary="Список стоимости по регионам",
     *     @OA\Parameter(
     *         name="region_id",
     *         in="query",
     *         description="ID региона",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="period_id",
     *         in="query",
     *         description="ID периода",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="quantity", type="integer", description="Количество ключей", example="1"),
     *                     @OA\Property(property="price", type="integer", example="100"),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function index(PriceListRequest $request)
    {
        $data = $request->validated();
        $prices = $this->priceService->listPrices(
            $data['region_id'],
            $data['period_id'],
            $data['offset'],
            $data['limit'],
        );
        
        return PriceResource::collection($prices);
    }
}
