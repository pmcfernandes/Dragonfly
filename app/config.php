<?php

use Impedro\Dragonfly\Config;

define('USE_SMTP', true);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_HOST', '');
define('SMTP_PORT', '');
define('DEFAULT_EMAIL', '');
define('DEFAULT_EMAIL_ACCOUNT_NAME', '');

global $config;

$config['base_url'] = '/'; // Base URL including trailing slash (e.g. http://localhost/)
$config['default_controller'] = 'home'; // Default controller to load
$config['error_controller'] = 'error'; // Controller used for errors (e.g. 404, 500 etc)
$config['admin_email'] = 'root@localhost'; // Email used for send trace messages

// Database configuration

$_config = Config::getInstance();

$parameters = json_decode(file_get_contents(__DIR__ . '/../conf/settings.json'),true)
    ['connection'][$_config->whereAmI()];

$config['db_host'] = $parameters['host'];
$config['db_name'] = $parameters['db'];
$config['db_user'] = $parameters['username'];
$config['db_password'] = $parameters['password'];
$config['db_port'] = $parameters['port'];
