<?php

/**
 * Get
 *
 * @param string $name
 * @param mixed $value
 * @return void
 */
function get($name, $value)
{
    if (isset($value)) {
        $_GET[$name] = $value;
    }

    return $_GET[$name];
}

/**
 * Post
 *
 * @param string $name
 * @param mixed $value
 * @return void
 */
function post($name, $value)
{
    if (isset($value)) {
        $_POST[$name] = $value;
    }

    return $_POST[$name];
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
 * @return void
 */
function cookie($name, $value)
{
    if (isset($value)) {
        setcookie($name, $value, time() + (86400 * 30), "/"); // 30 Days
    }

    return $_COOKIE[$name];
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
