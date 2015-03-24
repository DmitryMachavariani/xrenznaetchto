<?php
require("Group.php");

class ParseClass
{
	private $file;
	private $folder;
	private $fullPath;
	private $groups;
	private $campusId;
	private $course;
	private $instituteId;
	
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
				$this->parseGroups($explode[1]);
			else if($command == "d")
			{
				$this->parseDayCommand($explode[1], $explode[2]);
			}
			else if($command == "h")
			{
				$headerData = explode(",", $explode[1]);
				
				$this->instituteId = $headerData[0];
				$this->campusId = $headerData[1];
				$this->course = $headerData[2];
			}
		}
		
		
		foreach($this->groups as $currentGroup)
		{
			foreach($currentGroup->getDays() as $day)
			{
				if(is_array($day->getLessons()))
				{
					$lessonCounter = 1;
					foreach($day->getLessons() as $lesson)
					{
						if(is_array($lesson) !== false)//если пары для подгрупп или в разные (чётные/нечётные) недели
							foreach($lesson as $sublesson)
								$sublesson->insertToDb($currentGroup->getName(), $lessonCounter, 0, $day->getNumber(), 
									$this->campusId, $this->course, 1, 1, $this->instituteId);
						else 
							$lesson->insertToDb($currentGroup->getName(), $lessonCounter, 0, $day->getNumber(), 
									$this->campusId, $this->course, 1, 0, $this->instituteId);
						
						$lessonCounter++;
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
			$this->splitBySubgroup($groupLesson, $group, $dayNumber, $lessonNumber);
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
				
				//***********************************************************************************
				//***********************************************************************************
				//***********************************************************************************
				//***********************************************************************************
				//***********************************************************************************
				//отсюда вызывать запросы на добавление в бд трёх переменных выше
				//***********************************************************************************
				//***********************************************************************************
				//***********************************************************************************
				//***********************************************************************************
				//***********************************************************************************
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
}

?>