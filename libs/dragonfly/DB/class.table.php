<?php
namespace Impedro\Dragonfly\DB;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\DB\Database;

/**
 * Class to implement Page
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class TableGateway
{
    protected $_table;
    private $_connection;

    public function __construct($tableName)
    {
        $this->_table = $tableName;
        $this->_connection = Database::getInstance();
    }

    public function insert(array $data)
    {
        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = "'$value'";
        }

        $fields = implode(',', $fields);
        $values = implode(',', $values);

        $insert = "INSERT INTO {$this->_table}($fields)VALUES($values)";

        return $this->_connection->exec($insert);
    }

    public function update(array $data, $where)
    {
        foreach ($data as $field => $value) {
            $sets[] = "$field='$value'";
        }
        $sets = implode(',', $sets);
        $update = "UPDATE {$this->_table} SET $sets WHERE $where";
        return $this->_connection->query($update);
    }

    public function query($fields = '*', $where = null, $ordem = null, $join = null, $limit = null)
    {
        // Logica para listar todos os registros
        $select = "SELECT $fields FROM $this->_table";

        if ($join) {
            $select .= " $join";
        }

        if ($where) {
            $select .= " $where";
        }

        if ($ordem) {
            $select .= " ORDER BY $ordem";
        }

        if ($limit) {
            $select .= " LIMIT $limit";
        }


        $pdoSt = $this->_connection->prepare($select);
        $pdoSt->execute();

        return $pdoSt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find($where, $fields = '*')
    {
        // Lógica para retornar um registro
        $select = "SELECT $fields FROM $this->_table WHERE $where";
        $pdoSt = $this->_connection->prepare($select);
        $pdoSt->execute();
        return $pdoSt->fetch(\PDO::FETCH_ASSOC);
    }

    public function delete($where)
    {
        $delete = "DELETE FROM $this->_table WHERE $where";
        return $this->_connection->query($delete);
    }

    public function count()
    {
        $stmt = $this->_connection->prepare("SELECT count(1) as nreg FROM $this->_table");
        $stmt->execute();
        $consulta = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $consulta['nreg'];
    }

    public function swapTable($_table){
        if($_table != ''){
            $this->_table = $_table;
        }
    }

    public function execSql($sql){
        $stmt = $this->_connection->prepare($sql);
        return $stmt->execute();
    }
}
