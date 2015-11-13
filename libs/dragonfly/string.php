<?php

if (!function_exists('startsWith')) {

    /**
     * Compare strings for check if start with
     *
     * @param string $str
     * @param string $str2
     * @return boolean
     */
    function startsWith($str, $str2)  {
        return (strpos($str, $str2) === 0);
    }
}

if (!function_exists('endsWith')) {
    /**
     * Compare strings for check if end with
     *
     * @param string $str
     * @param string $test
     * @return boolean
     */
    function endsWith($str, $test) {
        return substr_compare($str, $test, -strlen($test), strlen($test)) === 0;
    }
}

if (!function_exists('ensureNotEndsWith')) {
    /**
     * Ensure string not end with other string
     *
     * @param string $str
     * @param string $str2
     * @return string
     */
    function ensureNotEndsWith($str, $str2) {
        if (!endsWith($str, $str2)) {
            return $str;
        } else {
            return substr(trim($str), 0, strlen(trim($str)) - strlen($str2));
        }
    }
}

if (!function_exists('truncate')) {
    /**
     * Truncate string with suffix if needed
     *
     * @param $str
     * @param $maximumLength
     * @param $suffix
     * @return string
     */
    function truncate($str, $maximumLength, $suffix) {
        if (strlen($str) <= $maximumLength) {
            return $str;
        }

        return substr(trim($str), 0, $maximumLength) . $suffix;
    }
}

if (!function_exists('cleanText')) {
    /**
     * Clean up text by removing potentially dangerous tags
     *
     * @param $str
     * @return string
     */
    function cleanText($str) {
        return trim(iconv('UTF-8', 'UTF-8//IGNORE', str_replace ("  ", " ", strip_tags($str))));
    }
}

if (!function_exists('slugify')) {
    /**
     * Creates a friendly URL slug from a string
     *
     * @param string $str
     * @return string
     */
    function slugify($str) {
        $str = preg_replace('/[^a-zA-Z0-9 -]/', '', $str);
        $str = strtolower(str_replace(' ', '-', trim($str)));
        $str = preg_replace('/-+/', '-', $str);

        return $str;
    }

}

if (!function_exists('bbcode')) {
    /**
     * Converts BBCode into HTML
     *
     * @param $bbtext
     * @return mixed
     */
    function bbcode($bbtext) {
        $bbtags = array(
            '[heading1]' => '<h1>','[/heading1]' => '</h1>',
            '[heading2]' => '<h2>','[/heading2]' => '</h2>',
            '[heading3]' => '<h3>','[/heading3]' => '</h3>',
            '[h1]' => '<h1>','[/h1]' => '</h1>',
            '[h2]' => '<h2>','[/h2]' => '</h2>',
            '[h3]' => '<h3>','[/h3]' => '</h3>',

            '[paragraph]' => '<p>','[/paragraph]' => '</p>',
            '[para]' => '<p>','[/para]' => '</p>',
            '[p]' => '<p>','[/p]' => '</p>',
            '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
            '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
            '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
            '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',

            '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
            '[italic]' => '<span style="font-weight:bold;">','[/italic]' => '</span>',
            '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
            '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
            '[i]' => '<span style="font-weight:bold;">','[/i]' => '</span>',
            '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
            '[break]' => '<br>',
            '[br]' => '<br>',
            '[newline]' => '<br>',
            '[nl]' => '<br>',

            '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
            '[list]' => '<ul>','[/list]' => '</ul>',
            '[ul]' => '<ul>','[/ul]' => '</ul>',

            '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
            '[ol]' => '<ol>','[/ol]' => '</ol>',
            '[list_item]' => '<li>','[/list_item]' => '</li>',
            '[li]' => '<li>','[/li]' => '</li>',

            '[*]' => '<li>','[/*]' => '</li>',
            '[code]' => '<code>','[/code]' => '</code>',
            '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
            '[pre]' => '<pre>','[/pre]' => '</pre>',
        );

        $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);

        $bbextended = array(
            "/\[url](.*?)\[\/url]/i" => "<a href=\"http://$1\" title=\"$1\">$1</a>",
            "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a href=\"$1\" title=\"$1\">$2</a>",
            "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
            "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
            "/\[img\]([^[]*)\[\/img\]/i" => "<img src=\"$1\" alt=\" \" />",
            "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
            "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
            "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",
        );

        foreach($bbextended as $match=>$replacement){
            $bbtext = preg_replace($match, $replacement, $bbtext);
        }
        return $bbtext;
    }
}

if (!function_exists('str_is_utf8')) {
    /**
     * Checks a string for UTF-8 encoding.
     *
     * @param string $string
     * @return boolean true if string is UTF-8 encoded, false otherwise
     */
    function str_is_utf8($string) {
        $length = strlen($string);

        for ($i = 0; $i < $length; $i++) {
            if (ord($string[$i]) < 0x80) {
                $n = 0;
            } else if ((ord($string[$i]) & 0xE0) == 0xC0) {
                $n = 1;
            } else if ((ord($string[$i]) & 0xF0) == 0xE0) {
                $n = 2;
            } else if ((ord($string[$i]) & 0xF0) == 0xF0) {
                $n = 3;
            } else {
                return false;
            }

            for ($j = 0; $j < $n; $j++) {
                if ((++$i == $length) || ((ord($string[$i]) & 0xC0) != 0x80)) {
                    return false;
                }
            }
        }

        return false;
    }
}