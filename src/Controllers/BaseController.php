<?php

namespace Controllers;
use JetBrains\PhpStorm\NoReturn;

class BaseController {
    #[NoReturn] protected function sendJsonResponse(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function getRequestBody(): array {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
