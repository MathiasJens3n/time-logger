<?php
namespace Controllers;

use Services\EventService;

class EventController extends BaseController {
    private EventService $eventService;

    public function __construct(EventService $eventService) {
        $this->eventService = $eventService;
    }

    // Handle GET /event
    public function getEvents(): void {
        $ip = $_GET['ip'] ?? $_SERVER['REMOTE_ADDR'];

        try {
            $events = $this->eventService->getEvents($ip);
            $this->sendJsonResponse($events);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    // Handle POST /event
    public function createEvent(): void {
        $data = $this->getRequestBody();

        try {
            $response = $this->eventService->createEvent($data);
            $this->sendJsonResponse(['response' => $response], 201);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    // Handle PUT /event
    public function updateEvent(): void {
        $data = $this->getRequestBody();

        try {
            $response = $this->eventService->updateEvent($data);
            $this->sendJsonResponse(['response' => $response], 200);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
