<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Netherlands', 'code' => 'NL', 'flag' => '🇳🇱', 'host' => 'nl.example.com', 'port' => 1194],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['code' => $region['code']],
                [
                    'name' => $region['name'],
                    'flag' => $region['flag'],
                    'host' => $region['host'],
                    'port' => $region['port']
                ]
            );
        }
    }
}
