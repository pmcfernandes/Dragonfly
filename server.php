<?php
declare(strict_types=1);

/**
 * PHP built-in server router script.
 *
 * Usage (from project root):
 * php -S localhost:8888 -t app server.php
 *
 * This script ensures the built-in server serves existing files directly
 * and routes other requests to the application front controller at `app/index.php`.
 */

// Only intended to be used with the PHP built-in webserver
if (PHP_SAPI !== 'cli-server') {
    // If executed in another SAPI, just bootstrap the app normally.
    require_once __DIR__ . '/app/index.php';
    return;
}

// Get the requested path and decode it
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($rawUri, PHP_URL_PATH) ?: '/';
$path = rawurldecode($path);

// Normalize path to prevent directory traversal attempts
$normalized = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
$normalized = ltrim($normalized, DIRECTORY_SEPARATOR);

$file = __DIR__ . DIRECTORY_SEPARATOR . $normalized;

// If the request maps to an existing file, let the built-in server serve it directly
if ($path !== '/' && is_file($file)) {
    return false; // serve the requested resource as-is
}

// Otherwise route the request to the application front controller
require_once __DIR__ . '/app/index.php';

