<?php
require("Group.php");


class ParseClass
{
	private $file;
	private $folder;
	private $fullPath;
	private $groups;
	
	public function __construct($folder, $file)
	{
		$this->file = $file;
		$this->folder = $folder;
		$this->fullPath = __DIR__.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$file;
		
		$this->groups = array();
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
				$this->parseGroups($explode[1]);/////////////////////////
			else if($command == "d")
			{
				$this->parseDayCommand($explode[1], $explode[2]);
			}
		}
		
		foreach($this->groups as $currentGroup)
		{
			echo "Группа: ".$currentGroup->getName()."<br>\n";
			foreach($currentGroup->getDays() as $day)
			{
				echo "&nbsp;&nbsp;&nbsp;&nbsp;День: ".$day->getNumber()."<br>\n";
				
				if(is_array($day->getLessons()))
				{
					foreach($day->getLessons() as $lesson)
					{
						if(is_array($lesson))
						{
							foreach($lesson as $sublesson)
							{
								$sublesson->show();
							}
						}
						else
							$lesson->show();
					}
				}
				
			}
		}
		
	}
	
	private function parseGroups($buffer)
	{
		$groupNames = explode(",", $buffer);
		foreach ($groupNames as $group)
		{
			echo "Я запихнул группу ".trim($group)."<br>\n";
			array_push($this->groups, new Group($group));
		}
		echo "<br>";
	}
	
	private function parseDayCommand($dayAndLessonNumbers, $lessonData)
	{
	//	var_dump($dayAndLessonNumbers);
	//	echo "<br>\n";
	//	var_dump($lessonData);
	//	echo "<br>\n";
		///////////////////////
		
		
		
		$lessonData = trim($lessonData);
		if(empty($lessonData))
		{
			//echo "<font color='#CC0000'><b>Нет пар!</b></font>";
			//echo "<br>\n";
			return;
		}
		
		$dayNumber = (int)$this->getDayNumber($dayAndLessonNumbers);
		$lessonNumber = (int)$this->getLessonNumber($dayAndLessonNumbers);
		
		$splittedLessonsByGroups = $this->splitByGroups($lessonData);
		
		foreach($splittedLessonsByGroups as $groupsLesson)
		{
			$this->parseByGroupLesson($groupsLesson, $dayNumber, $lessonNumber);
			
		}
		//$groupList = $this->getGroupsList($lessonData);
		
		////////////////////////
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
			//echo "группа ".$group."<br>\n";
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
		
		//var_dump($group);
		//echo "<br>\n";
		//var_dump($subgroups);
		//echo"<br>\n";
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
			}
			
			//echo $subject."; ".$teacher."<br>\n";
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
}

?>