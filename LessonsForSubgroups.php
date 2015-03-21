<?php 
require_once("AbstractLesson.php");
require_once("Lesson.php");

class LessonsForSubgroups extends AbstractLesson
{
	private $lessons = null;
	
	
	public function __construct($lessons)
	{
		$this->lessons = $lessons;
	}
	
	public function show()
	{
		foreach($this->lessons as $lesson)
		{
				echo
					"Название пары: ".$lesson->getSubject()."; ".
					"Преподаватель: ".$lesson->getTeacher()."; ".
					"Помещение: ".$lesson->getRoom()."; ".
					"По чётным: ".(false !== $lesson->getOnEvenWeek())."; ".
					"По нечётным: ".(false !== $lesson->getOnOddWeek())."&nbsp;&nbsp;&nbsp;&nbsp;###";
		}
	}
	
	public function insertToDb($group, $lessonNumber, $subgroupNumber, $dayNumber, $campusId, $course, $week_id, $instituteId)
	{
		$subgroupsCounter = 0;
		foreach($this->lessons as $lesson)
		{
			$lesson->insertToDb($group, $lessonNumber, $subgroupsCounter, $dayNumber, $campusId, $course, $week_id, 1, $instituteId);
			$subgroupsCounter++;
		}
				
		
		$this->updateGroupAsWithSubgroups($group);
	}
	
	private function updateGroupAsWithSubgroups($group)
	{
		$updateGroupQuery = "UPDATE groups SET has_subgroups=1 WHERE name='".trim($group)."';";
		mysql_query($updateGroupQuery);
		$GLOBALS['db']->exec($updateGroupQuery);
		//sql_query("UPDATE groups SET has_subgroups=1 WHERE name='".trim($group)."';");
	}
}
?>