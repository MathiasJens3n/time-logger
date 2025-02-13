<?php

namespace Controllers;

use Services\NetworkService;
class NetworkController extends BaseController {
    private NetworkService $networkService;

    public function __construct(NetworkService $networkService) {
        $this->networkService = $networkService;
    }

    // Handle GET /network
    public function getNetwork(): void {
        $ip = $_GET['ip'] ?? $_SERVER['REMOTE_ADDR'];
        $network = $this->networkService->getNetworkInfo($ip);
        $network
            ? $this->sendJsonResponse($network)
            : $this->sendJsonResponse(['error' => 'Network not found'], 404);
    }

    // Handle POST /network
    public function createNetwork(): void {
        $data = $this->getRequestBody();

        if (!isset($data['ssid'], $data['password'], $data['timestamp'])) {
            $this->sendJsonResponse(['error' => 'Bad Request'], 400);
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        $success = $this->networkService->createNetwork($ip, $data['ssid'], $data['password'], $data['timestamp']);

        $success
            ? $this->sendJsonResponse(['message' => 'Network saved'], 201)
            : $this->sendJsonResponse(['error' => 'Failed to save network'], 400);
    }
}
