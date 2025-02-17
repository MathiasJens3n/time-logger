<?php
namespace Services;

use Interfaces\INetworkRepository;
use InvalidArgumentException;
use RuntimeException;

class NetworkService {
    private INetworkRepository $networkRepo;

    public function __construct(INetworkRepository $networkRepo) {
        $this->networkRepo = $networkRepo;
    }

    /**
     * Retrieve network information for a device based on its IP address.
     *
     * @param string $ip The IP address of the device.
     * @return array Associative array with network info:
     *               - IP (string)
     *               - SSID (string)
     *               - Password (string)
     * @throws InvalidArgumentException If the IP address is empty.
     * @throws RuntimeException If no network info is found.
     */
    public function getNetwork(string $ip): array {
        if (empty($ip)) {
            throw new InvalidArgumentException("IP address is required.");
        }

        // Fetch the latest network information
        $network = $this->networkRepo->getNetwork($ip);

        // Check if network data exists
        if (empty($network)) {
            throw new RuntimeException("No network info found for IP: {$ip}");
        }

        return $network;
    }

    /**
     * Create a new network entry.
     *
     * @param string $ip The IP address of the device.
     * @param string $ssid The SSID of the network.
     * @param string $password The password of the network.
     * @return bool True if the network was created successfully, false otherwise.
     * @throws InvalidArgumentException If any required parameter is empty.
     * @throws RuntimeException If network creation fails.
     */
    public function createNetwork(string $ip, string $ssid, string $password): bool {
        // Validate input
        if (empty($ip) || empty($ssid) || empty($password)) {
            throw new InvalidArgumentException("IP, SSID, and Password are required fields.");
        }

        // Attempt to save the network
        $success = $this->networkRepo->saveNetwork($ip, $ssid, $password);

        if (!$success) {
            throw new RuntimeException("Failed to create network for IP: {$ip}");
        }

        return true;
    }
}
