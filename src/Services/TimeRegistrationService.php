<?php
namespace Services;

use Interfaces\ITimeRegistrationRepository;

class TimeRegistrationService {
    private ITimeRegistrationRepository $timeRepo;

    public function __construct(ITimeRegistrationRepository $timeRepo) {
        $this->timeRepo = $timeRepo;
    }

    // Get time registrations by IP
    public function getTimeRegistrations(string $ip): array {
        if (empty($ip)) {
            throw new \InvalidArgumentException("IP address is required.");
        }

        $registrations = $this->timeRepo->getTimeRegistrationsByIP($ip);

        if (empty($registrations)) {
            throw new \RuntimeException("No time registrations found for IP: {$ip}");
        }

        return $registrations;
    }

    // Create a new time registration
    public function createTimeRegistration(array $data): bool {
        if (!isset($data['eventId'], $data['deviceId'], $data['startTime'], $data['endTime'], $data['status'])) {
            throw new \InvalidArgumentException("Missing required fields.");
        }

        return $this->timeRepo->insertTimeRegistration(
            $data['eventId'],
            $data['deviceId'],
            $data['startTime'],
            $data['endTime'],
            $data['status']
        );
    }

    // Update an existing time registration
    public function updateTimeRegistration(array $data): bool {
        if (!isset($data['eventId'], $data['deviceId'], $data['startTime'], $data['endTime'], $data['status'])) {
            throw new \InvalidArgumentException("Missing required fields.");
        }

        return $this->timeRepo->updateTimeRegistration(
            $data['eventId'],
            $data['deviceId'],
            $data['startTime'],
            $data['endTime'],
            $data['status']
        );
    }
}
