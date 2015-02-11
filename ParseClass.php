<?php
require("Group.php");


class ParseClass
{
	private $file;
	private $folder;
	private $fullPath;
	
	public function __construct($folder, $file)
	{
		$this->file = $file;
		$this->folder = $folder;
		
		$this->fullPath = __DIR__.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$file;
	}
	
	//Проверяем папку
	protected function checkFolder(){
		if($this->folder != '' && is_dir($this->folder))
			return true;
		else
			return false;
	}
	
	//Проверяем файл
	protected function checkFile(){
		if(file_exists($this->fullPath) && is_file($this->fullPath))
			return true;
		else
			return false;
	}
	
	//Загружаем файл
	public function loadFile(){
		if($this->checkFolder() && $this->checkFile()){
			$this->file = fopen($this->fullPath, "r");
			return $this->file;
		}
	}
	private function getData()
	{
		//while has next line
		while(!feof($this->file))
		{
			$buffer = fgets($this->file);
			$explode = explode(":", $buffer);
	
			//is line contains colon and something else
			if(count($explode) == 0)
				continue;
	
			$command = $explode[0];
	
			if($command == "g")
				$this->getGroupNumbers($explode[1]);
			else if($command == "d")
			{
				$this->parseDay($explode);
			}
		}
	}
}

?>