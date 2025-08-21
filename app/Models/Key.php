<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(schema="Key", description="Ключ", properties={
 *     @OA\Property(property="id", type="integer", description="ID ключа"),
 *     @OA\Property(property="region_name", type="string", description="Название региона"),
 *     @OA\Property(property="region_flag", type="string", description="Флаг региона"),
 *     @OA\Property(property="expiration_date", type="string", format="date-time", description="Дата экспирации"),
 *     @OA\Property(property="amount", type="integer", description="Стоимость продления"),
 * })
 */
class Key extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'region_id',
        'period_id',
        'key',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function getPrice()
    {
        return Price::getAmount($this->region_id, $this->period_id);
    }
}
