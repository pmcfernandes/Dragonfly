<?php

/**
 * Connects to mysql server
 *
 * @param string $db
 * @param string $server
 * @param string $username
 * @param string $password
 * @return void
 */
function mysql_connect($db, $server, $username, $password)
{
    global $config;

    if (!isset($db)) {
        $db = $config['db_name'];
    }

    if (!isset($server)) {
        $server = $config['db_host'];
    }

    if (!isset($username)) {
        $username = $config['db_user'];
    }

    if (!isset($password)) {
        $password = $config['db_password'];
    }

    global $connection;
    $connection = mysqli_connect($server, $username, $password, $db);

    if (mysqli_connect_errno()) {
        die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
    } else {
        mysqli_autocommit($connection, TRUE);
        mysqli_set_charset($connection, "utf8");
        return $connection;
    }
}

/**
 * Close connection in mysql server
 *
 * @return void
 */
function mysql_close()
{
    global $connection;

    if (isset($connection)) {
        mysqli_close($connection);
    }
}

/**
 * Escape sql string agains sql injection
 *
 * @param string $string
 * @return void
 */
function mysql_prep($string)
{
    global $connection;

    $escaped_string = mysqli_real_escape_string($connection, $string);
    return $escaped_string;
}

/**
 * confirm result is set
 *
 * @param mixed $result_set
 * @return void
 */
function confirm_query($result_set)
{
    if (!$result_set) {
        die("Database query failed.");
    }
}

/**
 * Query database using sql
 *
 * @param string $sql
 * @return void
 */
function mysql_query($sql)
{
    global $connection;

    $result = mysqli_query($connection, $sql);
    confirm_query($result);
    $data = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_free_result($result);
    return $data;
}

/**
 * Create a record in database table
 *
 * @param string $sql
 * @return void
 */
function mysql_insert($sql)
{
    global $connection;

    if (mysqli_query($connection, $sql)) {
        return mysqli_affected_rows($connection);
    } else {
        return 0;
    }
}

/**
 * Update record in database table
 *
 * @param string $sql
 * @return void
 */
function mysql_update($sql)
{
    return mysql_insert($sql);
}

/**
 * Delete record from database table
 *
 * @param string $sql
 * @return void
 */
function mysql_delete($sql)
{
    return mysql_insert($sql);
}

/**
 * Get last id inserted in connection
 *
 * @return void
 */
function mysql_lastid()
{
    global $connection;
    $last_id = mysqli_insert_id($connection);
    return $last_id;
}

/**
 * Count number of records returned by sql
 *
 * @param string $sql
 * @return void
 */
function mysql_count($sql)
{
    global $connection;

    $result = mysqli_query($connection, $sql);
    confirm_query($result);
    $count = mysqli_num_rows($result);
    return $count;
}

/**
 * Begin a transaction in database
 *
 * @return void
 */
function mysql_begin_transaction()
{
    global $connection;
    mysqli_autocommit($connection, FALSE);
    mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_ONLY);
}

/**
 * Commit a transaction
 *
 * @return void
 */
function mysql_commit()
{
    global $connection;
    mysqli_commit($connection);
    mysqli_autocommit($connection, TRUE);
}

/**
 * Rollback transaction
 *
 * @return void
 */
function mysql_rollback()
{
    global $connection;
    mysqli_rollback($connection);
    mysqli_autocommit($connection, TRUE);
}
