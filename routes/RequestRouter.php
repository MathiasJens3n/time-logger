<?php

namespace Routes;
class RequestRouter {
    private array $routes = [];

    public function addRoute(string $method, string $path, callable $handler): void {
        $this->routes[$method][$path] = $handler;
    }

    public function handleRequest(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = strtok($_SERVER['REQUEST_URI'], '?');

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler) {
            call_user_func($handler);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }
}
