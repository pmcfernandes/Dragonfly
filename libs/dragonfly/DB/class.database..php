<?php
namespace Impedro\Dragonfly\DB;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use \PDO;

class Database {
    private $_dsn;
    private $_user;
    private $_password;
    private $_parameters = [];

    public static function getInstance()
    {
        global $config;

        $db = new Database($config['db_user'], $config['db_password'], $config['db_name'], $config['db_host']);
        $pdo = $db->__connect();
        return $pdo;
    }

    public function __construct($user, $pass, $dbname, $host) {
        $this->_dsn = "mysql:host=$host;dbname=$dbname";
        $this->_user = $user;
        $this->_password = $pass;
    }

    public function __connect() {
        $pdo = new PDO($this->_dsn, $this->_user, $this->_password, $this->_parameters);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
