<?php
namespace Interfaces;

interface IEventRepository {
    public function getEventsByIP(string $ip): array;
    public function insertEvent(int $deviceId, int $buttonNumber, bool $status): string;
    public function updateEventStatus(int $eventId, int $deviceId, bool $status): string;
}
