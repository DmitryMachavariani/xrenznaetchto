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
		
		if(isset($this->lessons[(int)$lessonIndex]) && !empty($this->lessons[(int)$lessonIndex]))
		{
			$temp = $this->lessons[$lessonIndex];
			$this->lessons[$lessonIndex] = array();
			array_push($this->lessons[$lessonIndex], $temp);
			array_push($this->lessons[$lessonIndex], $lesson);
			
			return;
		}
			
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