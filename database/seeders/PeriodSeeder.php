<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periods = [
            ['name' => 'Free', 'value' => 0, 'discount' => null],
            ['name' => 'Monthly', 'value' => 1, 'discount' => null],
            ['name' => 'Quarterly', 'value' => 3, 'discount' => 10],
            ['name' => 'Semiannual', 'value' => 6, 'discount' => 15],
            ['name' => 'Yearly', 'value' => 12, 'discount' => 20],
        ];

        foreach ($periods as $period) {
            Period::updateOrCreate(
                ['name' => $period['name']],
                [
                    'value' => $period['value'],
                    'discount' => $period['discount'],
                ],
            );
        }
    }
}
