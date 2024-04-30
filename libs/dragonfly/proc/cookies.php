<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Set Cookie Value With Number of Days Before Expiring
 * @return  void
 */
function set_cookie($name, $value, $days = 30)
{
    $expiretime = time() + (86400 * $days);
    setcookie($name, $value, $expiretime, "/");
}

/**
 * Get Cookie Value
 * @return  null | string
 */
function get_cookie($name)
{
    if (!empty($_COOKIE[$name])) {
        return $_COOKIE[$name];
    }
    return null;
}

/**
 * Clear Cookie Value
 * @return  boolean
 */
function clear_cookie($name)
{
    setcookie($name, "", time() - 3600, "/");
    return true;
}
