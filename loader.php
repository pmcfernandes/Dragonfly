<?php
    define('DRAGONFLY_LIB_PATH', '/libs/dragonfly');

    //Start the Session
    session_start();

	// Load Functions
    require (DRAGONFLY_LIB_PATH . '/string.php');
    require (DRAGONFLY_LIB_PATH . '/html_mail.php');
    require (DRAGONFLY_LIB_PATH . '/class.config.php');
	require (DRAGONFLY_LIB_PATH . '/class.app.php');
    require (DRAGONFLY_LIB_PATH . '/class.model.php');
    require (DRAGONFLY_LIB_PATH . '/class.view.php');
    require (DRAGONFLY_LIB_PATH . '/class.controller.php');
    require (DRAGONFLY_LIB_PATH . '/class.template.php');
    require (DRAGONFLY_LIB_PATH . '/class.page.php');
    require (DRAGONFLY_LIB_PATH . '/class.file.php');
    require (DRAGONFLY_LIB_PATH . '/class.regex.php');
    require (DRAGONFLY_LIB_PATH . '/class.sql.php');
    require (DRAGONFLY_LIB_PATH . '/class.database.php');
    require (DRAGONFLY_LIB_PATH . '/class.dbloop.php');
    require (DRAGONFLY_LIB_PATH . '/class.orm.php');
    require (DRAGONFLY_LIB_PATH . '/class.cache.php');
    require (DRAGONFLY_LIB_PATH . '/class.cookie.php');
    require (DRAGONFLY_LIB_PATH . '/class.html.php');
    require (DRAGONFLY_LIB_PATH . '/class.upload.php');
    require (DRAGONFLY_LIB_PATH . '/class.pagination.php');
    require (DRAGONFLY_LIB_PATH . '/class.restserver.php');
    require (DRAGONFLY_LIB_PATH . '/class.interop.php');
    require (DRAGONFLY_LIB_PATH . '/class.core.php');
    require (DRAGONFLY_LIB_PATH . '/class.date.php');
    require (DRAGONFLY_LIB_PATH . '/class.event.php');
    require (DRAGONFLY_LIB_PATH . '/class.crypto.php');
    require (DRAGONFLY_LIB_PATH . '/class.gd.php');
