<?php

namespace Repositories;

use DTO\NetworkDTO;
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
    /**
     * Retrieve network information by IP.
     *
     * @param string $ip The IP address to search.
     * @return NetworkDTO|null A NetworkDTO if found, null otherwise.
     */
    public function getNetwork(string $ip): ?NetworkDTO
    {
        try {
            $stmt = $this->db->prepare('CALL getNetworkCredentials(:ip)');
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() instead of fetchAll()

            if ($result) {
                return new NetworkDTO(
                    ssid: $result['SSID'],
                    password: $result['Password']
                );
            }

        } catch (PDOException $e) {
            $this->logger->error('Database error during GetNetworkCredentials', [
                'message' => $e->getMessage(),
                'ip' => $ip
            ]);
        }
        return null;
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