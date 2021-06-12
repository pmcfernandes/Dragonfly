<?php

/**
 * Connects to microsoft sql server
 *
 * @param string $db
 * @param string $server
 * @param string $username
 * @param string $password
 * @return void
 */
function mssql_connect($db, $server, $username, $password)
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
    $connection = sqlsrv_connect($server, array( 
        "UID"       => $username,  
        "PWD"       => $password,  
        "Database"  => $db
    ));

    if ($connection == false) {  
        die("Database connection failed: " . print_r(sqlsrv_errors(), true));
    } else {
        return $connection;
    }
}

/**
 * Close connection in sql server
 *
 * @return void
 */
function mssql_close()
{
    global $connection;

    if (isset($connection)) {
        sqlsrv_close($connection);
    }
}

/**
 * Escape sql string agains sql injection
 *
 * @param string $string
 * @return void
 */
function mssql_prep($string)
{
    global $connection;

    $escaped_string = sqlsrv_prepare($connection, $string);
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
function mssql_query($sql)
{
    global $connection;

    $result = sqlsrv_query($connection, $sql);
    confirm_query($result);
    $data = array();

    if (sqlsrv_num_rows($result) > 0) {
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
    }

    sqlsrv_free_stmt($result);
    return $data;
}

/**
 * Create a record in database table
 *
 * @param string $sql
 * @return void
 */
function mssql_insert($sql)
{
    global $connection;

    if (sqlsrv_query($connection, $sql)) {
        return sqlsrv_rows_affected($connection);
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
function mssql_update($sql)
{
    return mssql_insert($sql);
}

/**
 * Delete record from database table
 *
 * @param string $sql
 * @return void
 */
function mssql_delete($sql)
{
    return mssql_insert($sql);
}

/**
 * Get last id inserted in connection
 *
 * @return void
 */
function mssql_lastid()
{
    global $connection;

    $stmt = sqlsrv_query($connection, "SELECT SCOPE_IDENTITY() as id");

    if ($stmt) {
        $row  = sqlsrv_fetch_array($stmt);
        return $row["id"];
    }

    return 0;
}

/**
 * Count number of records returned by sql
 *
 * @param string $sql
 * @return void
 */
function mssql_count($sql)
{
    global $connection;

    $result = sqlsrv_query($connection, $sql);
    confirm_query($result);
    $count = sqlsrv_num_rows($result);
    return $count;
}

/**
 * Begin a transaction in database
 *
 * @return void
 */
function mssql_begin_transaction()
{
    global $connection;
    
    if (sqlsrv_begin_transaction($connection) == false) {
        die(print_r(sqlsrv_errors(), true));  
    } 
}

/**
 * Commit a transaction
 *
 * @return void
 */
function mssql_commit()
{
    global $connection;
    sqlsrv_commit($connection);
}

/**
 * Rollback transaction
 *
 * @return void
 */
function mssql_rollback()
{
    global $connection;
    sqlsrv_rollback($connection);
}
