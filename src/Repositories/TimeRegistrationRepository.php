<?php
namespace Repositories;

use Interfaces\ITimeRegistrationRepository;
use PDO;
use PDOException;
use Services\LoggerService;

class TimeRegistrationRepository implements ITimeRegistrationRepository {
    private PDO $db;
    private LoggerService $logger;

    public function __construct(PDO $db, LoggerService $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    // Get time registrations by IP
    public function getTimeRegistrationsByIP(string $ip): array {
        try {
            $this->logger->info("Fetching time registrations for IP: {$ip}");
            $stmt = $this->db->prepare('CALL GetTimeRegistrationByIP(:ip)');
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();

            $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->logger->info("Fetched " . count($registrations) . " time registrations for IP: {$ip}");

            return $registrations ?: [];
        } catch (PDOException $e) {
            $this->logger->error("Failed to get time registrations for IP: {$ip}", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // Insert a new time registration
    public function insertTimeRegistration(int $eventId, int $deviceId, string $startTime, string $endTime, bool $status): bool {
        try {
            $this->logger->info("Inserting time registration for Event ID: {$eventId}, Device ID: {$deviceId}");

            $stmt = $this->db->prepare('CALL InsertTimeRegistration(:eventId, :deviceId, :startTime, :endTime, :status)');
            $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
            $stmt->bindParam(':deviceId', $deviceId, PDO::PARAM_INT);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
            $success = $stmt->execute();

            $this->logger->info("Time registration insert " . ($success ? "successful" : "failed"));
            return $success;
        } catch (PDOException $e) {
            $this->logger->error("Failed to insert time registration", ['error' => $e->getMessage()]);
            return false;
        }
    }

    // Update an existing time registration
    public function updateTimeRegistration(int $eventId, int $deviceId, string $startTime, string $endTime, bool $status): bool {
        try {
            $this->logger->info("Updating time registration for Event ID: {$eventId}, Device ID: {$deviceId}");

            $stmt = $this->db->prepare('CALL UpdateTimeRegistration(:eventId, :deviceId, :startTime, :endTime, :status)');
            $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
            $stmt->bindParam(':deviceId', $deviceId, PDO::PARAM_INT);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
            $success = $stmt->execute();

            $this->logger->info("Time registration update " . ($success ? "successful" : "failed"));
            return $success;
        } catch (PDOException $e) {
            $this->logger->error("Failed to update time registration", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
