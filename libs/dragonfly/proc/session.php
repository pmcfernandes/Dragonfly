<?php

/**
 * Set Application Session Variable 
 * @return  object
 */
function set_session($session_name, $session_value)
{
    clear_session($session_name);
    $_SESSION[$session_name] = $session_value;
    return $_SESSION[$session_name];
}

/**
 * Clear Session
 * @return  boolean
 */
function clear_session($session_name)
{
    $_SESSION[$session_name] = null;
    unset($_SESSION[$session_name]);
    return true;
}

/**
 * Return Session Value
 * @return  object
 */
function get_session($session_name)
{
    if (!empty($_SESSION[$session_name])) {
        return $_SESSION[$session_name];
    }
    return null;
}
