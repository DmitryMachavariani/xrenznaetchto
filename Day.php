<?php 
require("Lesson.php");
require("LessonsForSubgroups.php");
class Day
{
	public $lessons = array();
	
	public function getLessons()
	{
		return $this->lessons;
	}
	
	public function getLesson($lessonNumber)
	{
		if(! isset($this->lessons[$lessonNumber - 1]))
			throw new Exception("<font color='#CC0000'><b>Ошибка! Урока № ".$lessonNumber." не существует</b></font>");
		
		return $this->lessons[$lessonNumber - 1];
	}
	
	public function addLesson($lessonNumber, $teacher = "-", $subject = "-", $room = "-", $onEven = true, $onOdd = true)
	{
		$lessonIndex = $lessonNumber - 1;
		if(! $this->isLessonAre($lessonIndex))
			$this->lessons[$lessonIndex] = new Lesson();
		
		$currentLesson = $this->lessons[$lessonIndex];
		$currentLesson->teacher = $teacher;
		$currentLesson->subject = $subject;
		$currentLesson->room = $room;
		$currentLesson->onEven = $onEven;
		$currentLesson->onOdd = $onOdd;
		
		$this->lessons[$lessonIndex] = $currentLesson;
	}
	
	public function addSubgroupLesson($lessonNumber, $teacher = "-", $subject = "-", $room = "-", $onEven = true, $onOdd = true)
	{
		$lessonIndex = $lessonNumber - 1;
		$this->lessons[$lessonIndex] = new LessonsForSubgroups();
	
		$currentLesson = $this->lessons[$lessonIndex];
		$currentLesson->teacher[] = $teacher;
		$currentLesson->subject[] = $subject;
		$currentLesson->room[] = $room;
		$currentLesson->onEven[] = $onEven;
		$currentLesson->onOdd[] = $onOdd;
	
		$this->lessons[$lessonIndex] = $currentLesson;
	}
	
	public function isLessonAre($lessonIndex)
	{
		return isset($this->lessons[$lessonIndex]);
	}
}

?>