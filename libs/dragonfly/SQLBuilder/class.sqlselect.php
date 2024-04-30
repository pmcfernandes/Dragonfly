<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\SQLBuilder\LogicalOperator;
use Impedro\Dragonfly\SQLBuilder\WhereOperator;
use Impedro\Dragonfly\SQLBuilder\JoinType;
use Impedro\Dragonfly\SQLBuilder\FieldType;
use Impedro\Dragonfly\SQLBuilder\OrderByDirection;
use Impedro\Dragonfly\SQLBuilder\SQLBase;

class SQLSelect extends SQLBase
{

    private $on;

    /**
     * Add fields to statement
     *
     * @param mixed $field
     */
    public function AddField($field)
    {
        $fieldnames = array();

        if (!is_array($field)) {
            $fieldnames = explode(',', trim($field));
        }

        foreach ($fieldnames as $fieldname) {
            $this->fields[] = trim($fieldname);
        }
    }

    /**
     * Add join to statement
     *
     * @param $table
     * @param int $joinType
     */
    public function AddJoin($table, $joinType = JoinType::Left)
    {
        $this->on = '';

        if ($joinType == JoinType::Inner)
            $this->Tablename .= ' INNER JOIN ' . trim($table);
        if ($joinType == JoinType::Left)
            $this->Tablename .= ' LEFT JOIN ' . trim($table);
        if ($joinType == JoinType::Right)
            $this->Tablename .= ' RIGHT JOIN ' . trim($table);
        if ($joinType == JoinType::Full)
            $this->Tablename .= ' FULL JOIN ' . trim($table);
    }

    /**
     * Add on to statement
     *
     * @param $field
     * @param $value
     * @param int $type
     * @param int $whereOperator
     * @param int $logical
     */
    public function AddOn($field, $value, $type = FieldType::Numeric, $whereOperator = WhereOperator::Equal, $logical = LogicalOperator::AND_)
    {
        if (strlen($this->on) == 0) {
            $this->on = ' ON ';
        } else {
            $this->on = ($logical == LogicalOperator::AND_ ? ' AND ' : ' OR ');
        }

        $this->Tablename .= $this->on . ' ' . trim($field) . SQLBase::Enclose(trim($field), $value, $type, $whereOperator, $logical);
    }

    /**
     * Add where to statement
     *
     * @param $field
     * @param $value
     * @param int $type
     * @param int $whereOperator
     * @param int $logical
     */
    public function AddWhere($field, $value, $type = FieldType::Numeric, $whereOperator = WhereOperator::Equal, $logical = LogicalOperator::AND_)
    {
        $this->wheres[] = array(
            trim($field),
            $value,
            $type,
            $whereOperator,
            $logical
        );
    }

    /**
     * Add Group By to Statement
     *
     * @param mixed $field
     */
    public function AddGroup($field)
    {
        foreach (explode(',', $field) as $fieldName) {
            $this->groupby[] = trim($fieldName);
        }
    }

    /**
     * Add Order By to Statement
     *
     * @param $field
     * @param int $order
     */
    public function AddOrder($field, $order = OrderByDirection::Asc)
    {
        $this->orderby[] = array(trim($field), ($order == OrderByDirection::Asc ? true : false));
    }

    /**
     * Construct SQL statement
     *
     * return String
     */
    public function SQL()
    {
        $sql = 'SELECT ' . $this->GetFields($this->fields) . ' ';
        $sql .= 'FROM ' . $this->Tablename;
        $sql .= $this->GetWheres($this->wheres);
        $sql .= $this->GetGroups($this->groupby);
        $sql .= $this->GetOrders($this->orderby);

        return trim($sql);
    }
}
