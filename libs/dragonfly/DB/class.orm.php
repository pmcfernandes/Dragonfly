<?php
namespace Impedro\Dragonfly\DB;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use \ReflectionClass;
use Impedro\Dragonfly\DB\Database;
use Impedro\Dragonfly\SQLBuilder\SQLInsert;
use Impedro\Dragonfly\SQLBuilder\SQLUpdate;
use Impedro\Dragonfly\SQLBuilder\SQLDelete;

/**
 * Class to implement ORM
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class dbORM
{

    // Singleton object. Leave $me alone.
    public $Tablename;

    /**
     * Constructor of dbORM
     *
     * @param string $tableName
     */
    public function __construct($tableName = null)
    {
        $this->Tablename = $tableName;
    }

    /**
     * Get all primary keys from table
     *
     * @param $obj
     * @return array
     */
    private function getKey($obj)
    {
        if (is_string($obj) == true) {
            $tableName = dbORM::getTablename($obj);
        } else {
            $tableName = $obj->Tablename;
        }

        $keys = array();
        $db = Database::getInstance();

        if (PDODb::getDatabseType() != "mysql") {
            die("Unsupported database or metadata not founded.");
        } else {
            $q = $db->query("SHOW KEYS FROM " . $tableName . " WHERE Key_name = 'PRIMARY'");
            $rs = new DBLoop($q);

            while ($rs->valid()) {
                array_push($keys, $rs->current()->Column_name);
                $rs->next();
            }

            return $keys;
        }
    }

    /**
     * Create where condition
     * Created this function because is used in more than one call
     *
     * @param string|array $values
     * @return string
     */
    private static function createWhere($values = null)
    {
        if ($values == null) {
            return '';
        }

        $where = '';

        if (is_array($values)) {
            foreach ($values as $key => $val) {
                $where .= $key . ' = ' . (is_string($val) ? "'" . $val . "'" : $val);
                $where .= " and ";
            }
        } else {
            $where = $values;
        }

        if (!empty($where)) {
            $where = rtrim(" where " . $where, " and");
        }

        return $where;
    }

    /**
     * Create a new instance of object
     *
     * @param $obj
     * @return object
     */
    private static function createInstanceOfObject($obj)
    {
        $reflect = new ReflectionClass($obj);
        return $reflect->newInstance(strval($obj));
    }

    /**
     * Merge properties of object and data from record
     *
     * @param $instance
     * @param $data
     * @return null
     */
    private static function merge($instance, $data)
    {
        $reflect = new ReflectionClass($instance);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);

        if (count($props) == 0) {
            return null;
        }

        foreach ($props as $prop) {
            $arr = ((array) $data);

            if (isset($arr[$prop->getName()])) {
                $prop->setValue($instance, $arr[$prop->getName()]);
            }
        }

        return $instance;
    }

    /**
     * Get Tablename from instance
     *
     * @param $obj
     * @return mixed
     */
    private static function getTablename($obj)
    {
        $reflect = new ReflectionClass($obj);
        $prop = $reflect->getProperty("Tablename");

        if ($prop) {
            if (is_string($obj) == true) {
                return $prop->getValue(dbORM::createInstanceOfObject($obj));
            } else {
                return $prop->getValue($obj);
            }
        } else {
            die('Tablename property is not founded in ORM class.');
        }
    }

    /**
     * Find all records data from passed condition
     *
     * @param array $values
     * @return array
     */
    public static function findAll($values = null)
    {
        $obj = get_called_class();
        $sql = trim("select * from " . dbORM::getTablename($obj) . " " . dbORM::createWhere($values));

        $q = Database::getInstance()->query($sql);
        $rs = new DBLoop($q);

        $list = array();

        while ($rs->valid()) {
            $instance = dbORM::merge(dbOrm::createInstanceOfObject($obj), $rs->current());
            if ($instance == null) {
                continue;
            }

            array_push($list, $instance);
            $rs->next();
        }

        return $list;
    }

    /**
     * Find first record data from passed condition
     *
     * @param array $values
     * @return null
     */
    public static function find($values = null)
    {
        if ($values != null && !is_array($values)) {
            die('Values parameter must have a null or array structure.');
        }

        $obj = get_called_class();
        $sql = trim("select * from " . dbORM::getTablename($obj) . " " . dbORM::createWhere($values));

        $q = Database::getInstance()->query($sql);
        $rs = new DBLoop($q);

        if ($rs->count() > 0) {
            if ($rs->valid() == true) {
                return dbORM::merge(dbORM::createInstanceOfObject($obj), $rs->current());
            }
        }

        return null;
    }

    /**
     * Insert data in table of database
     *
     * @return bool|null
     */
    public function insert()
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        if (count($props) == 0) {
            return null;
        }

        $insert = new SQLInsert(dbORM::getTablename($this));

        foreach ($props as $prop) {
            $field = $prop->getName();
            $value = $prop->getValue($this);

            if ($field == "Tablename") {
                continue;
            }

            foreach ($this->getKey($this) as $i => $key) {
                if ($field != $key) {
                    $insert->AddValue($field, $value, $insert->detect($value));
                }
            }
        }

        return (Database::getInstance()->exec($insert->SQL()) == 1);
    }

    /**
     * Update data in table of database
     *
     * @return bool|null
     */
    public function update()
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        if (count($props) == 0) {
            return null;
        }

        $update = new SQLUpdate(dbORM::getTablename($this));

        foreach ($props as $prop) {
            $field = $prop->getName();
            $value = $prop->getValue($this);

            if ($field == "Tablename") {
                continue;
            }

            foreach ($this->getKey($this) as $i => $key) {
                if (!($field == $key)) {
                    $update->AddSet($field, $value, $update->detect($value));
                } else {
                    $update->AddWhere($field, $value, $update->detect($value));
                }
            }
        }

        return (Database::getInstance()->exec($update->SQL()) == 1);
    }

    /**
     * Delete current object from database
     *
     * @return bool
     */
    public function delete()
    {
        $reflect = new ReflectionClass($this);
        $delete = new SQLDelete(dbORM::getTablename($this));

        foreach ($this->getKey($this) as $i => $field) {
            $prop = $reflect->getProperty($field);
            if ($prop) {
                $value = $prop->getValue($this);
                $delete->AddWhere($field, $value, $delete->detect($value));
            }
        }

        return (Database::getInstance()->exec($delete->SQL()) == 1);
    }

    /**
     * Convert this instance to JSON
     *
     * @return null|string
     */
    public function toJSON()
    {
        $values = array();

        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);

        if (count($props) == 0) {
            return null;
        }

        foreach ($props as $prop) {
            if ($prop->getName() != "Tablename") {
                $values[$prop->getName()] = $prop->getValue($this);
            }
        }

        return json_encode($values);
    }
}
