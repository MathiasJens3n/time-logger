<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Config\Database;
use Routes\RequestRouter;
use Controllers\NetworkController;
use Services\NetworkService;
use Repositories\NetworkRepository;
use Services\LoggerService;

$db = new Database()->connect();
$requestRouter = new RequestRouter();

// Initialize logger
$logger = new LoggerService();

// Initialize services and controllers
$networkRepo = new NetworkRepository($db, $logger);
$networkService = new NetworkService($networkRepo, $logger);
$networkController = new NetworkController($networkService);

// Register routes
$requestRouter->addRoute('GET', '/network', [$networkController, 'getNetwork']);
$requestRouter->addRoute('POST', '/network', [$networkController, 'createNetwork']);

// Handle the incoming request
$requestRouter->handleRequest();
