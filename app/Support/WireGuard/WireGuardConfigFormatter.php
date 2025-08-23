<?php

namespace App\Support\WireGuard;

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
}
