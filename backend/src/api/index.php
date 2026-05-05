<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . ($_ENV['ALLOWED_ORIGINS'] ?? 'http://localhost:8000'));
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load environment variables
require_once __DIR__ . '/../../config/env.php';

// Autoloader
require_once __DIR__ . '/../../config/Autoloader.php';

use Utils\Response;

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('/api', '', $path);

    // Route requests
    if (strpos($path, '/auth') === 0) {
        require_once __DIR__ . '/auth.php';
    } elseif (strpos($path, '/transactions') === 0) {
        require_once __DIR__ . '/transactions.php';
    } elseif (strpos($path, '/categories') === 0) {
        require_once __DIR__ . '/categories.php';
    } elseif (strpos($path, '/budgets') === 0) {
        require_once __DIR__ . '/budgets.php';
    } elseif (strpos($path, '/goals') === 0) {
        require_once __DIR__ . '/goals.php';
    } elseif (strpos($path, '/dashboard') === 0) {
        require_once __DIR__ . '/dashboard.php';
    } else {
        Response::error('Endpoint not found', 404);
    }
} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), 500);
}
