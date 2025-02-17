<?php
namespace Controllers;

use Services\DeviceService;

class DeviceController extends BaseController {
    private DeviceService $deviceService;

    public function __construct(DeviceService $deviceService) {
        $this->deviceService = $deviceService;
    }

    public function registerDevice(): void {
        $data = $this->getRequestBody();

        $ip = $_SERVER['REMOTE_ADDR'];
        $name = $data['name'] ?? null;

        if (!$name) {
            $this->sendJsonResponse(['error' => 'Device name is required'], 400);
        }

        try {
            $deviceId = $this->deviceService->registerDevice($name, $ip);
            $this->sendJsonResponse(['deviceId' => $deviceId], 201);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
