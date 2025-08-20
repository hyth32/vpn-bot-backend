<?php

namespace App\Http\Services;

use App\Http\DTOs\KeyOrderDTO;
use App\Http\Integrations\YooKassaService;
use App\Http\Repositories\KeyRepository;
use App\Http\Repositories\UserRepository;
use App\Models\Key;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;

class KeyService
{
    public function __construct(
        private YooKassaService $yooKassaService,
        private WireGuardService $wireGuardService,
        private KeyRepository $repository,
        private UserRepository $userRepository,
    ) {}

    public function listKeys(int $userId, int $offset, int $limit)
    {
        return $this->repository->index($userId, $offset, $limit);
    }

    public function showKey(int $id)
    {
        return $this->repository->findOne($id);
    }

    public function getConfig(int $keyId)
    {
        $key = $this->repository->findOne($keyId);
        $config = $this->wireGuardService->getPeer($key);
        $parsedConfig = $this->parseConfig($config);
        // return $this->encryptHybrid($parsedConfig);
        return $parsedConfig;
    }

    public function buyKey(KeyOrderDTO $dto)
    {
        $user = $this->userRepository->findByTelegramId($dto->getTelegramId());
        $amount = Price::getAmount($dto->getRegionId(), $dto->getPeriodId());

        $config = $this->wireGuardService->createPeer($user, $amount);

        $user->keys()->create([
            'region_id' => $dto->getRegionId(),
            'period_id' => $dto->getPeriodId(),
            'expiration_date' => $config['ExpiresAt'],
            'key' => $config['Identifier'],
        ]);

        $parsedConfig = $this->parseConfig($config);
        // return $this->encryptHybrid($parsedConfig);
        return $parsedConfig;
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
