<?php

// Load environment variables from .env file
$envFile = __DIR__ . '/../../.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
    }
}

// Set default values if not in .env
if (!isset($_ENV['DB_HOST'])) $_ENV['DB_HOST'] = 'localhost';
if (!isset($_ENV['DB_PORT'])) $_ENV['DB_PORT'] = '3306';
if (!isset($_ENV['DB_NAME'])) $_ENV['DB_NAME'] = 'personal_finance_tracker';
if (!isset($_ENV['DB_USER'])) $_ENV['DB_USER'] = 'root';
if (!isset($_ENV['APP_ENV'])) $_ENV['APP_ENV'] = 'development';
if (!isset($_ENV['SESSION_LIFETIME'])) $_ENV['SESSION_LIFETIME'] = 3600;
