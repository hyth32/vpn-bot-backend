<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Integrations\YooKassaService;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;

class KeyService
{
    public function __construct(
        private readonly YooKassaService $yooKassaService,
        private readonly WireGuardService $wireGuardService,
    ) {}

    public function listKeys(array $pagination)
    {
        //
    }

    public function getKey(int $keyId)
    {
        //
    }

    public function buyKey(KeyOrderDTO $dto)
    {
        $user = User::where('telegram_id', $dto->getUserId())->firstOrFail();
        $amount = Price::getAmount($dto->getRegionId(), $dto->getPeriodId());

        $config = $this->wireGuardService->createPeer($user, $amount);

        $user->keys()->create([
            'region_id' => $dto->getRegionId(),
            'period_id' => $dto->getPeriodId(),
            'expiration_date' => $config['ExpiresAt'],
            'key' => $config['Identifier'],
        ]);

        $parsedConfig = $this->parseConfig($config);
        return $this->encryptHybrid($parsedConfig);
    }

    public function encryptHybrid(string $config)
    {
        $aesKey = random_bytes(32);
        $iv = random_bytes(16);

        $encryptedData = openssl_encrypt($config, 'AES-256-CBC', $aesKey, OPENSSL_RAW_DATA, $iv);

        $publicKey = openssl_pkey_get_public(Storage::get('client_public.pem'));

        $encryptedKey = null;
        if (!openssl_public_encrypt($aesKey, $encryptedKey, $publicKey)) {
            throw new \Exception('Не удалось зашифровать AES-ключ');
        }

        return [
            'key' => base64_encode($encryptedKey),
            'data' => base64_encode($encryptedData),
            'iv' => base64_encode($iv),
        ];
    }

    public function parseConfig(Response $config)
    {
        $resultConfig = collect();
        $resultConfig->push($this->getConfigInterface($config));
        $resultConfig->push($this->getConfigPeer($config));

        return implode("\n", $resultConfig->toArray());
    }

    public function getConfigInterface($config)
    {
        $interface = collect();
        $interface->push('[Interface]');
        $interface->push('Private key = ' . $config['PrivateKey']);
        $interface->push('Address = ' . implode(',', $config['Addresses']));
        $interface->push('DNS = ' . implode(',', $config['Dns']['Value']));
        $interface->push('MTU = ' . $config['Mtu']['Value']);

        return implode("\n", $interface->toArray());
    }

    public function getConfigPeer($config)
    {
        $peer = collect();
        $peer->push('[Peer]');
        $peer->push('PublicKey = ' . $config['PublicKey']);
        $peer->push('Endpoint = ' . $config['Endpoint']['Value']);
        $peer->push('AllowedIPs = ' . implode(',', $config['AllowedIPs']['Value']));
        $peer->push('PresharedKey = ' . $config['PersistentKeepalive']['Value']);
        $peer->push('PersistentKeepalive = ' . $config['PublicKey']);

        return implode("\n", $peer->toArray());
    }
}
