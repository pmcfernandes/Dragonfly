<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Class to implement easy GD library
 *
 * @link http://www.impedro.com
 * @since 1.0
 * @version 1.0.1
 * @author Pedro Fernandes
 */
final class GD
{
    private $im = null;
    private $filename;


    /**
     * Get size of resized image with correct aspect ratio
     *
     * @param $currentWidth
     * @param $currentHeight
     * @param $maximunSize
     * @return array
     */
    public static function aspectRatio($currentWidth, $currentHeight, $maximunSize)
    {
        $tempMultiplier = 0;

        if ($currentHeight > $currentWidth) { // Portrait
            $tempMultiplier = $maximunSize / $currentHeight;
            if (($currentWidth * $tempMultiplier) > $currentWidth) {
                $tempMultiplier /= 2;
            }
        }

        if ($currentWidth > $currentHeight) { // Landscape
            $tempMultiplier = $maximunSize / $currentWidth;
            if (($currentHeight * $tempMultiplier) > $currentHeight) {
                $tempMultiplier /= 2;
            }
        }

        return array(($currentWidth * $tempMultiplier), ($currentHeight * $tempMultiplier));
    }

    /**
     * Constructor of GD
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->im = $this->loadFile($filename);
    }

    /**
     * Load image from file
     *
     * @param $filename
     * @return null|resource
     */
    private function loadFile($filename)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            die("File '$filename' not found or not exists.");
        }

        $info = getimagesize($filename);
        $type = image_type_to_extension($info[2], false);

        if ($type == 'jpeg' && (imagetypes() & IMG_JPG)) {
            return imagecreatefromjpeg($filename);
        } elseif ($type == 'png' && (imagetypes() & IMG_PNG)) {
            return imagecreatefrompng($filename);
        } elseif ($type == 'gif' && (imagetypes() & IMG_GIF)) {
            return imagecreatefromgif($filename);
        } else {
            return NULL;
        }
    }

    /**
     * Save file to disk
     *
     * @param mixed $filename
     * @param mixed $quality
     * @return bool
     */
    public function saveAs($filename, $quality = 75)
    {
        $type = Str::right($filename, 4);

        switch ($type) {
            case ".jpg":
                return imagejpeg($this->im, $filename, $quality);
            case ".png":
                return imagepng($this->im, $filename);
            case ".gif":
                return imagegif($this->im, $filename);
            default:
                return false;
        }
    }

    /**
     * Save output to OutStream
     *
     * @param string $type
     * @param mixed $quality
     * @return bool
     */
    public function output($type, $quality = 75)
    {
        if (Str::isNullOrEmpty($type)) {
            $type = Str::right($this->filename, 4);
        }

        switch ($type) {
            case ".jpg":
                header("Content-Type: image/jpeg");
                imagejpeg($this->im, null, $quality);
                return true;
            case ".png":
                header("Content-Type: image/png");
                imagepng($this->im);
                return true;
            case ".gif":
                header("Content-Type: image/gif");
                imagegif($this->im);
                return true;
            default:
                return false;
        }
    }

    /**
     * Resize picture from original image
     *
     * @param $new_width
     * @param $new_height
     * @return bool
     */
    public function resize($new_width, $new_height)
    {
        $dest = imagecreatetruecolor($new_width, $new_height);

        // Transparency fix contributed by Google Code user 'desfrenes'
        imagealphablending($dest, false);
        imagesavealpha($dest, true);

        $info = getimagesize($this->filename);
        $width = $info[0];
        $height = $info[1];

        if (imagecopyresampled($dest, $this->im, 0, 0, 0, 0, $new_width, $new_height, $width, $height)) {
            $this->im = $dest;
            return true;
        }

        return false;
    }

    /**
     * Crop image
     *
     * @param $x
     * @param $y
     * @param $w
     * @param $h
     * @return bool
     */
    public function crop($x, $y, $w, $h)
    {
        $dest = imagecreatetruecolor($w, $h);

        if (imagecopyresampled($dest, $this->im, 0, 0, $x, $y, $w, $h, $w, $h)) {
            $this->im = $dest;
            return true;
        }

        return false;
    }

    /**
     * Crop image from center
     *
     * @param $w
     * @param $h
     * @return bool
     */
    public function cropCentered($w, $h)
    {
        $info = getimagesize($this->filename);
        $width = $info[0];
        $height = $info[1];

        $cx = $width / 2;
        $cy = $height / 2;
        $x = $cx - $w / 2;
        $y = $cy - $h / 2;
        if ($x < 0)
            $x = 0;
        if ($y < 0)
            $y = 0;
        return $this->crop($x, $y, $w, $h);
    }
}
