<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\SQLBuilder\FieldType;
use Impedro\Dragonfly\SQLBuilder\SQLBase;

class SQLInsert extends SQLBase
{
    /**
     * Add value statement
     *
     * @param mixed $field
     * @param mixed $value
     * @param mixed $type
     */
    public function AddValue($field, $value, $type = FieldType::Numeric)
    {
        $this->fields[] = array(
            trim($field),
            $value,
            $type
        );
    }

    /**
     * Construct SQL statement
     *
     * return String
     */
    public function SQL()
    {
        $sql = 'INSERT INTO ' . $this->Tablename . ' (' . $this->GetFields($this->fields) . ') ';
        $sql .= 'VALUES (' . $this->GetValues($this->fields) . ')';

        return trim($sql);
    }
}
