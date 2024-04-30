<?php
namespace Impedro\Dragonfly\Utils;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Class to implement Date helper operations
 *
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
final class Date
{

    /**
     * Check if date is a weekend day
     *
     * @param $date
     * @return bool
     */
    public static function isWeekend($date)
    {
        $d = strtolower(date("l", strtotime($date)));
        return ($d == "saturday" || $d == "sunday");
    }

    /**
     * Get date now
     *
     * @return string
     */
    public static function now()
    {
        return date('d-m-Y H:i:s');
    }

    /**
     * Get number of days in the current month
     *
     * @param $month
     * @param $year
     * @return int
     */
    public static function daysInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Get last day of month
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    public static function getLastDayOfMonth($month, $year)
    {
        return date("t", mktime(0, 0, 0, $month, '01', $year));
    }

    /**
     * Get extended month name in portuguese
     *
     * @param mixed $month
     * @return string
     */
    public static function getMonthName($month)
    {
        switch ($month) {
            case 1:
                return "Janeiro";
            case 2:
                return "Fevereiro";
            case 3:
                return "Marco";
            case 4:
                return "Abril";
            case 5:
                return "Maio";
            case 6:
                return "Junho";
            case 7:
                return "Julho";
            case 8:
                return "Agosto";
            case 9:
                return "Setembro";
            case 10:
                return "Outubro";
            case 11:
                return "Novembro";
            case 12:
                return "Dezembro";
            default:
                return "";
        }
    }

    /**
     * Create a time ago text
     *
     * @param $timestamp
     * @return string
     */
    public static function timeago($timestamp)
    {
        if (intval($timestamp) == 0) {
            return 'n/a';
        }

        $difference = time() - intval($timestamp);

        $periods = array(
            "second",
            "minute",
            "hour",
            "day",
            "week",
            "month",
            "years",
            "decade"
        );

        $lengths = array(
            "60",
            "60",
            "24",
            "7",
            "4.35",
            "12",
            "10"
        );

        for ($j = 0; $difference >= $lengths[$j]; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        $text = "$difference $periods[$j] ago";
        return $text;
    }
}
