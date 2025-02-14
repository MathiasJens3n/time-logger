<?php
namespace Services;

use Interfaces\INetworkRepository;

class NetworkService {
    private INetworkRepository $networkRepo;
    private LoggerService $logger;

    public function __construct(INetworkRepository $networkRepo, LoggerService $logger) {
        $this->networkRepo = $networkRepo;
        $this->logger = $logger;
    }

    public function getNetwork(string $ip): array {
        $this->logger->info("Fetching network info for IP: {$ip}");

        // Fetch the latest network information
        $network = $this->networkRepo->getNetwork($ip);

        // Check if network data exists
        if (empty($network)) {
            $this->logger->warning("No network info found for IP: {$ip}");
            return [];
        }

        // Validate SSID and Password
        if (empty($network['SSID']) || empty($network['Password'])) {
            $this->logger->warning("Network info retrieved but missing SSID or Password for IP: {$ip}", $network);
            return [];
        }

        $this->logger->info("Network info successfully retrieved for IP: {$ip}", $network);
        return $network;
    }



    public function createNetwork(string $ip, string $ssid, string $password): bool {
        $this->logger->info("Attempting to create network for IP: {$ip}", [
            'SSID' => $ssid,
        ]);

        $success = $this->networkRepo->saveNetwork($ip, $ssid, $password);

        if ($success) {
            $this->logger->info("Network created successfully for IP: {$ip}");
        } else {
            $this->logger->error("Failed to create network for IP: {$ip}");
        }

        return $success;
    }
}
