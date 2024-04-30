<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Read file contents
 *
 * @param string $filename
 * @return string
 */
function read_file($filename)
{
    $handle = fopen($filename, "r") or die("Unable to open file!");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    return $contents;
}

/**
 * write file contents
 *
 * @param string $filename
 * @param string $contents
 * @return void
 */
function write_file($filename, $contents)
{
    $myfile = fopen($filename, "w") or die("Unable to open file!");
    fwrite($myfile, $contents);
    fclose($myfile);
}

/**
 * Get number of files in a directory~
 *
 * @param [type] $dirpath
 * @return int
 */
function get_dir_file_count($dirpath)
{
    $filecount = 0;
    $files = glob($dirpath . "*");

    if ($files) $filecount = count($files);
    return $filecount;
}
