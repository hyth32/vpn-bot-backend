<?php

namespace App\Support\WireGuard;

use Carbon\Carbon;

class WireGuardConfigFormatter
{
    public function prepareConfig(array $baseConfig, string $configName, int $expirationDays): array
    {
        $config = $baseConfig;
        $config['DisplayName'] = $configName;
        $config['Filename'] = $configName;
        $config['ExpiresAt'] = now()->addDays($expirationDays)->toDateString();

        return $config;
    }

    public function updateConfigExpiration(array $config, int $expirationDays): array
    {
        $config['ExpiresAt'] = Carbon::parse($config['ExpiresAt'])->addDays($expirationDays);
        return $config;
    }
}
