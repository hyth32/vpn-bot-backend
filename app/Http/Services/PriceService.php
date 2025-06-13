<?php

namespace App\Http\Services;

use App\Http\DTOs\PaginationDTO;

class PriceService
{
    /**
     * @OA\Get(
     *     path="/api/v1/prices",
     *     tags={"Prices"},
     *     summary="Список стоимости по регионам",
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
     *         name="region_id",
     *         in="query",
     *         description="ID региона",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *                     @OA\Property(property="period_id", type="integer", example=1),
     *                     @OA\Property(property="period_name", type="string", example="Месяц"),
     *                     @OA\Property(property="price", type="integer", example="100"),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function listPrices(PaginationDTO $pagination, int $regionId)
    {
        //
    }
}
