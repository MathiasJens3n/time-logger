<?php

namespace Repositories;

use Interfaces\INetworkRepository;
use PDO;

class NetworkRepository implements INetworkRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getNetwork(string $ip): array
    {
        $stmt = $this->db->prepare('SELECT * FROM networks WHERE ip = :ip');
        $stmt->execute(['ip' => $ip]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function saveNetwork(string $ip, string $ssid, string $password, string $timestamp): bool
    {
        $stmt = $this->db->prepare('INSERT INTO networks (ip, ssid, password, timestamp) VALUES (:ip, :ssid, :password, :timestamp)');
        return $stmt->execute(['ip' => $ip, 'ssid' => $ssid, 'password' => $password, 'timestamp' => $timestamp]);
    }
}