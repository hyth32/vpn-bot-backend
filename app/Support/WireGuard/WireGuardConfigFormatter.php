<?php

namespace App\Support\WireGuard;

use Carbon\Carbon;

class WireGuardConfigFormatter
{
    public function prepareConfig(array $baseConfig, string $configName, string $expirationDate): array
    {
        $config = $baseConfig;
        $config['DisplayName'] = $configName;
        $config['Filename'] = $configName;
        $config['ExpiresAt'] = $expirationDate;

        return $config;
    }

    public function updateConfigExpiration(array $config, string $expirationDate): array
    {
        $config['ExpiresAt'] = $expirationDate;
        return $config;
    }
}
