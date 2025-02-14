<?php

namespace Repositories;

use Interfaces\INetworkRepository;
use PDO;
use PDOException;
use Services\LoggerService;

class NetworkRepository implements INetworkRepository
{
    private PDO $db;
    private LoggerService $logger;

    public function __construct(PDO $db, LoggerService $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function getNetwork(string $ip): array
    {
        // Call GetNetworkCredentials stored procedure
        try {
            $stmt = $this->db->prepare('CALL GetNetworkCredentials(:ip, @outputSSID, @outputPassword)');
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();

            // Retrieve the output variables
            $result = $this->db->query('SELECT @outputSSID AS SSID, @outputPassword AS Password')->fetch(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (PDOException $e) {
            $this->logger->error('Database error during GetNetworkCredentials', [
                'message' => $e->getMessage(),
                'ip' => $ip
            ]);
            return [];
        }
    }

    public function saveNetwork(string $ip, string $ssid, string $password): bool
    {
        // Call AddToNetwork stored procedure
        try {
            $stmt = $this->db->prepare('CALL AddToNetwork(:ip, :name, :ssid, :password)');
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':ssid', $ssid, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logger->error('Database error during AddToNetwork', [
                'message' => $e->getMessage(),
                'ip' => $ip
            ]);
            return false;
        }
    }
}