<?php
/*
 * PHP Server Router
 *
 * To emulate friendly URLs in the browser run:
 * |> php -S localhost:8888 -t app server.php
 */

 $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

 
if ($uri !== '/' && file_exists(__DIR__ . '/' . $uri)) {
    return false;
} else {
    require_once __DIR__ . '/app/index.php';    
}

