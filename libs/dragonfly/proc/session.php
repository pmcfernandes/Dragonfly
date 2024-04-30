<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Set Application Session Variable
 * @return  void
 */
function set_session($session_name, $session_value)
{
    clear_session($session_name);
    $_SESSION[$session_name] = $session_value;
}

/**
 * Clear Session
 * @return  bool
 */
function clear_session($session_name)
{
    $_SESSION[$session_name] = null;
    unset($_SESSION[$session_name]);
    return true;
}

/**
 * Return Session Value
 * @return object | bool
 */
function get_session($session_name)
{
    if (!empty($_SESSION[$session_name])) {
        return $_SESSION[$session_name];
    }
    return false;
}
