<?php
namespace Interfaces;

interface ITimeRegistrationRepository {
    public function getTimeRegistrationsByIP(string $ip): array;
    public function insertTimeRegistration(int $eventId, int $deviceId, string $startTime, string $endTime, bool $status): bool;
    public function updateTimeRegistration(int $eventId, int $deviceId, string $startTime, string $endTime, bool $status): bool;
}
