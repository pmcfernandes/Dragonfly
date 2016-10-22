<?php
    define('DRAGONFLY_APP_PATH', '/app');
	define('DRAGONFLY_LIB_PATH', '/libs/dragonfly');
	
    require_once (DRAGONFLY_APP_PATH . '/config.php');
    Application::run();
