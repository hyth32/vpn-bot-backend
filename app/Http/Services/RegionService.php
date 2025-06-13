<?php

namespace App\Http\Services;

use App\Http\DTOs\PaginationDTO;

class RegionService
{
    /**
     * @OA\Get(
     *     path="/api/v1/regions",
     *     tags={"Regions"},
     *     summary="Список поддерживаемых регионов",
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
     *                     @OA\Property(property="name", type="string", example="US East")
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function listRegions(PaginationDTO $pagination)
    {
        //
    }
}
