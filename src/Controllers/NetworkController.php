<?php

namespace Controllers;

use Services\NetworkService;

class NetworkController extends BaseController {
    private NetworkService $networkService;

    public function __construct(NetworkService $networkService) {
        $this->networkService = $networkService;
    }

    /**
     * Handle GET /network
     * Retrieves network information for a device based on its IP.
     */
    public function getNetwork(): void {
        $ip = $_GET['ip'] ?? $_SERVER['REMOTE_ADDR'];

        try {
            $network = $this->networkService->getNetwork($ip);
            $this->sendJsonResponse($network);
        } catch (\InvalidArgumentException $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Handle POST /network
     * Creates a new network entry.
     */
    public function createNetwork(): void {
        $data = $this->getRequestBody();

        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->networkService->createNetwork($ip, $data['ssid'] ?? '', $data['password'] ?? '');
            $this->sendJsonResponse(['message' => 'Network saved'], 201);
        } catch (\InvalidArgumentException $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            $this->sendJsonResponse(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            $this->sendJsonResponse(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
