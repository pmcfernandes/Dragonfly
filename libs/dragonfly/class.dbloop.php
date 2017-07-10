<?php

/**
 * Class to implement DB Loop operations
 *
 * @link http://www.pfernandes.pt
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
    public function __construct($result_or_sql) {
        if (!is_string($result_or_sql)) {
            $query = $result_or_sql;
        } else {
            $query = Database::getInstance()->query($result_or_sql);
        }

        $this->result = $query;
        $this->rewind();
    }

    /**
     * Reset index to Zero position
     *
     * @return void
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * Get current row array
     *
     * @link http://stackoverflow.com/questions/8510917/how-would-i-read-this-array-stdclass-object
     */
    public function current() {
        return $this->result[$this->position];
    }

    /**
     * Get or set position of array
     *
     * @param null $position
     * @return int|mixed
     */
    public function key($position = null) {
        if (isset($position)) {
            $this->position = $position;
        }

        return $this->position;
    }

    /**
     * Increment position of array
     */
    public function next() {
        $this->position++;
    }

    /**
     * Get size of length
     *
     * @return int
     */
    public function count() {
        return count($this->result);
    }

    /**
     * Check current position is valid
     *
     * @return bool
     */
    public function valid() {
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
    public function listColumns() {
        $ar = array();

        foreach (get_object_vars($this->current()) as $key => $val) {
            $ar[$key] = $key;
        }

        return $ar;
    }

}
