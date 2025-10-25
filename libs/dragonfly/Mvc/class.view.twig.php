<?php
namespace Impedro\Dragonfly\Mvc;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');


use \Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * View
 *
 * PHP version 7.0
 */
class ViewTwig
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/app/views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require_once $file;
        } else {
            throw new Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader(dirname(__DIR__) . '/app/views');
            $twig = new Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}
