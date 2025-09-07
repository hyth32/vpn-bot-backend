<?php

namespace App\Support\WireGuard;

class WireGuardConfigParser
{
    public function parse(array $config): string
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
        $interface->push('PrivateKey = ' . $config['PrivateKey']);
        $interface->push('Address = ' . implode(',', $config['Addresses']));
        $interface->push('DNS = ' . implode(',', $config['Dns']['Value']));
        $interface->push('MTU = ' . $config['Mtu']['Value']);

        return implode("\n", $interface->toArray()) . "\n";
    }

    public function getConfigPeer($config)
    {
        $peer = collect();
        $peer->push('[Peer]');
        $peer->push('PublicKey = ' . $config['EndpointPublicKey']['Value']);
        $peer->push('Endpoint = ' . $config['Endpoint']['Value']);
        $peer->push('AllowedIPs = ' . implode(',', $config['AllowedIPs']['Value']));
        $peer->push('PresharedKey = ' . $config['PresharedKey']);
        $peer->push('PersistentKeepalive = ' . $config['PersistentKeepalive']['Value']);

        return implode("\n", $peer->toArray());
    }
}
