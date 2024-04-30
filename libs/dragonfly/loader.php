<?php
session_start();

define('LIBS_DIR', DRAGONFLY_LIB_PATH);
define('LIBS_PROC_DIR', DRAGONFLY_LIB_PATH . '/proc');

require_once (LIBS_DIR . '/../../vendor/autoload.php');
Requests::register_autoloader();

function autoload()
{
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LIBS_DIR));

    foreach ($rii as $file) {
        if (!$file->isDir())
            require ($file->getPathname());
    }
}

spl_autoload_register("autoload");
