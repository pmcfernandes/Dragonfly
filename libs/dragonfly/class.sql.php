<?php

class FieldType
{
    const Numeric = 0;
    const Text = 1;
    const Date = 2;
    const Boolean = 3;
    const Currency = 4;
    const Empty_ = 5;
    const Image = 6;
    const Memo = 7;
    const Decimal4 = 8;
    const Decimal18 = 9;

}

class WhereOperator
{
    const Equal = 0;
    const IsNull = 1;
    const IsNotNull = 2;
    const Like = 3;
    const In = 4;
    const NotLike = 5;
    const NotEqual = 6;
    const Greater = 7;
    const Less = 8;
    const Greater_OR_Equal = 9;
    const Less_OR_Equal = 10;
    const NotIn = 11;
    const Contains = 12;
    const BeginsWith = 13;
    const EndWith = 14;
    const NotContains = 15;
    const NotBeginsWith = 16;
    const NotEndsWith = 17;
}

class LogicalOperator
{
    const AND_ = 0;
    const OR_ = 1;

}

class JoinType
{
    const Inner = 0;
    const Left = 1;
    const Right = 2;
    const Full = 3;

}

class OrderByDirection
{
    const Asc = 0;
    const Desc = 1;

}

class SQLBase
{
    protected $fields = array();
    protected $wheres = array();
    protected $groupby = array();
    protected $orderby = array();
    protected $joins = array();

    /* Public variables */
    public $Tablename;

    /**
     * Constructor of SQLBase
     *
     * @param $tableName
     */
    function __construct($tableName)
    {
        $this->Tablename = $tableName;
    }

    /**
     * Sanitize sql query
     *
     * @param mixed $sql
     * @return string
     */
    public static function Sanitize($sql)
    {
        return mysql_real_escape_string($sql);
    }

    /**
     * Get values
     *
     * @param mixed $fields
     * @return string
     */
    protected function GetValues($fields)
    {
        if (count($fields) == 0) {
            die('Values not found or is empty. Add new values to continue;');
        } else {
            $str = ' ';

            foreach ($fields as $field) {
                $str .= $this->Enclose(trim($field[0]), $field[1], $field[2]
                        , WhereOperator::Equal
                        , LogicalOperator::AND_
                        , true) . ', ';
            }

            return ensureNotEndsWith(trim($str), ',');
        }
    }

    /**
     * Get sets
     *
     * @param mixed $fields
     * @return string
     */
    protected function GetSets($fields)
    {
        if (count($fields) == 0) {
            die('Values not found or is empty. Add new values to continue;');
        } else {
            $str = ' ';

            foreach ($fields as $field) {
                $str .= trim($field[0]) . ' = ' . $this->Enclose(trim($field[0]), $field[1], $field[2]
                        , WhereOperator::Equal
                        , LogicalOperator::AND_
                        , true) . ', ';
            }

            return ensureNotEndsWith(trim($str), ',');
        }
    }

    /**
     * Get fields list
     *
     * return String
     */
    protected function GetFields($fields)
    {
        $str = '';

        if (count($fields) == 0) {
            return '*';
        } else {
            for ($i = 0; $i < count($fields); $i++) {
                $str .= trim(is_array($fields[$i]) ? $fields[$i][0] : $fields[$i]) . ', ';
            }

            return ensureNotEndsWith(trim($str), ',');
        }
    }

    /**
     * Get Wheres
     *
     * @param mixed $wheres
     * @return mixed
     */
    protected function GetWheres($wheres)
    {
        if (count($wheres) == 0) {
            return '';
        } else {
            $str = '';

            foreach ($wheres as $where) {
                if (strlen($str) == 0) {
                    $str = ' WHERE ';
                } else {
                    if ($where[4] == LogicalOperator::OR_) {
                        $str .= ' OR ';
                    } else {
                        $str .= ' AND ';
                    }
                }
                $str .= trim($where[0]) . SQLBase::Enclose($where[0], $where[1], $where[2], $where[3], $where[4]);
            }

            return $str;
        }
    }

    /**
     * Get Groups
     *
     * @param mixed $groups
     * @return mixed
     */
    protected function GetGroups($groups)
    {
        if (count($groups) == 0) {
            return '';
        } else {
            return ' GROUP BY ' . $this->GetFields($groups);
        }
    }

    /**
     * Get Orders
     *
     * @param mixed $orders
     * @return mixed
     */
    protected function GetOrders($orders)
    {
        if (count($orders) == 0) {
            return '';
        } else {
            $str = '';
            foreach ($orders as $order) {
                if ($order[1] == true || $order[1] == 'ASC' || $order[1] == OrderByDirection::Asc) {
                    $str = trim($order[0]) . ' ' . 'ASC, ';
                } else {
                    $str = trim($order[0]) . ' ' . 'DESC, ';
                }
            }

            return ' ORDER BY ' . ensureNotEndsWith(trim($str), ',');
        }
    }

    /**
     * Detect a type of value
     *
     * @param $value
     * @return int
     */
    public function detect($value)
    {
        if (is_string($value)) {
            return FieldType::Text;
        }

        if (is_bool($value)) {
            return FieldType::Boolean;
        }

        if (is_double($value)) {
            return FieldType::Currency;
        }

        if (is_int($value) || is_integer($value) || is_numeric($value)) {
            return FieldType::Numeric;
        }

        return FieldType::Empty_;
    }

    /**
     * Enclose values
     *
     * @param $field
     * @param $value
     * @param int $type
     * @param int $whereOperator
     * @param int $logical
     * @param bool $withoutWhereOperator
     * @return int|string
     */
    protected static function Enclose($field, $value, $type = FieldType::Numeric, $whereOperator = WhereOperator::Equal, $logical = LogicalOperator::AND_, $withoutWhereOperator = false)
    {
        $str = "";
        $strEnclose = "";

        if ($whereOperator == WhereOperator::IsNull) {
            $strEnclose = " IS NULL";
        } elseif ($whereOperator == WhereOperator::IsNotNull) {
            $strEnclose = " IS NOT NULL";
        } elseif ($whereOperator == WhereOperator::Like) {
            $strEnclose = " LIKE '" . $value . "'";
        } elseif ($whereOperator == WhereOperator::Contains) {
            $strEnclose = " LIKE '%" . $value . "%'";
        } elseif ($whereOperator == WhereOperator::BeginsWith) {
            $strEnclose = " LIKE '%" . $value . "'";
        } elseif ($whereOperator == WhereOperator::EndWith) {
            $strEnclose = " LIKE '" . $value . "%'";
        } elseif ($whereOperator == WhereOperator::NotContains) {
            $strEnclose = " NOT LIKE '%" . $value . "%'";
        } elseif ($whereOperator == WhereOperator::NotBeginsWith) {
            $strEnclose = " NOT LIKE '" . $value . "%'";
        } elseif ($whereOperator == WhereOperator::NotEndsWith) {
            $strEnclose = " NOT LIKE '%" . $value . "'";
        } elseif ($whereOperator == WhereOperator::NotLike) {
            $strEnclose = " NOT LIKE '" . $value . "'";
        } elseif ($whereOperator == WhereOperator::In) {
            $strEnclose = " IN (" . $value . ")";
        } elseif ($whereOperator == WhereOperator::NotIn) {
            $strEnclose = " NOT IN (" . $value . ")";
        } else {
            if ($value == null || strlen($value) == 0) {
                $strEnclose = ($withoutWhereOperator == true ? "NULL" : " IS NULL");
            }

            if ($value != null || strlen($value) != 0) {
                switch ($type) {
                    case FieldType::Decimal4:
                    case FieldType::Decimal18:
                    case FieldType::Currency:
                    case FieldType::Numeric:
                        $str = $value;
                        break;
                    case FieldType::Boolean:
                        $str = ($value == true ? 1 : 0);
                        break;
                    case FieldType::Text:

                    case FieldType::Memo:
                        $str = "'" . $value . "'";
                        break;
                    case FieldType::Date:
                        $str = "'" . $value . "'";
                        break;
                }

                if ($withoutWhereOperator == true) {
                    return $str;
                }

                switch ($whereOperator) {
                    case WhereOperator::Equal:
                        $strEnclose = " = " . $str;
                        break;
                    case WhereOperator::NotEqual:
                        $strEnclose = " <> " . $str;
                        break;
                    case WhereOperator::Greater:
                        $strEnclose = " > " . $str;
                        break;
                    case WhereOperator::Greater_OR_Equal:
                        $strEnclose = " >= " . $str;
                        break;
                    case WhereOperator::Less:
                        $strEnclose = " < " . $str;
                        break;
                    case WhereOperator::Less_OR_Equal:
                        $strEnclose = " <= " . $str;
                        break;
                }
            }

        }

        return $strEnclose;
    }

}

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