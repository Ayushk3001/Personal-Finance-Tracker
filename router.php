<?php
// Router script for PHP built-in server
$requested_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// API requests
if (strpos($requested_path, '/api/') === 0) {
    require __DIR__ . '/backend/src/api/index.php';
    return true;
}

// Frontend requests
if (strpos($requested_path, '/frontend/') === 0 || $requested_path === '/') {
    if (file_exists(__DIR__ . $requested_path)) {
        return false; // Serve the file
    }
    // Serve index.html for frontend routes
    require __DIR__ . '/frontend/public/index.html';
    return true;
}

return false; // Let PHP handle it
