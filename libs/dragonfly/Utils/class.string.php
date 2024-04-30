<?php
namespace Impedro\Dragonfly\Utils;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class Str
{

    /**
     * A UTF-8 safe version of strotoupper()
     *
     * @param string $str
     * @return string
     */
    public static function upper($str)
    {
        return strtoupper($str);
    }

    /**
     * A UTF-8 safe version of strtolower()
     *
     * @param string $str
     * @return string
     */
    public static function lower($str)
    {
        return strtolower($str);
    }

    /**
     * Convert a string to 7-bit ASCII.
     *
     * @param string $str
     * @return string
     */
    public static function ascii($str)
    {
        return iconv("UTF-8", "ASCII", $str);
    }

    /**
     * Checks if a str contains another string
     *
     * @param string $str
     * @param string $needle
     * @return bool
     */
    public static function contains($str, $needle)
    {
        if (preg_match("/{$needle}/i", $str)) {
            return true;
        }

        return false;
    }

    /**
     * A UTF-8 safe version of strlen()
     *
     * @param string $str
     * @return int
     */
    public static function length($str)
    {
        return strlen($str);
    }

    /**
     * Replaces placeholders in string with value from array
     *
     * @param [type] $str
     * @param array $data
     * @param [type] $start
     * @return string
     */
    public static function template($str, $data = [], $start = '{{', $end = '}}')
    {
        $d = array();

        foreach ($data as $key => $value) {
            $key["$start $key $end"] = $value;
        }

        return strtr($str, $d);
    }

    /**
     * Normalize string removing accents and special chars
     *
     * @param [type] $str
     * @return string
     */
    public static function normalize($str)
    {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
        );

        return strtr($str, $table);
    }

    /**
     * Returns a random string of the selected length
     *
     * @param $number
     * @return string
     */
    public static function random($number)
    {
        $mask = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYWZ';
        $str = '';

        for ($i = 0; $i < $number; $i++) {
            $r = rand(0, strlen($mask) - 1);
            $str .= substr($mask, $r, 1);
        }

        return $str;
    }

    /**
     * String::split()
     *
     * @param mixed $str
     * @param mixed $delimiter
     * @return array
     */
    public static function split($str, $delimiter)
    {
        return explode($delimiter, $str);
    }

    /**
     * String::mid()
     *
     * @param mixed $str
     * @param mixed $startIndex
     * @param mixed $size
     * @return string
     */
    public static function mid($str, $startIndex, $size)
    {
        if (empty($str)) {
            return '';
        } else {
            return substr($str, $startIndex, $size);
        }
    }

    /**
     * String::left()
     *
     * @param mixed $str
     * @param mixed $size
     * @return string
     */
    public static function left($str, $size)
    {
        return self::mid($str, 0, $size);
    }

    /**
     * String::right()
     *
     * @param mixed $str
     * @param mixed $size
     * @return string
     */
    public static function right($str, $size)
    {
        return self::mid($str, self::length($str) - $size, $size);
    }

    /**
     * String::format()
     *
     * @return string
     */
    public static function format()
    {
        $args = func_get_args();

        if (count($args) == 0) {
            return '';
        }

        if (count($args) == 1) {
            return $args[0];
        }

        if (is_array($args[1])) {
            $a = array();
            array_push($a, $args[0]);

            foreach ($args[1] as $value) {
                array_push($a, $value);
            }

            $args = $a;
        }

        $str = array_shift($args);
        $str = preg_replace_callback('/\\{(0|[1-9]\\d*)\\}/', create_function('$match', '$args = ' . var_export($args, true) . '; return isset($args[$match[1]]) ? $args[$match[1]] : $match[0];'), $str);

        return $str;
    }

    /**
     * Create a new UUID
     *
     * @return string
     */
    public static function new_guid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }

    /**
     * Check if string is null or empty
     *
     * @param $str
     * @return bool
     */
    public static function isNullOrEmpty($str)
    {
        if (isset($str) && !empty($str)) {
            return false;
        }

        return true;
    }

    public static function ensureNotEndsWith($str, $str2) {
        if (!self::endsWith($str, $str2)) {
            return $str;
        } else {
            return substr(trim($str), 0, strlen(trim($str)) - strlen($str2));
        }
    }

    public static function endsWith($str, $test)
    {
        return substr_compare($str, $test, -strlen($test), strlen($test)) === 0;
    }
}
