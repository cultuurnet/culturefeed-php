<?php
/**
 * @file
 */ 

class CultureFeed_FileUpload
{
    protected $fileName;
    protected $mimeType;
    protected $postName;

    public function __construct($fileName, $mimeType = NULL, $postName = NULL) {
        $this->fileName = $fileName;
        if ($mimeType) {
            $this->mimeType = $mimeType;
        }
        if ($postName) {
            $this->postName = $postName;
        }
        else {
            $this->postName = basename($fileName);
        }
    }

    function __toString()
    {
        $value = "@{$this->fileName};filename=" . $this->postName;
        if ($this->mimeType) {
            $value .= ';type=' . $this->mimeType;
        }

        return $value;
    }
}
