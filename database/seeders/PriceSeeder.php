<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $periods = Period::query();

        Price::updateOrCreate(
            ['region_id' => 1, 'period_id' => 1, 'key_count' => 1],
            ['amount' => 0],
        );

        $periods->cursor()->each(function (Period $period) {
            for ($i = 1; $i < 6; $i++) {
                if ($period->id == 1) {
                    continue;
                }

                Price::updateOrCreate(
                    ['region_id' => 1, 'period_id' => $period->id, 'key_count' => $i],
                    ['amount' => $this->calculatePrice($i, $period->value)],
                );
            }
        });
    }

    private function calculatePrice(int $deviceCount, int $monthCount)
    {
        $basePrice = 100;

        $deviceDiscount = [
            1 => 0,
            2 => 70,
            3 => 110,
            4 => 120,
            5 => 140,
        ];

        $durationDiscount = [
            1 => 0,
            3 => 0.05,
            6 => 0.10,
            12 => 0.20,
        ];

        $price = ($deviceCount * $basePrice - ($deviceDiscount[$deviceCount] ?? 0))
            * $monthCount * (1 - ($durationDiscount[$monthCount] ?? 0));

        return $this->roundToNice($price);
    }

    private function roundToNice(float $number)
    {
        if ($number < 1000) {
            return round($number / 10) * 10;
        }
        return round($number / 50) * 50;
    }
}
