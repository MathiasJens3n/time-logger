<?php

namespace Services;

use DTO\NetworkDTO;
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
     * @return NetworkDTO A DTO containing SSID and Password.
     * @throws InvalidArgumentException If the IP address is empty.
     * @throws RuntimeException If no network info is found.
     */
    public function getNetwork(string $ip): NetworkDTO {
        if (empty($ip)) {
            throw new InvalidArgumentException("IP address is required.");
        }

        // Get the DTO directly from the repository
        $networkDTO = $this->networkRepo->getNetwork($ip);

        if (!$networkDTO) {
            throw new RuntimeException("No network info found for IP: {$ip}");
        }

        return $networkDTO;
    }

    /**
     * Create a new network entry.
     *
     * @param string $ip The IP address of the device.
     * @param string $ssid The SSID of the network.
     * @param string $password The password of the network.
     * @return bool True if the network was created successfully.
     * @throws InvalidArgumentException If any required parameter is empty.
     * @throws RuntimeException If network creation fails.
     */
    public function createNetwork(string $ip, string $ssid, string $password): bool {
        if (empty($ssid) || empty($password)) {
            throw new InvalidArgumentException("SSID, and Password are required fields.");
        }

        // Save directly through repository
        $success = $this->networkRepo->saveNetwork($ip, $ssid, $password);

        if (!$success) {
            throw new RuntimeException("Failed to create network for IP: {$ip}");
        }

        return true;
    }
}
