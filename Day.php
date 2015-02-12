<?php 
require("Lesson.php");
require("LessonsForSubgroups.php");
class Day
{
	private $lessons;
	private $number;
	private $group;
	
	public function __construct($number, $group)
	{
		$this->number = $number;
		$this->group = $group;
		$lessons = array();
	}
	
	public function addLesson($lessonNumber, $lesson)
	{
		$lessonIndex = $lessonNumber - 1;
		
		if(isset($this->lesson[$lessonIndex]) && !empty($this->lesson[$lessonIndex]))
			throw new Exception("<font color='#CC0000'><b>Ошибка! Пара №".$lessonNumber.
					" в дне номер ".$this->number." для группы ".$this->group." уже занята чем-то другим!</b></font>");
			
		$this->lessons[$lessonIndex] = $lesson;
	}
	
	public function getLesson($lessonNumber)
	{
		$lessonIndex = $lessonNumber - 1;
		if(!isset($this->lesson[$lessonIndex]))
			throw new Exception("<font color='#CC0000'><b>Ошибка! Пары №".$lessonNumber.
					" в дне номер ".$this->number." для группы ".$this->group." не существует!</b></font>");
		
		return $this->lesson[$lessonIndex];
	}
	
	public function getLessons()
	{
		return $this->lessons;
	}
	
	public function getNumber()
	{
		return $this->number;
	}
}

?>