<?php
namespace Repositories;

use Interfaces\IDeviceRepository;
use PDO;
use PDOException;
use Services\LoggerService;

class DeviceRepository implements IDeviceRepository {
    private PDO $db;
    private LoggerService $logger;

    public function __construct(PDO $db, LoggerService $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function addDevice(string $name, string $ip): int {
        try {
            $stmt = $this->db->prepare('CALL AddDevice(:name, :ip, @deviceId)');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();

            $result = $this->db->query('SELECT @deviceId AS deviceId')->fetch(PDO::FETCH_ASSOC);
            return $result['deviceId'] ?? 0;
        } catch (PDOException $e) {
            $this->logger->error('Database error during addDevice', [
                'message' => $e->getMessage(),
                'ip' => $ip
            ]);
            return 0;
        }
    }
}
