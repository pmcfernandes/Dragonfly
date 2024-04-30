<?php
namespace Impedro\Dragonfly\DB;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\DB\Database;

/**
 * Class to implement Model for database
 *
 * @link http://www.impedro.com
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
        $this->connection = Database::getInstance();
    }

    /**
     * Execute query with non return value
     *
     * @param mixed $qry
     * @return int
     */
    public function execute($qry)
    {
        return $this->connection->exec($qry);
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
