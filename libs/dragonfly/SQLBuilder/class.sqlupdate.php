<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\SQLBuilder\LogicalOperator;
use Impedro\Dragonfly\SQLBuilder\WhereOperator;
use Impedro\Dragonfly\SQLBuilder\FieldType;
use Impedro\Dragonfly\SQLBuilder\SQLBase;

class SQLUpdate extends SQLBase
{
    /**
     * Add value statement
     *
     * @param mixed $field
     * @param mixed $value
     * @param mixed $type
     */
    public function AddSet($field, $value, $type = FieldType::Numeric)
    {
        $this->fields[] = array(
            trim($field),
            $value,
            $type
        );
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
            $field,
            $value,
            $type,
            $whereOperator,
            $logical
        );
    }

    /**
     * Construct SQL statement
     *
     * return String
     */
    public function SQL()
    {
        $sql = 'UPDATE ' . $this->Tablename . ' ';
        $sql .= 'SET ' . $this->GetSets($this->fields);
        $sql .= $this->GetWheres($this->wheres);

        return trim($sql);
    }
}
