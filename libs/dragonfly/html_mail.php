<?php

if (!function_exists('html_mail')) {
    /**
     * Send email with HTML support
     *
     * @param $from
     * @param $to
     * @param $subject
     * @param $msg
     * @return bool
     */
    function html_mail($from, $to, $subject, $msg)
    {
        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: " . $from;

        return (mail($to, $subject, $msg, $headers) == 1);
    }
}
