<?php

parse_str($_SERVER['QUERY_STRING']);

class Application {

    /**
     * Check if DEBUG is available
     *
     * @return int
     */
    public static function canDebug() {
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
    public static function debug($message) {
        if (!canDebug()) {
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
    private static function disableGlobals() {
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
	public static function render($action, $controller) {
		$instance = new $controller;
		
		if ($instance) {
			return $instance->$action();
		} else {
			die('Can\'t initialize controller, check if controller and action exists.');
		}
	}

    /**
     * Start application and Model-view-controller magic process
     *
     */
	public static function run() {
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
            $str = preg_replace('/' . $str, '', $request_url, 1);

            $url = trim($str, '/');
        }

        // Split the url into segments
        $segments = explode('/', $url);

        // Do our default checks
        if (isset($segments[0]) && $segments[0] != '') {
            $controller = $segments[0];
        }
        if (isset($segments[1]) && $segments[1] != '') {
            $action = $segments[1];
        }

        // Get our controller file
        $base_path = 'app/controllers/';
        $path = $base_path . $controller . '.php';

        if (file_exists($path)) {
            require_once($path);
        } else {
            $controller = $config['error_controller'];
            $path = $base_path . $controller . '.php';
            require_once($path);
        }

        // Check the action exists
        if (!method_exists($controller . 'Controller', $action)) {
            $controller = $config['error_controller'];
            $path = $base_path . $controller . '.php';
            require_once($path);
            $action = 'index';
        }

        // Create object and call method
        die(Application::render($action, $controller . 'Controller'));
	}
}
