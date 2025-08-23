<?php

namespace App\Http\Repositories;

use App\Support\WireGuard\WireGuardConfigFormatter;
use ErrorException;
use Illuminate\Support\Facades\Http;

class WireGuardRepository
{
    private string $username;
    private string $password;
    private string $baseUrl;

    public function __construct(
        private WireGuardConfigFormatter $formatter,
    ) {
        $this->init();
    }

    public function init()
    {
        $this->username = config('wireguard.username');
        $this->password = config('wireguard.password');
        $this->baseUrl = config('wireguard.url');
    }

    public function getBaseConfig(): array
    {
        $prepareConfigUrl = "$this->baseUrl/peer/prepare/wg0";
        $baseConfigResponse = Http::withBasicAuth($this->username, $this->password)->get($prepareConfigUrl);
        return $baseConfigResponse->json();
    }

    public function createConfig(string $configName, int $expirationDays): array
    {
        $baseConfig = $this->getBaseConfig();
        $rawConfig = $this->formatter->prepareConfig($baseConfig, $configName, $expirationDays);

        return $this->syncConfig($rawConfig);
    }

    public function findConfig(string $configId): array
    {
        $configId = rawurlencode($configId);
        $getPeerUrl = "$this->baseUrl/peer/by-id/$configId";
        $responseConfig = Http::withBasicAuth($this->username, $this->password)->get($getPeerUrl);

        $decodedConfig = json_decode($responseConfig, true);
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $responseConfig->json();
    }

    public function deleteConfig(string $configId)
    {
        $configId = rawurlencode($configId);
        $deletePeerUrl = "$this->baseUrl/peer/by-id/$configId";
        $response = Http::withBasicAuth($this->username, $this->password)->delete($deletePeerUrl);
        return $response->status() == 204;
    }

    public function syncConfig(array $config): array
    {
        $syncConfigUrl = "$this->baseUrl/peer/new";

        $responseConfig = Http::withBasicAuth($this->username, $this->password)
            ->withBody(json_encode($config))
            ->post($syncConfigUrl);

        $decodedConfig = json_decode($responseConfig, true);
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $responseConfig->json();
    }
}
