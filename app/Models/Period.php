<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="Period", description="Период", properties={
 *     @OA\Property(property="id", type="integer", description="ID периода"),
 *     @OA\Property(property="name", type="string", description="ID периода"),
 *     @OA\Property(property="discount", type="integer", description="Скидка в процентах"),
 * })
 */
class Period extends Model
{
    protected $fillable = [
        'name',
        'value',
        'discount',
    ];
}
