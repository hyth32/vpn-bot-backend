<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $fillable = [
        'region_id',
        'period_id',
        'amount',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public static function getAmount(int $regionId, int $periodId, ?int $quantity = 1): ?float
    {
        return Price::query()
            ->where('region_id', $regionId)
            ->where('period_id', $periodId)
            ->where('key_count', $quantity)
            ->value('amount');
    }
}
