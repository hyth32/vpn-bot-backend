<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $amounts = [
            1 => [1 => 0], // periodId => keys + price // free
            2 => [1 => 120, 2 => 180, 3 => 270, 4 => 360, 5 => 450], // monthly
            3 => [1 => 320, 2 => 480, 3 => 730, 4 => 970, 5 => 1220], // quarterly
            4 => [1 => 610, 2 => 920, 3 => 1380, 4 => 1840, 5 => 3460], // semmianual
            5 => [1 => 1160, 2 => 1730, 3 => 2600, 4 => 3460, 5 => 4320], // yearly
        ];

        foreach ($amounts as $periodId => $keysData) {
            foreach ($keysData as $keyCount => $amount) {
                Price::updateOrCreate(
                    [
                        'region_id' => 1,
                        'period_id' => $periodId,
                        'key_count' => $keyCount,
                    ],
                    [
                        'amount' => $amount,
                    ]
                );
            }
        }
    }
}
