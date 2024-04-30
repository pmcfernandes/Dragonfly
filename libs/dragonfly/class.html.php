<?php
namespace Impedro\Dragonfly;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Class to implement HTML helper
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class HTML
{
    /**
     * Converts a string to a html-safe string
     *
     * @param string $str
     * @return string
     */
    public static function htmlentities($str)
    {
        return htmlentities($str, ENT_QUOTES);
    }

    /**
     * Takes an array of attributes and turns it into a string
     *
     * @param $attrs
     * @return string
     */
    public static function attributes($attrs)
    {
        if (!is_array($attrs) || count($attrs) == 0) {
            return '';
        }

        $str = '';

        foreach ($attrs as $key => $value) {
            $str .= $key . '="' . $value . '" ';
        }

        return trim($str);
    }

    /**
     * Creates a HTML tag
     *
     * @param $name
     * @param array $attrs
     * @param string $content
     * @return string
     */
    public static function tag($name, $attrs = array(), $content = null)
    {
        $str = '<' . $name;

        if (is_array($attrs) && count($attrs) > 0) {
            $str .= ' ' . HTML::attributes($attrs);
        }

        $str .= '>' . ($content == null ? '' : $content) . '</' . $name . '>';
        return $str;
    }

    /**
     * Generates an img tag
     *
     * @param string $src
     * @param array $attrs
     * @return string
     */
    public static function img($src, $attrs = array())
    {
        return '<img src="' .  $src . '"' . HTML::attributes($attrs) . ' />';
    }

    /**
     * Generates an a tag with an absolute Url
     *
     * @param [type] $url
     * @param [type] $text
     * @param array $attrs
     * @return string
     */
    public static function a($url, $text = NULL, $attrs = array())
    {
        return '<a href="' . $url . '"' . HTML::attributes($attrs) . '>' . ($text == NULL ? $url : $text) . '</a>';
    }

    /**
     * Generates an "a mailto" tag
     *
     * @param string $email
     * @param string $text
     * @param array $attrs
     * @return string
     */
    public static function email($email, $text = NULL, $attrs = array())
    {
        return HTML::a('mailto:' . $email, $text, $attrs);
    }
}
