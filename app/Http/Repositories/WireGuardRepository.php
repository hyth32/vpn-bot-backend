<?php

namespace App\Http\Repositories;

use ErrorException;
use Illuminate\Support\Facades\Http;

class WireGuardRepository
{
    private string $username;
    private string $password;
    private string $baseUrl;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->username = config('wireguard.username');
        $this->password = config('wireguard.password');
        $this->baseUrl = config('wireguard.url');
    }

    public function getBaseConfig()
    {
        $prepareConfigUrl = "$this->baseUrl/peer/prepare/wg0";
        $response = Http::withBasicAuth($this->username, $this->password)
            ->get($prepareConfigUrl);
        
        return $response;
    }

    public function createConfig(string $configName, int $expirationDays)
    {
        $rawConfig = $this->prepareConfig($configName, $expirationDays);

        return $this->syncConfig($rawConfig);
    }

    public function findConfig(string $configId)
    {
        $configId = rawurlencode($configId);
        $getPeerUrl = "$this->baseUrl/peer/by-id/$configId";
        $responseConfig = Http::withBasicAuth($this->username, $this->password)
            ->get($getPeerUrl);

        $decodedConfig = json_decode($responseConfig, true);
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $responseConfig;
    }

    private function prepareConfig(string $configName, int $expirationDays)
    {
        $config = json_decode($this->getBaseConfig(), true);
        $config['DisplayName'] = $configName;
        $config['Filename'] = $configName;
        $config['ExpiresAt'] = now()->addDays($expirationDays)->toDateString();

        return json_encode($config);
    }

    public function syncConfig($config)
    {
        $syncConfigUrl = "$this->baseUrl/peer/new";

        $responseConfig = Http::withBasicAuth($this->username, $this->password)
            ->withBody($config)
            ->post($syncConfigUrl);

        $decodedConfig = json_decode($responseConfig, true);
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $responseConfig;
    }
}
