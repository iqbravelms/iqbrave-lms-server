<?php
header('Access-Control-Allow-Origin: *'); // Allow any origin; for production, specify your domain
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific methods
header('Access-Control-Allow-Headers: Authorization, Content-Type'); // Allow specific headers

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No content
    exit;
}


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';

// Instantiate the Router
$router = new Router();

// Include the routes (after $router has been created)
require_once __DIR__ . '/../app/Routes/web.php';

// Get the current method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Dispatch the request
$router->dispatch($method, $uri);
