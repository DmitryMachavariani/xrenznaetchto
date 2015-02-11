<?php 
class Lesson
{
	public $teacher;
	public $subject;
	public $room;
	public $onEven;
	public $onOdd;
	
	public function show()
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пара: ".
				$this->subject."; Препод: ".
				$this->teacher."; Кабинет: ".
				$this->room."; ч нч".
				$this->onEven." ".
				$this->onOdd."<br>";
	}
};
?>