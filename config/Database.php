<?php

namespace Config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database {

    public function connect(): ?PDO {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Get DB credentials from .env
        $host = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }

        return $conn;
    }
}
