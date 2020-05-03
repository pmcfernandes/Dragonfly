<?php

/**
 * Encrypt password
 *
 * @param string $password
 * @return void
 */
function password_encrypt($password)
{
    $hash_format = "$2y$10$";
    $salt_length = 22;
    $salt = generate_salt($salt_length);
    $format_and_salt = $hash_format . $salt;
    $hash = crypt($password, $format_and_salt);
    return $hash;
}

/**
 * Generate a salt for password
 *
 * @param int $length
 * @return void
 */
function generate_salt($length)
{
    $unique_random_string = md5(uniqid(mt_rand(), true));
    $base64_string = base64_encode($unique_random_string);
    $modified_base64_string = str_replace('+', '.', $base64_string);
    $salt = substr($modified_base64_string, 0, $length);
    return $salt;
}

/**
 * Check if password is equal
 *
 * @param string $password
 * @param string $existing_hash
 * @return void
 */
function password_check($password, $existing_hash)
{
    $hash = crypt($password, $existing_hash);
    if ($hash === $existing_hash) {
        return true;
    } else {
        return false;
    }
}
