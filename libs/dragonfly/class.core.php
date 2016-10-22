<?php

/**
 * Class to implement String helper operations
 *
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
final class Text
{

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
     * String::upper()
     *
     * @param mixed $str
     * @return string
     */
    public static function upper($str)
    {
        return strtoupper($str);
    }

    /**
     * Get length of String
     *
     * @param $str
     * @return int
     */
    public static function length($str)
    {
        return strlen($str);
    }

    /**
     * String::lower()
     *
     * @param mixed $str
     * @return string
     */
    public static function lower($str)
    {
        return strtolower($str);
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
        return substr($str, $startIndex, $size);
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
        return String::mid($str, 0, $size);
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

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535));

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

}

/**
 * Class to implement Numeric helper operations
 *
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
final class Number
{
    /**
     * Check if number is a multiple of other number
     *
     * @param mixed $i
     * @param mixed $numberToCompare
     * @return bool
     */
    public function isMultipleOf($i, $numberToCompare)
    {
        return ($i % $numberToCompare) == 0;
    }

    /**
     * Check if string is a number
     *
     * @param mixed $i
     * @return bool
     */
    public function isNumeric($i)
    {
        return is_numeric($i);
    }

    /**
     * Check if number is negative
     *
     * @param mixed $i
     * @return bool
     */
    public function isNegative($i)
    {
        if (is_numeric($i) == true) {
            return ($i < 0);
        } else {
            return false;
        }
    }

    /**
     * Check if number is a par
     *
     * @param mixed $i
     * @return bool
     */
    public static function isPar($i)
    {
        return self::isMultipleOf($i, 2);
    }

}

