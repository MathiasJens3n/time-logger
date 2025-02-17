<?php

namespace Interfaces;
interface INetworkRepository
{
    public function getNetwork(string $ip): array;
    public function saveNetwork(string $ip, string $ssid, string $password): bool;
}