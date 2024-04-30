<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Smart version of echo with an if condition as first argument
 *
 * @param string $condition
 * @param string] $value
 * @param string $alternative
 * @return void
 */
function e($condition, $value, $alternative = null)
{
    if ($condition == TRUE) {
        echo $value;
    } else {
        if ($alternative != NULL) {
            echo $alternative;
        }
    }
}

/**
 * Generates a list of HTML attributes
 *
 * @param array $attr
 * @param string $prepend
 * @param string $append
 * @return string
 */
function attr(array $attr, $prepend = "", $append = "")
{
    $str = $prepend;

    foreach ($attr as $key => $value) {
        $str .= $key . '="' . $value . '"';
    }

    $str .= $append;
    return $str;
}

/**
 * Creates a script tag to load a javascript file
 *
 * @param string $url
 * @param array $options
 * @return string
 */
function js($url, $options = array())
{
    $str = '<script type="text/javascript" src="' . $url . '"' . attr($options, " ", "") . '></script>';
    return $str;
}

/**
 * Creates a link tag to load a css file
 *
 * @param string $url
 * @param array $options
 * @return string
 */
function css($url, $options = array())
{
    $str = '<link type="text/css" rel="stylesheet" href="' . $url . '"' . attr($options, " ", "") . '></link>';
    return $str;
}

/**
 * Shortcut for get_url()
 *
 * @return string
 */
function url()
{
    return get_url();
}

/**
 * Returns all params from the current Url
 *
 * @param [type] $url
 * @return array
 */
function params($url = NULL)
{
    if ($url == NULL) {
        $url = url();
    }

    $query = parse_url($url, PHP_URL_QUERY);
    parse_str($query, $arr);
    return $arr;
}

/**
 * Fix input data
 *
 * @param string $data
 * @return string
 */
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Parses markdown in the given string.
 *
 * @param string $text
 * @return string
 */
function markdown($text)
{
    $parser = new Parsedown();
    return $parser->text($text);
}

/**
 * Check if email is valid
 *
 * @param string $str
 * @return boolean
 */
function is_email($str)
{
    $email = test_input($str);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Check if string is URL
 *
 * @param string $str
 * @return boolean
 */
function is_url($str)
{
    $url = test_input($str);
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Slugify text
 *
 * @param string $text
 * @return string
 */
function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

/**
 * Dispatch Content in JSON Format
 *
 * @param object $data
 * @param string $status
 * @return void
 */
function render_json($data, $status = 'ok')
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}


function render_error($data = null, $code = 501)
{
    header("HTTP/1.1 $code $data", true, $code);
    exit;
}

/**
 * Return A clean Html entities free from xss attacks
 *
 * @param string $text
 * @return string
 */
function html_xss_clean($text)
{
    return htmlspecialchars($text);
}

/**
 * Concat Array  Values With Comma if REQUEST Value is Array
 *
 * @param array $arr
 * @return array
 */
function transform_request_data($arr)
{
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $arr[$key] = implode(',', $val);
        }
    }
    return $arr;
}

/**
 * Concat Array  Values With Comma
 * Specific for this Framework Only
 * @arr $_POST || $_GET data
 * @return  array
 */
function transform_multi_request_data($arr)
{
    $alldata = array();
    foreach ($arr as $key => $value) {
        $combine_vals = implode("", array_values($value));
        if (!empty($combine_vals)) {
            $alldata[] = transform_request_data($value);
        }
    }
    return $alldata;
}

/**
 * Concat Array  Values With Comma if REQUEST Value is Array
 *
 * @param [type] $fieldname
 * @param [type] $default
 * @return string
 */
function get_value($fieldname, $default = null)
{
    if (!empty($_REQUEST[$fieldname])) {
        $get = $_REQUEST[$fieldname];
        if (is_array($get)) {
            return implode(', ', $get);
        } else {
            return $get;
        }
    }
    return $default;
}

/**
 * Return current DateTime in Mysql Default Date Time Format
 *
 * @return string
 */
function datetime_now()
{
    return date("Y-m-d H:i:s");
}

/**
 * Return current Time in Mysql Default Date Time Format
 *
 * @return string
 */
function time_now()
{
    return date("H:i:s");
}

/**
 * Return current Date in Mysql Default Date Time Format
 *
 * @return string
 */
function date_now()
{
    return date("Y-m-d");
}

/**
 * Parse Date Or Timestamp Object into Relative Time (e.g. 2 days Ago, 2 days from now)
 *
 * @param  string $date
 * @return string
 */
function relative_date($date)
{
    if (empty($date)) {
        return "No date provided";
    }

    $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();

    //check if supplied Date is in unix date form
    if (is_numeric($date)) {
        $unix_date = $date;
    } else {
        $unix_date = strtotime($date);
    }


    // check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }

    // is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= "s";
    }

    return "$difference $periods[$j] {$tense}";
}

/**
 * Print out language translation of the default language
 *
 * @param [type] $name
 * @return void
 */
function print_lang($name)
{
    global $lang;
    $phrase = $lang->get_phrase($name);
    if (!empty($phrase)) {
        echo $phrase;
    } else {
        echo $name;
    }
}

/**
 * Return language translation of the default language
 *
 * @param [type] $name
 * @return void
 */
function get_lang($name)
{
    global $lang;
    $phrase = $lang->get_phrase($name);
    if (!empty($phrase)) {
        return $phrase;
    }
    return $name;
}

/**
 * Get The Current Url Address of The Application Server
 *
 * @return string
 */
function get_url()
{
    $url = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
    $url .= '://' . $_SERVER['SERVER_NAME'];
    $url .= in_array($_SERVER['SERVER_PORT'], array('80', '443')) ? '' : ':' . $_SERVER['SERVER_PORT'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}

/**
 * Will Return a $_GET value or null if key Does not exit or is Empty
 *
 * @param [type] $name
 * @return string
 */
function get_query_str_value($name)
{
    return (array_key_exists($name, $_GET) ? $_GET[$name] : null);
}

/**
 * Get a value from query string
 *
 * @param [type] $name
 * @return string
 */
function get_val($name)
{
    return get_query_str_value($name);
}

/**
 *  Will Return a $_GET Key Value or null if key Does not exit or is Empty
 *
 * @param [type] $key
 * @return string
 */
function get_query_string($key)
{
    $val = null;
    if (!empty($_GET[$key])) {
        $val = $_GET[$key];
    }

    return $val;
}

/**
 * Set Msg that Will be Display to User in a Session.
 *
 * @param [type] $msg
 * @param string $type
 * @param boolean $dismissable
 * @param integer $showduration
 * @return void
 */
function set_flash_msg($msg, $type = "success", $dismissable = true, $showduration = 5000)
{
    if ($msg !== '') {
        $class = null;
        $closeBtn = null;
        if ($type != 'custom') {
            $class = "alert alert-$type";
            if ($dismissable == true) {
                $class .= " alert-dismissable";
                $closeBtn = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            }
        }

        $msg = '<div data-show-duration="' . $showduration . '" id="flashmsgholder" class="' . $class . ' animated bounce">
                    ' . $closeBtn . '
                    ' . $msg . '
            </div>';

        set_session("MsgFlash", $msg);
    }
}

/**
 * Display The Message Set In MsgFlash Session On Any Page
 *
 * @return void
 */
function show_flash_msg()
{
    $f = get_session("MsgFlash");
    if (!empty($f)) {
        echo $f;
        clear_session("MsgFlash");
    }
}

/**
 * Check if current browser platform is a mobile browser
 *
 * @return boolean
 */
function is_mobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

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
