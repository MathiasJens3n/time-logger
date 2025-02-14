<?php
namespace Services;

use Interfaces\IEventRepository;

class EventService {
    private IEventRepository $eventRepo;

    public function __construct(IEventRepository $eventRepo) {
        $this->eventRepo = $eventRepo;
    }

    // Get events for a device by IP
    public function getEvents(string $ip): array {
        if (empty($ip)) {
            throw new \InvalidArgumentException("IP address is required.");
        }

        $events = $this->eventRepo->getEventsByIP($ip);

        if (empty($events)) {
            throw new \RuntimeException("No events found for IP: {$ip}");
        }

        return $events;
    }

    // Create a new event
    public function createEvent(array $data): string {
        // Validate input
        if (empty($data['deviceId']) || empty($data['buttonNumber']) || !isset($data['status'])) {
            throw new \InvalidArgumentException("Missing required fields: deviceId, buttonNumber, or status.");
        }

        // Insert event
        $response = $this->eventRepo->insertEvent(
            (int)$data['deviceId'],
            (int)$data['buttonNumber'],
            (bool)$data['status']
        );

        if ($response !== 'OK') {
            throw new \RuntimeException("Failed to insert event: {$response}");
        }

        return $response;
    }

    // Update an event's status
    public function updateEvent(array $data): string {
        // Validate input
        if (empty($data['eventId']) || empty($data['deviceId']) || !isset($data['status'])) {
            throw new \InvalidArgumentException("Missing required fields: eventId, deviceId, or status.");
        }

        // Update event status
        $response = $this->eventRepo->updateEventStatus(
            (int)$data['eventId'],
            (int)$data['deviceId'],
            (bool)$data['status']
        );

        if ($response !== 'OK') {
            throw new \RuntimeException("Failed to update event: {$response}");
        }

        return $response;
    }
}
