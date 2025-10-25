<?php

if (function_exists('spl_autoload_register') === false) {
    die('Your PHP installation does not support spl_autoload_register, which is required by Dragonfly Framework.');
} else {
    if (version_compare(PHP_VERSION, '7.4.0', '<')) {
        die('Dragonfly Framework requires at least PHP version 7.4.0. You are using PHP version ' . PHP_VERSION . '.');
    }
}

session_start();

define('LIBS_DIR', DRAGONFLY_LIB_PATH);
define('LIBS_PROC_DIR', DRAGONFLY_LIB_PATH . '/proc');

require_once (LIBS_DIR . '/../../vendor/autoload.php');
Requests::register_autoloader();

function autoload()
{
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LIBS_DIR));

    foreach ($rii as $file) {
        if (!$file->isDir() && $file->getExtension() === 'php')
            require_once ($file->getPathname());
    }
}

spl_autoload_register("autoload");
