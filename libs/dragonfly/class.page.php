<?php

class Page {

    /**
     * Get a variable from Query String
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public static function get($key, $default = null) {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    /**
     * Get a variable from Post
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public static function post($key, $default = null) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    /**
     * Check how requested POST method, useful for verify form submit
     *
     * @param $key
     * @return bool
     */
    public static function is_posted_by($key)
    {
        if (isset($_POST[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Check if request is a POST or a GET
     *
     * @return bool
     */
    public static function is_postback() {
        return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
    }

    /**
     * Check if AJAX call or normal call (post)
     */
    public static function is_callback() {
        echo $_SERVER['HTTP_X_REQUESTED_WITH'];

        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

}