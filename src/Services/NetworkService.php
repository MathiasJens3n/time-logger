<?php

namespace Services;

use Interfaces\INetworkRepository;
class NetworkService {
    private INetworkRepository $networkRepo;

    public function __construct(INetworkRepository $networkRepo) {
        $this->networkRepo = $networkRepo;
    }

    public function getNetworkInfo(string $ip): array {
        return $this->networkRepo->getNetwork($ip);
    }

    public function createNetwork(string $ip, string $ssid, string $password, string $timestamp): bool {
        return $this->networkRepo->saveNetwork($ip, $ssid, $password, $timestamp);
    }
}
