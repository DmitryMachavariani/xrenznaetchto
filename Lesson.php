<?php 
require_once("AbstractLesson.php");

class Lesson extends AbstractLesson
{
	private $subject;
	private $teacher;
	private $room;
	private $onEvenWeek;
	private $onOddWeek;
	
	public function __construct($subject = "-", $teacher = "-", $room = "-", $onEvenWeek = true, $onOddWeek = true)
	{
		$this->subject = $subject;
		$this->teacher = $teacher;
		$this->room = $room;
		$this->onEvenWeek = $onEvenWeek;
		$this->onOddWeek = $onOddWeek;
	}
	
	public function show()
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
				"Название пары: ".$this->subject."; ".
				"Преподаватель: ".$this->teacher."; ".
				"Помещение: ".$this->room."; ".
				"По чётным: ".(false !== $this->onEvenWeek)."; ".
				"По нечётным: ".(false !== $this->onOddWeek)."<br>";
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function getTeacher()
	{
		return $this->teacher;
	}
	
	public function getRoom()
	{
		return $this->room;
	}
	
	public function getOnEvenWeek()
	{
		return $this->onEvenWeek;
	}
	
	public function getOnOddWeek()
	{
		return $this->onOddWeek;
	}
}
?>