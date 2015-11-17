<?php

	// Base URL including trailing slash (e.g. http://localhost/)
	$config['base_url'] = '/';

	// Default controller to load
	$config['default_controller'] = 'home';
		
	// Controller used for errors (e.g. 404, 500 etc)
	$config['error_controller'] = 'error';

    // Email used for send trace messages
    $config['admin_email'] = 'root@localhost';

    // Database configuration
    $config['db_host'] = 'localhost';
    $config['db_name'] = 'framework.data2';
    $config['db_user'] = 'root';
    $config['db_password'] = 'P@ssw0rd';

    // Load Functions
    require ('../loader.php');