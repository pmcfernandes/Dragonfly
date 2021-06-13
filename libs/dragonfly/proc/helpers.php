<?php

/**
 * Checks / returns a CSRF token
 *
 * @param string $formName
 * @param string $check
 * @return void
 */
function csrf(string $formName, string $token = null)
{
    if ($token != null) {
        return $token === csrf($formName);
    } else {
        if (!session_id()) {
            session_start();
        }
        return sha1($formName . session_id() . 'gsfhs154aergz2#');
    }
}
