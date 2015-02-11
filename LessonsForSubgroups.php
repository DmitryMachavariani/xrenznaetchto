<?php 
require_once("AbstractLesson.php");
require_once("Lesson.php");

class LessonsForSubgroups extends AbstractLesson
{
	private $lessons;
	
	public function __construct($lessons)
	{
		$this->$lessons = $lessons;
	}
	
	public function show()
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;###";
		foreach($lessons as $lesson)
		{
				echo
					"Название пары: ".$lesson->getSubject()."; ".
					"Преподаватель: ".$lesson->getTeacher()."; ".
					"Помещение: ".$lesson->getRoom()."; ".
					"По чётным: ".(false !== $lesson->getOnEvenWeek())."; ".
					"По нечётным: ".(false !== $lesson->getOnOddWeek())."&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
}
?>