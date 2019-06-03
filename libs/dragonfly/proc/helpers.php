<?php

/**
 * Generates a list of HTML attributes
 *
 * @param array $attr
 * @param string $before
 * @param string $after
 * @return string
 */
function attr(array $attr = null, string $before = null, string $after = null) {    
    $str = '';

    if ($before != null) {
        $str .= $before;
    }

    $str .= HTML::attributes($attr);
    
    if ($after != null) {
        $str .= $after;
    }

    return $str;
}

/**
 * Checks / returns a CSRF token
 *
 * @param string $formName
 * @param string $check
 * @return void
 */
function csrf(string $formName, string $token = null) {
    if ($token != null) {
        return $token === csrf($formName);
    } else {
        if (!session_id()) { session_start(); }
        return sha1($formName . session_id() . 'gsfhs154aergz2#');
    }
}