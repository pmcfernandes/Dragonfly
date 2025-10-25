<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Connects to microsoft sql server
 *
 * @param string $db
 * @param string $server
 * @param string $username
 * @param string $password
 * @return resource
 */
function mssql_connect($db = null, $server = null, $username = null, $password = null)
{
    // Ensure the SQLSRV extension is available before calling its functions
    if (!function_exists('sqlsrv_connect')) {
        die("SQL Server driver for PHP ('sqlsrv') is not available.\nPlease install/enable the Microsoft Drivers for PHP for SQL Server or configure the project to use a supported database driver.");
    }
    global $config;
    global $connection;

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

    if (!function_exists('sqlsrv_close')) {
        // nothing to do if driver is not available
        return;
    }

    if (isset($connection)) {
        sqlsrv_close($connection);
    }
}

/**
 * Escape sql string agains sql injection
 *
 * @param string $sql
 * @return void
 */
function mssql_prep($sql)
{
    global $connection;

    if (!function_exists('sqlsrv_prepare')) {
        die("SQLSRV functions not available. Can't prepare SQL queries.");
    }

    $escaped_string = sqlsrv_prepare($connection, $sql);
    return $escaped_string;
}

/**
 * confirm result is set
 *
 * @param mixed $result_set
 * @return void
 */
function mssql_confirm_query($stmt)
{
    if (!function_exists('sqlsrv_errors')) {
        die('SQLSRV functions not available.');
    }

    if (!$stmt) {
        die(print_r(sqlsrv_errors(), true));
    }
}

/**
 * Query database using sql
 *
 * @param string $sql
 * @return array
 */
function mssql_query($sql)
{
    global $connection;

    if (!function_exists('sqlsrv_query')) {
        die("SQLSRV functions not available. Can't execute queries.");
    }

    $stmt = sqlsrv_query($connection, $sql, array(), array("Scrollable" => 'static'));
    mssql_confirm_query($stmt);
    $data = array();

    if (sqlsrv_num_rows($stmt) > 0) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
    }

    sqlsrv_free_stmt($stmt);
    return $data;
}

/**
 * Create a record in database table
 *
 * @param string $sql
 * @return int
 */
function mssql_insert($sql)
{
    global $connection;

    if (!function_exists('sqlsrv_query')) {
        die("SQLSRV functions not available. Can't execute insert.");
    }

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
 * @return int
 */
function mssql_lastid()
{
    global $connection;

    if (!function_exists('sqlsrv_query')) {
        die("SQLSRV functions not available. Can't get last insert id.");
    }

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

    if (!function_exists('sqlsrv_query')) {
        die("SQLSRV functions not available. Can't count results.");
    }

    $result = sqlsrv_query($connection, $sql, array(), array("Scrollable" => 'static'));
    mssql_confirm_query($result);
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

    if (!function_exists('sqlsrv_begin_transaction')) {
        die("SQLSRV functions not available. Can't begin transaction.");
    }

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
    if (!function_exists('sqlsrv_commit')) {
        die("SQLSRV functions not available. Can't commit transaction.");
    }

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
    if (!function_exists('sqlsrv_rollback')) {
        die("SQLSRV functions not available. Can't rollback transaction.");
    }

    sqlsrv_rollback($connection);
}
