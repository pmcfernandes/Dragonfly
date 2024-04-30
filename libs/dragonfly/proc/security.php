<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\JWT;

function secure_api_method() {
    if (!logged_in()) {
        die('User is not authenticated.');
    } else {
        $authorization = get_bearer_auth_token();
        $jwt = JWT::decode($authorization);

        if ($jwt === array()) {
            die('Bearer JWT token is not valid.');
        } else {
            if ($jwt->username === $_SESSION['login_name']) {
                return true;
            } else {
                die('Bearer JWT token is not valid for this login.');
            }
        }
    }
}

/**
 * Check if user is logged
 *
 * @return bool
 */
function logged_in()
{
    return isset($_SESSION['login_id']);
}

/**
 * Redirect user to login page if not logged
 *
 * @return void
 */
function confirm_logged_in()
{
    if (!logged_in()) {
        redirect_to("login.php");
    }
}

/**
 * Redirect to page url
 *
 * @param string $new_location
 * @return void
 */
function redirect_to($new_location)
{
    header("Location: " . $new_location);
    exit;
}

/**
 * Attempt to login user in database
 *
 * @param string $username
 * @param string $password
 * @return bool
 */
function attempt_login($username, $password)
{
    $user = find_user_by_username($username);
    if ($user) {
        if (password_check($password, $user["Password"])) {
            $_SESSION['login_id'] = $user['IDUser'];
            $_SESSION['login_name'] = $user['Username'];
            return $user;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Find user by username in user tables
 *
 * @param string $username
 * @return string | bool
 */
function find_user_by_username($username)
{
    global $connection;
    $safe_username = mysqli_real_escape_string($connection, $username);

    $query  = "SELECT * ";
    $query .= "FROM MetaUser ";
    $query .= "WHERE Username = '{$safe_username}' AND IsGroup = 0 AND M_IsDeleted = 0 ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);

    if ($user = mysqli_fetch_assoc($user_set)) {
        mysqli_free_result($user_set);
        return $user;
    } else {
        return false;
    }
}

/**
 * Find user by id in user tables
 *
 * @param int $user_id
 * @return string | bool
 */
function find_user_by_id($user_id)
{
    global $connection;
    $safe_user_id = mysqli_real_escape_string($connection, $user_id);

    $query  = "SELECT * ";
    $query .= "FROM MetaUser ";
    $query .= "WHERE IDUser = {$safe_user_id} ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($connection, $query);

    confirm_query($user_set);
    if ($user = mysqli_fetch_assoc($user_set)) {
        mysqli_free_result($user_set);
        return $user;
    } else {
        return false;
    }
}


function user_is_member_of($user_id, $group) {
    global $connection;

    $sql = "SELECT COUNT(mgu.IDGroup) AS total " .
           "FROM MetaGroupUsers mgu " .
           "INNER JOIN MetaUser mg ON mgu.IDGroup = mg.IDUser " .
           "WHERE mgu.IDUser = {$user_id} AND mg.Username LIKE '{$group}'";

    $result = mysqli_query($connection, $sql);
    confirm_query($result);

    if (mysqli_num_rows($result) == 0) {
        mysqli_free_result($result);
        return false;
    } else {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row[0]['total'] == 0) {
            mysqli_free_result($result);
            return false;
        } else {
            mysqli_free_result($result);
            return true;
        }
    }
}
