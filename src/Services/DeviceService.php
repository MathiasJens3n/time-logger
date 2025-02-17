<?php
namespace Services;

use Interfaces\IDeviceRepository;

class DeviceService {
    private IDeviceRepository $deviceRepo;
    private LoggerService $logger;

    public function __construct(IDeviceRepository $deviceRepo, LoggerService $logger) {
        $this->deviceRepo = $deviceRepo;
        $this->logger = $logger;
    }

    public function registerDevice(string $name, string $ip): int {
        $this->logger->info("Registering device with name: {$name} and IP: {$ip}");

        if (empty($name) || empty($ip)) {
            $this->logger->warning("Failed to register device: Missing name or IP");
            throw new \InvalidArgumentException("Name and IP are required");
        }

        $deviceId = $this->deviceRepo->addDevice($name, $ip);

        if ($deviceId > 0) {
            $this->logger->info("Device registered successfully with ID: {$deviceId}");
            return $deviceId;
        }

        $this->logger->error("Failed to register device with name: {$name}");
        throw new \RuntimeException("Failed to register device");
    }
}
