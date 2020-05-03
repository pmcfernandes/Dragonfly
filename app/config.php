<?php

global $config;

$config['base_url'] = '/'; // Base URL including trailing slash (e.g. http://localhost/)
$config['default_controller'] = 'home'; // Default controller to load	
$config['error_controller'] = 'error'; // Controller used for errors (e.g. 404, 500 etc)  
$config['admin_email'] = 'root@localhost'; // Email used for send trace messages

// Database configuration
$config['db_host'] = 'localhost';
$config['db_name'] = 'framework.data2';
$config['db_user'] = 'root';
$config['db_password'] = 'P@ssw0rd';
