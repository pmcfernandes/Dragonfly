<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class SQLDelete extends SQLBase
{
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
     * Construct SQL statement
     *
     * return String
     */
    public function SQL()
    {
        $sql = 'DELETE FROM ' . $this->Tablename . ' ' . $this->GetWheres($this->wheres);
        return trim($sql);
    }
}
