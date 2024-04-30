<?php
namespace Impedro\Dragonfly\Http;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class Server
{

    /**
     * Returns the server's IP address
     *
     * @return string
     */
    public static function address()
    {
        return $_SERVER['SERVER_ADDR'];
    }

    /**
     * Checks if the request is being served by the CLI
     *
     * @return bool
     */
    public static function cli()
    {
        return (php_sapi_name() === 'cli');
    }

    /**
     * Gets a value from the $_SERVER array
     *
     * @param string $name
     * @return string
     */
    public static function get($name)
    {
        return $_SERVER[$name];
    }

    /**
     * Returns the correct host
     *
     * @return string
     */
    public static function host()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Checks for a https request
     *
     * @return bool
     */
    public static function https()
    {
        return isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'];
    }

    /**
     * Returns the correct port number
     *
     * @return string
     */
    public static function port()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * Help to sanitize some _SERVER keys
     *
     * @param string $key
     * @return string
     */
    public static function sanitize($name)
    {
        return filter_var(Server::get($name), FILTER_SANITIZE_STRING);
    }
}
