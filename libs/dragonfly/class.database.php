<?php

class Database
{
    // Singleton object, leave $me alone.
    private static $me;
    private $pdo;
    private $dbType;

    /**
     * Constructor of Database
     *
     * @param mixed $type Database Type can be mysql|sqlite|oracle|mssql
     * @return Database
     */
    public function __construct($type = "mysql")  {
        global $config;

        if ($this->isSupported($type) == true) {
            $this->dbType = $type;
        } else {
            die("Database type is not supported yet.");
        }

        try {
            $this->pdo = new PDO($this->getConnectionString($this->dbType), $config['db_user'], $config['db_password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $ex) {
            Config::notify("Database error", $ex->getMessage());
        }
    }

    /**
     * Get type of database supported: mysql|sqlite|oracle|mssql
     *
     * @return mixed|string
     */
    public function getDbType()
    {
        return $this->dbType;
    }

    /**
     * Check if is supported database type
     *
     * @param mixed $type
     * @return bool
     */
    private function isSupported($type)
    {
        if ($type == "mysql" || $type == "sqlite" || $type == "oracle" || $type == "mssql") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Connection Strings
     *
     * @param mixed $type
     * @return string
     */
    public function getConnectionString($type = "mysql")
    {
        global $config;
        return $type . ":dbname=" . $config['db_name'] . ";host=" . $config['db_host'];
    }

    /**
     * Get singleton object instance
     *
     * @return Database
     *
     */
    public static function getInstance() {
        if (is_null(self::$me)) {
            self::$me = new Database();
        }

        return self::$me;
    }

    /**
     * Begin a transaction.
     */
    public function beginTransaction()
    {
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $this->pdo->beginTransaction();
    }

    /**
     * Commit the transaction.
     */
    public function commit()
    {
        $this->pdo->commit();
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }

    /**
     * Rollback the transaction.
     */
    public function rollback()
    {
        $this->pdo->rollBack();
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }

    /**
     * Get last insert id
     * @return int last insert id
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Get query
     *
     * @param mixed $sql
     * @param mixed $params
     * @return mixed
     */
    public function query($sql, $params = array()) {
        $stmt = $this->statement($sql, $params);
        $obj = $stmt->fetchAll();
        return $obj;
    }

    /**
     * Get scalar value
     *
     * @param mixed $sql
     * @param mixed $params
     * @return mixed
     */
    public function getScalar($sql, $params = array()) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
        $obj = $stmt->fetch();
        return $obj;
    }

    /**
     * Get first row values
     *
     * @param mixed $sql
     * @param mixed $params
     * @return mixed
     */
    public function getRow($sql, $params = array()) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $obj = $stmt->fetch();
        return $obj;
    }

    /**
     * Get data representation in JSON
     *
     * @param mixed $sql
     * @param mixed $params
     * @return string
     */
    public function getJSON($sql, $params = array()) {
        $stmt = $this->statement($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $stmt->fetchAll();
        return json_encode($arr);
    }

    /**
     * Execute CRUD sql into Database
     *
     * @param mixed $sql
     * @param mixed $params
     * @return int
     */
    public function execute($sql, $params = array()) {
        $stmt = $this->statement($sql, $params);

        if (!$stmt) {
            die("Function parameter sql is needed for continue");
        } else {
            if ($stmt->execute() == true) { // execute the statement
                return $stmt->rowCount();
            } else {
                return 0;
            }
        }
    }

    /**
     * Get query statement ready for execute
     *
     * @param mixed $sql
     * @param mixed $params
     * @return mixed
     */
    public function statement($sql, $params = array()) {
        $stmt = $this->pdo->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindParam(":$key", $value);
            }
        }               

        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt;
    }

}
