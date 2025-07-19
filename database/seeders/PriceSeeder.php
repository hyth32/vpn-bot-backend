<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $regionId = 1;
        $periodIds = Period::query()->pluck('id')->toArray();

        $prices = [
            1 => 100,
            2 => 300,
            3 => 600,
            4 => 1200,
        ];

        foreach ($periodIds as $periodId) {
            Price::updateOrCreate(
                ['region_id' => $regionId, 'period_id' => $periodId],
                ['amount' => $prices[$periodId]],
            );
        }
    }
}
