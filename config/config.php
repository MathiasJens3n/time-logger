<?php


require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$config = [
    'db_host' => $_ENV['DB_HOST'],
    'db_name' => $_ENV['DB_DATABASE'],
    'db_user' => $_ENV['DB_USERNAME'],
    'db_pass' => $_ENV['DB_PASSWORD'],
    'api_secret_key' => $_ENV['API_SECRET_KEY']
];

