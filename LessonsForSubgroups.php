<?php 
require_once("Lesson.php");

class LessonsForSubgroups
{
	public $lessons = array();
	
	public function show()
	{
		echo "COUNT: ".count($this->lessons)."; ";
		for($i = 0; $i < count($this->lessons); $i++)
		{
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пара: ".
				$this->lessons[$i]->subject."; Препод: ".
				$this->lessons[$i]->teacher."; Кабинет: ".
				$this->lessons[$i]->room."; ч нч".
				$this->lessons[$i]->onEven." ".
				$this->lessons[$i]->onOdd." | ";
		}
		
		echo "<br>";
	}
};
?>