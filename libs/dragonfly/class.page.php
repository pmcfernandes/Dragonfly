<?php
namespace Impedro\Dragonfly;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Class to implement Page
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class Page
{

    public static function printDoctype()
    {
        echo '<!DOCTYPE html>';
    }

    public static function printTitle($title)
    {
        echo "<title>$title</title>";
    }

    public static function printDescription($description)
    {
        echo '<meta name="description" content="' . $description . '">';
    }

    public static function enableResponsiveDesign()
    {
        echo '<meta name="viewport" content="width=device-width">';
    }

    public static function printCharset()
    {
        echo '<meta charset="utf-8">';
    }

    public static function blockRobots()
    {
        echo '<meta name="robots" content="nofollow,noindex">';
    }

    /**
     * Get a variable from Query String
     *
     * @param $key
     * @param string $default
     * @return string
     */
    public static function get($key, $default = null)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    /**
     * Get a variable from Post
     *
     * @param $key
     * @param string $default
     * @return string
     */
    public static function post($key, $default = null)
    {
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
    public static function is_postback()
    {
        return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
    }

    /**
     * Check if AJAX call or normal call (post)
     */
    public static function is_callback()
    {
        echo $_SERVER['HTTP_X_REQUESTED_WITH'];

        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
}
