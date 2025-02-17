<?php

namespace Services;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerService {
    private Logger $logger;

    public function __construct(string $channel = 'app') {
        // Initialize logger with a channel name
        $this->logger = new Logger($channel);

        // Add a stream handler for logs/log.log
        $logFile = __DIR__ . '/../../logs/system.log';
        $streamHandler = new StreamHandler($logFile, Level::Debug);
        $this->logger->pushHandler($streamHandler);
    }

    public function debug(string $message, array $context = []): void {
        $this->logger->debug($message, $context);
    }

    public function info(string $message, array $context = []): void {
        $this->logger->info($message, $context);
    }

    public function warning(string $message, array $context = []): void {
        $this->logger->warning($message, $context);
    }

    public function error(string $message, array $context = []): void {
        $this->logger->error($message, $context);
    }

    public function critical(string $message, array $context = []): void {
        $this->logger->critical($message, $context);
    }
}