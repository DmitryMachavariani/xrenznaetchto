<?php

require_once("Parser.php");

class ScheduleParserController
{
	private $allGroups;
	private $undefinedDictionaryWords;
	
	private static $instance;
	
	public static function getInstance()
	{
		if(empty($instance))
			$instance = new ScheduleParserController();
		return $instance;
	}
	
	private function __construct()
	{
		$undefinedDictionaryWords = array();
		
		$directoryWithFilesForParse = "../models/file";
		$filesArray = $this->readDirectory($directoryWithFilesForParse);
		$this->allGroups = array();
		
		foreach($filesArray as $currentFile)
		{
			if(!is_array($currentFile))
			{
				$parser = new Parser($directoryWithFilesForParse, $currentFile);
				if($parser->loadFile())
				{
					$parser->parse();
					array_push($this->allGroups, $parser->getAllGroups());
				}
			}
		}
		
		$dictionaryTest = new Dictionary();
		$testResult = $dictionaryTest->getWord($this->allGroups[0][0]->getDay(1)->getLesson(4)->getTeacher());
		if(empty($testResult))
		{
			echo "Пусто<br>\n";
		}
	}
	
	
	private function readDirectory($dir)
	{
		$result[] = array();
		if ($handle = opendir($dir))
		{
			while ($file = readdir($handle))
				if ($file != "." && $file != "..")
					array_push($result, $file);
				
			closedir($handle);
		}
		
		return $result;
	}
	
	public function getAllGroups()
	{
		return $this->allGroups;
	}
}
?>
