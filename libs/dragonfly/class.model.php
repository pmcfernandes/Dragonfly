<?php

/**
 * Class to implement Model for database
 *
 * @link http://www.pfernandes.pt
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class Model
{
    private $connection;

    /**
     * Constructor of Model
     */
    public function __construct()
    {
        $this->connection = PDODb::getInstance();
    }

    /**
     * Execute query with non return value
     *
     * @param mixed $qry
     * @return int
     */
    public function execute($qry)
    {
        return $this->connection->execute($qry);
    }

    /**
     * Query function
     *
     * @param $qry
     * @return mixed
     */
    public function query($qry)
    {
        return $this->connection->query($qry);
    }
}
