<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periods = [
            ['name' => 'Monthly', 'value' => 31],
            ['name' => 'Quarterly', 'value' => 93],
            ['name' => 'Semiannual', 'value' => 182],
            ['name' => 'Yearly', 'value' => 365],
        ];

        foreach ($periods as $period) {
            Period::updateOrCreate(
                ['name' => $period['name']],
                ['value' => $period['value']]
            );
        }
    }
}
