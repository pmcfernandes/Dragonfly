<?php
namespace Impedro\Dragonfly;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

if (isset($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $qs);
}

class Application
{

    /**
     * Check if DEBUG is available
     *
     * @return int
     */
    public static function canDebug()
    {
        global $DEBUG;

        $allowed = array('127.0.0.1', '81.1.1.1');

        if (in_array($_SERVER['REMOTE_ADDR'], $allowed)) {
            return $DEBUG;
        } else {
            return 0;
        }
    }

    /**
     * Show a debug message on screen
     *
     * @param $message
     */
    public static function debug($message)
    {
        if (!Application::canDebug()) {
            return;
        }

        echo '<div style="background:yellow; color:black; border:1px solid black; padding:5px; margin:5px; white-space:pre;">';

        if (is_string($message)) {
            echo $message;
        } else {
            var_dump($message);
        }

        echo '</div>';
    }

    /**
     * Disable global variables
     *
     */
    private static function disableGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array(
                '_SESSION',
                '_POST',
                '_GET',
                '_COOKIE',
                '_REQUEST',
                '_SERVER',
                '_ENV',
                '_FILES'
            );

            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /**
     * Render an action inside a controller
     *
     * @param $action
     * @param $controller
     * @return mixed
     */
    public static function render($action, $controller)
    {
        // Provide an empty route params array by default so controllers
        // extending AbstractController receive the expected constructor arg.
        $route_params = [];
        $instance = new $controller($route_params);

        if ($instance) {
            // Call the action method. Controllers use magic __call to
            // dispatch to methods named e.g. indexAction. We call the
            // method name so the controller's __call will append 'Action'.
            return $instance->$action();
        }

        die("Can't initialize controller, check if controller and action exists.");
    }

    /**
     * Start application and Model-view-controller magic process
     *
     */
    public static function run()
    {
        Application::disableGlobals();

        global $config;

        // Set our defaults
        $controller = $config['default_controller'];
        $action = 'index';
        $url = '';
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';

        // Get request url and script url
        $request_url = (isset($request_uri)) ? $request_uri : '';
        $script_url = (isset($php_self)) ? $php_self : '';

        if (empty($controller)) { // Ignore MVC
            return;
        }

        // Get our url path and trim the / of the left and the right
        if ($request_url != $script_url) {
            $str = str_replace('index.php', '', $script_url);
            $str = str_replace('/', '\//', $str);
              $script_path = str_replace('index.php', '', $script_url);
              // Escape for use in regex
              $pattern = '/^' . preg_quote($script_path, '/') . '/';
              $url = preg_replace($pattern, '', $request_url, 1);
              $url = is_string($url) ? trim($url, '/') : '';
        }

        // Split the url into segments
        $segments = explode('/', $url);

        // Do our default checks and convert controller name to StudlyCaps
        if (isset($segments[0]) && $segments[0] != '') {
            // Convert hyphenated names to StudlyCaps (example: post-authors -> PostAuthors)
            $controller = str_replace(' ', '', ucwords(str_replace('-', ' ', $segments[0])));
        }
        if (isset($segments[1]) && $segments[1] != '') {
            $action = $segments[1];
        }

    // Get our controller file
    $base_path = 'app/controllers/';
    $path = $base_path . strtolower($controller) . '.php';

        if (file_exists($path)) {
            require_once($path);
        } else {
            $controller = $config['error_controller'];
            $path = $base_path . strtolower($controller) . '.php';
            require_once($path);
        }

        // Build controller class name and prefer the global namespace if present
        $controllerClass = $controller . 'Controller';
        $fqcn = '\\' . $controllerClass; // fully-qualified global class name

        if (!class_exists($controllerClass) && class_exists($fqcn)) {
            $controllerClassUsed = $fqcn;
        } else {
            $controllerClassUsed = $controllerClass;
        }

        // Check the action exists on the chosen class
        if (!method_exists($controllerClassUsed, $action)) {
            $controller = $config['error_controller'];
            $path = $base_path . strtolower($controller) . '.php';
            require_once($path);
            $controllerClass = $controller . 'Controller';
            $fqcn = '\\' . $controllerClass;
            $controllerClassUsed = class_exists($fqcn) ? $fqcn : $controllerClass;
            $action = 'index';
        }

        // Create object and call method
        die(Application::render($action, $controllerClassUsed));
    }
}
