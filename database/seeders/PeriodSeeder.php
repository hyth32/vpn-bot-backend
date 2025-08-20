<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periods = [
            ['name' => 'Free', 'value' => 0],
            ['name' => 'Monthly', 'value' => 1],
            ['name' => 'Quarterly', 'value' => 3],
            ['name' => 'Semiannual', 'value' => 6],
            ['name' => 'Yearly', 'value' => 12],
        ];

        foreach ($periods as $period) {
            Period::updateOrCreate(
                ['name' => $period['name']],
                ['value' => $period['value']]
            );
        }
    }
}
