<?php
require("../models/Group.php");
require_once("../models/Dictionary.php");
include_once("../views/site/index.php"); // подключаем view

class Parser
{
	private $file;
	private $folder;
	private $groups;
	private $dictionary;
	
	public function __construct($folder, $file)
	{
		$this->file = $file;
		$this->folder = $folder;
		
		$this->groups = array();
		$this->dictionary = new Dictionary();
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
		if(file_exists($this->folder."/".$this->file) && is_file($this->folder."/".$this->file))
			return true;
		else
			return false;
	}
	
	//Загружаем файл
	public function loadFile(){
		if($this->checkFolder() && $this->checkFile()){
			$this->file = fopen($this->folder."/".$this->file, "r");
			return true;
		}
		return false;
	}
	
	public function parse()
	{
		//while has next line
		while(!feof($this->file))
		{
			$buffer = fgets($this->file);
			$explode = explode(":", $buffer);
	
			//is line have not ":" and something else
			if(count($explode) == 0)
				continue;
	
			$command = $explode[0];
	
			if($command == "g")
				$this->parseGroups($explode[1]);
			else if($command == "d")
			{
				$this->parseDayCommand($explode[1], $explode[2]);
			}
		}
	}
	
	private function parseGroups($buffer)
	{
		$groupNames = explode(",", $buffer);
		foreach ($groupNames as $group)
		{
			array_push($this->groups, new Group($group));
		}
		echo "<br>";
	}
	
	private function parseDayCommand($dayAndLessonNumbers, $lessonData)
	{
		$dayNumber = (int)$this->getDayNumber($dayAndLessonNumbers);
		$lessonNumber = (int)$this->getLessonNumber($dayAndLessonNumbers);
		
		$lessonData = trim($lessonData);
		
		if(empty($lessonData))
		{
			foreach($this->groups as $group)
			{
				$group->getDay($dayNumber)->addLesson($lessonNumber, new Lesson());
			}
			
			return;
		}
		
		$splittedLessonsByGroups = $this->splitByGroups($lessonData);
		
		foreach($splittedLessonsByGroups as $groupsLesson)
		{
			$this->parseByGroupLesson($groupsLesson, $dayNumber, $lessonNumber);
		}
		
		foreach($this->groups as $group)
		{
			$group->getDay($dayNumber)->getOrAddLesson($lessonNumber);
		}
	}
	
	private function getDayNumber($dayAndLessonNumbers)
	{
		$dayNumber = explode("-", $dayAndLessonNumbers);
		
		if(empty($dayNumber[0]))
			throw new Exception("<font color='#CC0000'><b>Внезапно нет номера пары для одного из дней</b></font>");
		
		return $dayNumber[0];
	}
	
	private function getLessonNumber($dayAndLessonNumbers)
	{
		$lessonNumber = explode("-", $dayAndLessonNumbers);
		
		if(empty($lessonNumber[1]))
			throw new Exception("<font color='#CC0000'><b>Внезапно нет номера пары для одного из дней</b></font>");
		
		return $lessonNumber[1];
	}
	
	private function splitByGroups($lessonData)
	{
		$oneLesson = explode("&", $lessonData);
		return $oneLesson;
	}
	
	private function parseByGroupLesson($groupLesson, $dayNumber, $lessonNumber)
	{
		$groups = $this->getGroupsList($groupLesson);
		$groupLesson = explode(">>", $groupLesson)[1];
		
		foreach($groups as $group)
		{
			$this->splitBySubgroup($groupLesson, $group, $dayNumber, $lessonNumber);
		}
	}
	
	private function getGroupsList($lessonData)
	{
		$splittedLessonData = explode(">>", $lessonData);
		$rawGroups = explode(",", $splittedLessonData[0]);
		$groups = array();
		foreach($rawGroups as $group)
		{
			array_push($groups, $group);
		}
		
		return $groups;
	}
	
	private function splitBySubgroup($groupLesson, $group, $dayNumber, $lessonNumber)
	{
		$subgroups = explode("#", $groupLesson);
		
		$lessonsWithSubgroups = array();
		
		foreach($subgroups as $subgroup)
		{
			$kindOfWeekRaw = explode("/", $subgroup);
			$kindOfWeek = $kindOfWeekRaw[0];
			
			$onEvenWeek = strpos($kindOfWeek, "ч") !== false;
			$onOddWeek = strpos($kindOfWeek, "н") !== false;
			
			$subject = "-";
			$teacher = "-";
			$room = "-";
			
			if(!empty($kindOfWeekRaw[1]))
			{
				$teacherRoomSubjectRaw = $kindOfWeekRaw[1];
				$teacherRoomSubject = explode("|", $teacherRoomSubjectRaw);
				$subject = $teacherRoomSubject[0];
				$teacher = $teacherRoomSubject[1];
				$room = $teacherRoomSubject[2];
				
				$this->dictionary->insertLesson($subject, $teacher, $room, $onOddWeek, $onEvenWeek);
				/*
				$subjectWord = $this->dictionary->getWord($subject);
				
				if(empty($subjectWord))
				{
					//овер дофига запросов, но мы не гонимся за производительность, а за безопасность транзакций
					answerWordData($subject, $this->dictionary->getMastersForSubjects());
				}
				*/
			}
			
			array_push($lessonsWithSubgroups, new Lesson($subject, $teacher, $room, $onEvenWeek, $onOddWeek));
		}
		
		if(count($lessonsWithSubgroups) > 1)
		{
			$this->groups[(int)$group]->getDay($dayNumber)->addLesson($lessonNumber, new LessonsForSubgroups($lessonsWithSubgroups));
		}
		else if(count($lessonsWithSubgroups) == 1)
		{
			$this->groups[(int)$group]->
			getDay($dayNumber)->
			addLesson($lessonNumber, $lessonsWithSubgroups[0]);
		}
	}

	public function getAllGroups()
	{
		return $this->groups;
	}
}

?>