<?php
namespace Impedro\Dragonfly\Utils;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

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
    public static function isMultipleOf($i, $numberToCompare)
    {
        return ($i % $numberToCompare) == 0;
    }

    /**
     * Check if string is a number
     *
     * @param mixed $i
     * @return bool
     */
    public static function isNumeric($i)
    {
        return is_numeric($i);
    }

    /**
     * Check if number is negative
     *
     * @param mixed $i
     * @return bool
     */
    public static function isNegative($i)
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
