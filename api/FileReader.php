<?php

namespace Api;

use Exception;

class FileReader
{
    private $fileName = null;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function json()
    {
        if (!file_exists($this->fileName)) {
            throw new Exception("File Not Found");
        }        
        $data =  json_decode(json_encode(file_get_contents($this->fileName)), true);
        if (!$data) {
            throw new Exception('Error reading file');
        }
        return json_decode($data, true);
    }
	
	public function xml()
	{

	}
	
}
