<?php

namespace Interfaces;
use DTO\NetworkDTO;

interface INetworkRepository
{
    public function getNetwork(string $ip): ?NetworkDTO;
    public function saveNetwork(string $ip, string $ssid, string $password): bool;
}