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

    const STATUS_SUCCESS = 200;
    const STATUS_DELETED = 204;

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

    public function createConfig(string $configName, string $expirationDate): array
    {
        $baseConfig = $this->getBaseConfig();
        $rawConfig = $this->formatter->prepareConfig($baseConfig, $configName, $expirationDate);

        $syncConfigUrl = "$this->baseUrl/peer/new";

        $responseConfig = Http::withBasicAuth($this->username, $this->password)
            ->withBody(json_encode($rawConfig))
            ->post($syncConfigUrl);

        $decodedConfig = $responseConfig->json();
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $decodedConfig;
    }

    public function findConfig(string $configId): array
    {
        $configId = rawurlencode($configId);
        $getPeerUrl = "$this->baseUrl/peer/by-id/$configId";
        $responseConfig = Http::withBasicAuth($this->username, $this->password)->get($getPeerUrl);

        $decodedConfig = $responseConfig->json();
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $responseConfig->json();
    }

    public function getPeerMetrics(string $configId)
    {
        $configId = rawurlencode($configId);
        $getPeerMetricsUrl = "$this->baseUrl/metrics/by-peer/$configId";
        $response = Http::withBasicAuth($this->username, $this->password)->get($getPeerMetricsUrl);

        $decodedResponse = $response->json();
        if (isset($decodedResponse['Code']) && isset($decodedResponse['Message'])) {
            throw new ErrorException($decodedResponse['Message'], $decodedResponse['Code']);
        }

        return $response->json();
    }

    public function renewConfig(string $configId, int $monthsToAdd)
    {
        $peerConfig = $this->findConfig($configId);
        $updatedConfig = $this->formatter->updateConfigExpiration($peerConfig, $monthsToAdd);
        
        $updateConfigUrl = "$this->baseUrl/peer/by-id/$configId";
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withBody(json_encode($updatedConfig))
            ->put($updateConfigUrl);

        $decodedConfig = $response->json();
        if (isset($decodedConfig['Code']) && isset($decodedConfig['Message'])) {
            throw new ErrorException($decodedConfig['Message'], $decodedConfig['Code']);
        }

        return $response->status() == self::STATUS_SUCCESS;
    }

    public function deleteConfig(string $configId)
    {
        $configId = rawurlencode($configId);
        $deletePeerUrl = "$this->baseUrl/peer/by-id/$configId";
        $response = Http::withBasicAuth($this->username, $this->password)->delete($deletePeerUrl);
        return $response->status() == self::STATUS_DELETED;
    }
}
