<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Get
 *
 * @param string $name
 * @return string
 */
function get($param = null)
{
    return $param !== null ? $_GET[$param] : $_GET;
}

/**
 * Post
 *
 * @param string $name
 * @return string
 */
function post($param = null)
{
    return $param !== null ? $_POST[$param] : $_POST;
}

/**
 * Session
 *
 * @param string $name
 * @param mixed $value
 * @return void
 */
function session($name, $value)
{
    if (isset($value)) {
        $_SESSION[$name] = $value;
    }

    return $_SESSION[$name];
}

/**
 * Server
 *
 * @param string $name
 * @param mixed $value
 * @return void
 */
function server($name, $value = NULL)
{
    if (isset($value)) {
        $_SERVER[$name] = $value;
    }

    return $_SERVER[$name];
}

/**
 * Cookie
 *
 * @param string $name
 * @param mixed $value
 * @return string
 */
function cookie($name, $value)
{
    if (isset($value)) {
        setcookie($name, $value, time() + (86400 * 30), "/"); // 30 Days
    }

    return $_COOKIE[$name];
}

/**
 * Get header value from HTTP header
 *
 * @param string $name
 * @return string
 */
function http_header($name) {
    $headers = getallheaders();

    foreach ($headers as $_name => $value) {
        if ($name == $_name) {
            return $value;
        }
    }

    return '';
}

/**
 * Get Bearer authentication token from HTTP headers
 *
 * @return string
 */
function get_bearer_auth_token() {
    $authorization = http_header('Authorization');

    if ($authorization !== '') {
        if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
            return $matches[1];
        }
    }

    return '';
}

/**
 * Get request method
 *
 * @return string
 */
function request_method()
{
    return strtolower($_SERVER['REQUEST_METHOD']);
}

/**
 * Check if is post request
 *
 * @return boolean
 */
function is_post_request()
{
    return (request_method() == 'post');
}

/**
 * Check if is AJAX request
 *
 * @return boolean
 */
function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function url_origin($s, $use_forwarded_host = false)
{
    $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
    $sp       = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port     = $s['SERVER_PORT'];
    $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url($s, $use_forwarded_host = false)
{
    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
