<?php
namespace Impedro\Dragonfly\IO;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Class to implement File System
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version $Revision$
 * @author Pedro Fernandes
 */
class File
{

    /**
     * Forces a file to be downloaded
     *
     * @param $file
     */
    public static function download($file)
    {
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }

    /**
     * Get extension from a string
     *
     * @param string $file Full Path of File including name and extension
     * @return string
     */
    public static function getExtension($file)
    {
        $parts = explode(".", $file);
        return end($parts);
    }

    /**
     * Gets readable size for the specified size value
     *
     * @param $size
     * @return string
     */
    public static function size($size)
    {
        $sizes = array("B", "KB", "MB", "GB");
        $length = $size;
        $i = 0;

        while ($length >= 1024 && ($i + 1) < count($sizes)) {
            $i++;
            $length /= 1024;
        }

        return number_format($length, 2, ",", ".") . ' ' . $sizes[$i];
    }

    /**
     * Read file content
     *
     * @param string $file Full Path of File including name and extension
     * @return string
     */
    public static function read($file)
    {
        $handle = fopen($file, "r");
        $contents = fread($handle, filesize($file));
        fclose($handle);

        return $contents;
    }

    /**
     * Write content to file
     *
     * @param $file Full Path of File including name and extension
     * @param $data
     * @return bool
     */
    public static function write($file, $data)
    {
        $handle = fopen($file, "w");

        if ($handle) {
            fwrite($handle, $data);
            fclose($handle);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all files and directories in specified path
     *
     * @param $path Full Path of Directory name in Disk
     * @return array
     */
    public static function getDir($path)
    {
        $files = array();

        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        foreach ($objects as $name => $object) {
            $files[] = $name;
        }

        return $files;
    }

    /**
     * Check if parameter is a directory
     *
     * @param $fileOrDirectory Full Path of File including name and extension or Directory name only
     * @return bool
     */
    public static function isDir($fileOrDirectory)
    {
        return is_dir($fileOrDirectory);
    }

    /**
     * Check if file exists
     *
     * @param $file Full Path of File including name and extension
     * @return bool
     */
    public static function exists($file)
    {
        return file_exists($file);
    }

    /**
     * Gives information about a file
     *
     * @param $file Full Path of File including name and extension
     * @return array
     */
    public static function info($file)
    {
        return stat($file);
    }

    /**
     * Create a directory
     *
     * @param $path
     */
    public static function createDirectory($path)
    {
        mkdir($path);
    }

    /**
     * Delete a file or directory
     *
     * @param $fileOrDirectory  Full Path of File including name and extension or Directory name only
     */
    public static function delete($fileOrDirectory)
    {
        if (File::isDir($fileOrDirectory) == true) {
            rmdir($fileOrDirectory);
        } else {
            if (File::exists($fileOrDirectory)) {
                unlink($fileOrDirectory);
            }
        }
    }
}
