<?php /** @noinspection ALL */

namespace Controllers;

use Services\NetworkService;

class NetworkController extends BaseController {
    private NetworkService $networkService;

    public function __construct(NetworkService $networkService) {
        $this->networkService = $networkService;
    }

    public function getNetwork(): void {
        $ip = $_GET['ip'] ?? $_SERVER['REMOTE_ADDR'];
        $network = $this->networkService->getNetwork($ip);

        if ($network) {
            $this->sendJsonResponse($network);
        } else {
            $this->sendJsonResponse(['Network not found'], 404);
        }
    }

    public function createNetwork(): void {
        $data = $this->getRequestBody();

        if (!isset($data['ssid'], $data['password'])) {
            $this->sendJsonResponse(['Bad Request'], 400);
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        $success = $this->networkService->createNetwork($ip, $data['ssid'], $data['password']);

        if ($success) {
            $this->sendJsonResponse(['Network saved'], 201);
        } else {
            $this->sendJsonResponse(['Failed to save network'], 400);
        }
    }
}
