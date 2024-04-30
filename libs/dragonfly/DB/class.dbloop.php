<?php
namespace Impedro\Dragonfly\DB;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use \Countable;
use \Iterator;
use Impedro\Dragonfly\DB\PDODb;

/**
 * Class to implement DB Loop operations
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version 1.0
 * @author Pedro Fernandes
 */
final class DBLoop implements Iterator, Countable
{
    private $result;
    private $position = -1;

    /**
     * Constructor of DBLoop
     *
     * @param mixed $result_or_sql
     * @return DBLoop
     */
    public function __construct($result_or_sql)
    {
        if (!is_string($result_or_sql)) {
            $query = $result_or_sql;
        } else {
            $query = PDODb::getInstance()->query($result_or_sql);
        }

        $this->result = $query;
        $this->rewind();
    }

    /**
     * Reset index to Zero position
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Get current row array
     *
     * @link http://stackoverflow.com/questions/8510917/how-would-i-read-this-array-stdclass-object
     */
    public function current(): void
    {
        return $this->result[$this->position];
    }

    /**
     * Get or set position of array
     *
     * @param int | null $position
     * @return int
     */
    public function key($position = null): int
    {
        if (isset($position)) {
            $this->position = $position;
        }

        return $this->position;
    }

    /**
     * Increment position of array
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Get size of length
     *
     * @return int
     */
    public function count()
    {
        return count($this->result);
    }

    /**
     * Check current position is valid
     *
     * @return bool
     */
    public function valid() : bool
    {
        if ($this->position <= ($this->count() - 1)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Fields
     *
     * @return array
     */
    public function listColumns()
    {
        $ar = array();

        foreach (get_object_vars($this->current()) as $key => $val) {
            $ar[$key] = $key;
        }

        return $ar;
    }
}
