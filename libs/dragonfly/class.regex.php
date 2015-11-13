<?php

class Regex
{

    /**
     * Gets result of regular expression compare
     *
     * @param $expression
     * @param $str
     * @return int
     */
    public static function test($expression, $str) {
        return preg_match($expression, $str);
    }

    /**
     * Check if string is a valid email
     *
     * @param $email
     * @return bool
     */
    public static function checkEmail($email)  {
        return Regex::test("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(.[[:lower:]]{2,3})(.[[:lower:]]{2})?$/", $email);
    }
}