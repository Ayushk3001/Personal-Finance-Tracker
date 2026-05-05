<?php

// Simple autoloader for PSR-0 namespace convention
spl_autoload_register(function ($class) {
    $prefix = 'Config\\';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) === 0) {
        $relative_class = substr($class, $len);
        $file = __DIR__ . '/../../backend/config/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    $prefix = 'Classes\\';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) === 0) {
        $relative_class = substr($class, $len);
        $file = __DIR__ . '/../../backend/src/classes/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    $prefix = 'Utils\\';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) === 0) {
        $relative_class = substr($class, $len);
        $file = __DIR__ . '/../../backend/src/utils/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});
