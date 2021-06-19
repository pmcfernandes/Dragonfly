<?php
//Start the Session
session_start();

define('LIBS_DIR', DRAGONFLY_LIB_PATH);
define('LIBS_PROC_DIR', DRAGONFLY_LIB_PATH . '/proc');

require_once(LIBS_DIR . '/../../vendor/autoload.php');
Requests::register_autoloader();

// Load Functions
require_once(LIBS_PROC_DIR . '/files.php');
require_once(LIBS_PROC_DIR . '/session.php');
require_once(LIBS_PROC_DIR . '/cookies.php');
require_once(LIBS_PROC_DIR . '/functions.php');
require_once(LIBS_PROC_DIR . '/helpers.php');
require_once(LIBS_PROC_DIR . '/forms.php');
require_once(LIBS_PROC_DIR . '/passwords.php');
require_once(LIBS_PROC_DIR . '/remote.php');
require_once(LIBS_PROC_DIR . '/security.php');
require_once(LIBS_PROC_DIR . '/simpledb_mssql.php');
require_once(LIBS_PROC_DIR . '/vars.php');
// require_once(LIBS_PROC_DIR . '/console-args.php');

require_once(DRAGONFLY_LIB_PATH . '/string.php');
require_once(DRAGONFLY_LIB_PATH . '/class.config.php');
require_once(DRAGONFLY_LIB_PATH . '/class.string.php');
require_once(DRAGONFLY_LIB_PATH . '/class.date.php');
require_once(DRAGONFLY_LIB_PATH . '/class.number.php');
require_once(DRAGONFLY_LIB_PATH . '/class.server.php');
require_once(DRAGONFLY_LIB_PATH . '/class.app.php');
require_once(DRAGONFLY_LIB_PATH . '/class.model.php');
require_once(DRAGONFLY_LIB_PATH . '/class.view.php');
require_once(DRAGONFLY_LIB_PATH . '/class.controller.php');
require_once(DRAGONFLY_LIB_PATH . '/class.template.php');
require_once(DRAGONFLY_LIB_PATH . '/class.page.php');
require_once(DRAGONFLY_LIB_PATH . '/class.file.php');
require_once(DRAGONFLY_LIB_PATH . '/class.regex.php');
require_once(DRAGONFLY_LIB_PATH . '/class.sql.php');
require_once(DRAGONFLY_LIB_PATH . '/class.pdo.php');
require_once(DRAGONFLY_LIB_PATH . '/class.database.php');
require_once(DRAGONFLY_LIB_PATH . '/class.dbloop.php');
require_once(DRAGONFLY_LIB_PATH . '/class.table.php');
require_once(DRAGONFLY_LIB_PATH . '/class.orm.php');
require_once(DRAGONFLY_LIB_PATH . '/class.cache.php');
require_once(DRAGONFLY_LIB_PATH . '/class.cookie.php');
require_once(DRAGONFLY_LIB_PATH . '/class.html.php');
require_once(DRAGONFLY_LIB_PATH . '/class.upload.php');
require_once(DRAGONFLY_LIB_PATH . '/class.pagination.php');
require_once(DRAGONFLY_LIB_PATH . '/class.restserver.php');
require_once(DRAGONFLY_LIB_PATH . '/class.interop.php');
require_once(DRAGONFLY_LIB_PATH . '/class.version.php');
require_once(DRAGONFLY_LIB_PATH . '/class.event.php');
require_once(DRAGONFLY_LIB_PATH . '/class.jwt.php');
require_once(DRAGONFLY_LIB_PATH . '/class.crypto.php');
require_once(DRAGONFLY_LIB_PATH . '/class.gd.php');
require_once(DRAGONFLY_LIB_PATH . '/class.mail.php');
require_once(DRAGONFLY_LIB_PATH . '/class.lang.php');
require_once(DRAGONFLY_LIB_PATH . '/class.validation.php');
require_once(DRAGONFLY_LIB_PATH . '/class.openweathermap.php');
require_once(DRAGONFLY_LIB_PATH . '/class.antixss.php');
require_once(DRAGONFLY_LIB_PATH . '/class.form.php');
require_once(DRAGONFLY_LIB_PATH . '/class.header.php');
require_once(DRAGONFLY_LIB_PATH . '/class.csrf.php');

function autoload($class)
{
    if (!class_exists($class)) {
        if (mb_strpos($class, "Controller") === true) {
            require(__DIR__ . "/controllers/$class.php");
        } else {
            require(__DIR__ . "/models/$class.php");
        }
    }
}

spl_autoload_register("autoload");
