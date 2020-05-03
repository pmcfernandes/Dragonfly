<?php

class Upload
{

    /**
     * Get uploaded files in array
     *
     * @return array
     */
    public static function getUploadFiles()
    {
        $files = array();

        foreach ($_FILES as $file) {
            if ($file["error"] > 0) {
                continue;
            }

            array_push($files, new Upload($file));
        }

        return $files;
    }

    private $filename;
    private $type;
    private $size;
    private $tmp_name;

    /**
     * Constructor of Upload
     *
     * @param $file
     */
    private function __construct($file)
    {
        $this->tmp_name = $file["tmp_name"];
        $this->filename = $file["name"];
        $this->type = $file["type"];
        $this->size = $file["size"];
    }

    /**
     * Get name of uploaded file
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->filename;
    }

    /**
     * Get size of file
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get type of file
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Save file in a new location
     *
     * @param $destination
     */
    public function saveAs($destination)
    {
        if (file_exists($this->tmp_name)) {
            move_uploaded_file($this->tmp_name, $destination);
        }
    }
}
