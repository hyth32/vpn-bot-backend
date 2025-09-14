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
            2 => [1 => 120, 2 => 160, 3 => 220, 4 => 310, 5 => 390], // monthly
            3 => [1 => 320, 2 => 440, 3 => 590, 4 => 840, 5 => 1040], // quarterly
            4 => [1 => 610, 2 => 820, 3 => 1120, 4 => 1580, 5 => 1990], // semmianual
            5 => [1 => 1160, 2 => 1540, 3 => 2120, 4 => 2980, 5 => 3820], // yearly
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
