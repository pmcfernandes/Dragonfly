<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Get contents from remote location
 *
 * @param string $url
 * @return string
 */
function rest_get_data($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * Insert data to remote location
 *
 * @param string $url
 * @param array $data
 * @return string
 */
function rest_post_data($url, $data)
{
    if (is_array($data)) {
        $data = json_encode($data);
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * Update data in remote location
 *
 * @param string $url
 * @param array $data
 * @return string
 */
function rest_put_data($url, $data)
{
    if (is_array($data)) {
        $data = json_encode($data);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * Delete data in remote location
 *
 * @param string $url
 * @param array $data
 * @return string
 */
function rest_delete_data($url, $data)
{
    if (is_array($data)) {
        $data = json_encode($data);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
