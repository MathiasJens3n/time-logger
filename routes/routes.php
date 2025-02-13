<?php
use Config\Database;
use Routes\RequestRouter;
use Controllers\NetworkController;
use Services\NetworkService;
use Repositories\NetworkRepository;
require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database()->connect();
$requestRouter = new RequestRouter();

// Initialize services and controllers
$networkRepo = new NetworkRepository($db);
$networkService = new NetworkService($networkRepo);
$networkController = new NetworkController($networkService);

// Register routes
$requestRouter->addRoute('GET', '/network', [$networkController, 'getNetwork']);
$requestRouter->addRoute('POST', '/network', [$networkController, 'createNetwork']);

// Handle the incoming request
$requestRouter->handleRequest();
