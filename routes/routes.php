<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Config\Database;
use Routes\RequestRouter;
use Controllers\NetworkController;
use Services\NetworkService;
use Repositories\NetworkRepository;
use Services\LoggerService;
use Controllers\DeviceController;
use Repositories\DeviceRepository;
use Services\DeviceService;
use Controllers\EventController;
use Repositories\EventRepository;
use Services\EventService;
use Controllers\TimeRegistrationController;
use Repositories\TimeRegistrationRepository;
use Services\TimeRegistrationService;

$db = new Database()->connect();
$requestRouter = new RequestRouter();

// Initialize logger
$logger = new LoggerService();

// Network
$networkRepo = new NetworkRepository($db, $logger);
$networkService = new NetworkService($networkRepo, $logger);
$networkController = new NetworkController($networkService);

$requestRouter->addRoute('GET', '/network', [$networkController, 'getNetwork']);
$requestRouter->addRoute('POST', '/network', [$networkController, 'createNetwork']);

// Device
$deviceRepo = new DeviceRepository($db, $logger);
$deviceService = new DeviceService($deviceRepo, $logger);
$deviceController = new DeviceController($deviceService);

$requestRouter->addRoute('POST', '/device', [$deviceController, 'registerDevice']);

// Event
$eventRepo = new EventRepository($db, $logger);
$eventService = new EventService($eventRepo);
$eventController = new EventController($eventService);

$requestRouter->addRoute('GET', '/event', [$eventController, 'getEvents']);
$requestRouter->addRoute('POST', '/event', [$eventController, 'createEvent']);
$requestRouter->addRoute('PUT', '/event', [$eventController, 'updateEvent']);

// TimeRegistration
$timeRepo = new TimeRegistrationRepository($db, $logger);
$timeService = new TimeRegistrationService($timeRepo);
$timeController = new TimeRegistrationController($timeService);

$requestRouter->addRoute('GET', '/time-registration', [$timeController, 'getTimeRegistrations']);
$requestRouter->addRoute('POST', '/time-registration', [$timeController, 'createTimeRegistration']);
$requestRouter->addRoute('PUT', '/time-registration', [$timeController, 'updateTimeRegistration']);

// Handle the incoming request
$requestRouter->handleRequest();
