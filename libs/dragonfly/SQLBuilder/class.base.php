<?php
namespace Impedro\Dragonfly\SQLBuilder;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\Utils\Str;
use Impedro\Dragonfly\SQLBuilder\LogicalOperator;
use Impedro\Dragonfly\SQLBuilder\WhereOperator;
use Impedro\Dragonfly\SQLBuilder\FieldType;
use Impedro\Dragonfly\SQLBuilder\OrderByDirection;

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
     * @param mixed $mysql
     * @param mixed $sql
     * @return string
     */
    public static function Sanitize($mysql, $sql)
    {
        return mysqli_real_escape_string($mysql, $sql);
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
                $str .= $this->Enclose(
                    trim($field[0]),
                    $field[1],
                    $field[2],
                    WhereOperator::Equal,
                    LogicalOperator::AND_,
                    true
                ) . ', ';
            }

            return Str::ensureNotEndsWith(trim($str), ',');
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
                $str .= trim($field[0]) . ' = ' . $this->Enclose(
                    trim($field[0]),
                    $field[1],
                    $field[2],
                    WhereOperator::Equal,
                    LogicalOperator::AND_,
                    true
                ) . ', ';
            }

            return Str::ensureNotEndsWith(trim($str), ',');
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

            return Str::ensureNotEndsWith(trim($str), ',');
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

            return ' ORDER BY ' . Str::ensureNotEndsWith(trim($str), ',');
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
