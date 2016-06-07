<?php

class Date {

    private $timestamp;

    /**
     * Constructor of Date
     *
     * @param $timestamp
     */
    function __construct($timestamp) {
        $this->timestamp = $timestamp;
    }

    /**
     * Create a ago text
     *
     * @return string
     */
    function ago(){
        $timestamp = (int) $this->timestamp;
        if ($this->timestamp == 0) {
            return 'n/a';
        }

        $difference = time() - $timestamp;

        $periods = array(
            "second",
            "minute",
            "hour",
            "day",
            "week",
            "month",
            "years",
            "decade");

        $lengths = array(
            "60",
            "60",
            "24",
            "7",
            "4.35",
            "12",
            "10");

        for($j = 0; $difference >= $lengths[$j]; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);
        if($difference != 1) {
            $periods[$j].= "s";
        }

        $text = "$difference $periods[$j] ago";
        return $text;
    }


} 