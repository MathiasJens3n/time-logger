<?php
namespace Repositories;

use Interfaces\IEventRepository;
use PDO;
use PDOException;
use Services\LoggerService;

class EventRepository implements IEventRepository {
    private PDO $db;
    private LoggerService $logger;

    public function __construct(PDO $db, LoggerService $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    // Get events by IP
    public function getEventsByIP(string $ip): array {
        try {
            $this->logger->info("Fetching events for IP: {$ip}");

            $stmt = $this->db->prepare('CALL GetEventDetailsByIP(:ip)');
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();

            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->logger->info("Fetched " . count($events) . " events for IP: {$ip}");

            return $events ?: [];
        } catch (PDOException $e) {
            $this->logger->error("Failed to get events for IP: {$ip}", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // Insert a new event
    public function insertEvent(int $deviceId, int $buttonNumber, bool $status): string {
        try {
            $this->logger->info("Inserting event for Device ID: {$deviceId}");

            $stmt = $this->db->prepare('CALL InsertEvent(:deviceId, :buttonNumber, :status, @response)');
            $stmt->bindParam(':deviceId', $deviceId, PDO::PARAM_INT);
            $stmt->bindParam(':buttonNumber', $buttonNumber, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
            $stmt->execute();

            $response = $this->db->query('SELECT @response')->fetchColumn();
            $this->logger->info("InsertEvent response: {$response}");

            return $response;
        } catch (PDOException $e) {
            $this->logger->error("Failed to insert event", ['error' => $e->getMessage()]);
            return 'Bad Request: Insert failed';
        }
    }

    // Update event status
    public function updateEventStatus(int $eventId, int $deviceId, bool $status): string {
        try {
            $this->logger->info("Updating event ID: {$eventId} for Device ID: {$deviceId}");

            $stmt = $this->db->prepare('CALL UpdateEventStatus(:eventId, :deviceId, :status, @result)');
            $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
            $stmt->bindParam(':deviceId', $deviceId, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
            $stmt->execute();

            $result = $this->db->query('SELECT @result')->fetchColumn();
            $this->logger->info("UpdateEventStatus response: {$result}");

            return $result;
        } catch (PDOException $e) {
            $this->logger->error("Failed to update event status", ['error' => $e->getMessage()]);
            return 'Bad Request: Update failed';
        }
    }
}
