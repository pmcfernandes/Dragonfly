<?php

/**
 * Class to implement easy cookie access
 *
 * @since 1.0
 * @version 1.0
 * @author Pedro Fernandes
 */
class Cookie
{
    /**
     * Set a new cookie
     *
     * @param mixed $name
     * @param mixed $content
     */
    public static function setCookie($name, $content) {
        $authDomain = Config::getInstance()->authDomain;
        setcookie($name, base64_encode($content), mktime(0, 0, 0, 6, 2, 2037), '/', (empty($authDomain) ? null : $authDomain));
    }

    /**
     * Get cookie value
     *
     * @param mixed $name
     * @return string
     */
    public static function getCookie($name) {
        return base64_decode($_COOKIE[$name]);
    }

    /**
     * Check if cookie is already set
     *
     * @param $name
     * @return bool
     */
    public static function isCookie($name) {
        return (isset($_COOKIE[$name]) == true);
    }

}