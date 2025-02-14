<?php
namespace Controllers;

use Services\TimeRegistrationService;

class TimeRegistrationController extends BaseController {
    private TimeRegistrationService $timeService;

    public function __construct(TimeRegistrationService $timeService) {
        $this->timeService = $timeService;
    }

    // Handle GET /time-registration
    public function getTimeRegistrations(): void {
        $ip = $_GET['ip'] ?? $_SERVER['REMOTE_ADDR'];

        try {
            $registrations = $this->timeService->getTimeRegistrations($ip);
            $this->sendJsonResponse($registrations);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    // Handle POST /time-registration
    public function createTimeRegistration(): void {
        $data = $this->getRequestBody();

        try {
            $success = $this->timeService->createTimeRegistration($data);
            $this->sendJsonResponse(['message' => $success ? 'Time registration created' : 'Failed to create time registration'], $success ? 201 : 400);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    // Handle PUT /time-registration
    public function updateTimeRegistration(): void {
        $data = $this->getRequestBody();

        try {
            $success = $this->timeService->updateTimeRegistration($data);
            $this->sendJsonResponse(['message' => $success ? 'Time registration updated' : 'Failed to update time registration'], $success ? 200 : 400);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
