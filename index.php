<?php
    set_time_limit(0);
    ignore_user_abort(1);
    $start = microtime(false);

    define('DRAGONFLY_APP_PATH', './app');
	define('DRAGONFLY_LIB_PATH', './libs/dragonfly');
    
    // Load Functions
    require_once (DRAGONFLY_LIB_PATH . '/loader.php');
    require_once (DRAGONFLY_APP_PATH . '/config.php');
    Application::run();

    echo microtime(false) - $start;
    ob_end_flush();
    exit(0);