<?php 
require_once("AbstractLesson.php");
require_once("db.php");

class Lesson extends AbstractLesson
{
	private $subject;
	private $teacher;
	private $room;
	private $onEvenWeek;
	private $onOddWeek;
	private $recursionDeep;
	
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
		echo "".
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
	
	public function insertToDb($group, $lessonNumber, $subgroupNumber, $dayNumber, $campusId, $course, $week_id, $forSubgroups = 0, $instituteId)
	{
		$this->recursionDeep = 0;
		$group_id = $this->getIdAndInsertIfNotExitsGroup($group, $campusId, $instituteId, $course);
		
		echo "aaa ".is_array($group_id);
		
		$query = "INSERT INTO `lessons`". 
			"(id_groups, id_weeks, discipline, teacher, place, status, lesson_number, day_number,". 
			"on_odd, on_even, id_subgroup, for_subgroups, campus_id, institute_id)".
			"VALUES (".$group_id.", ".$week_id.", '".$this->subject."', '".$this->teacher."', '".$this->room."', 1, ".$lessonNumber.",". 
			(int)$dayNumber.", ".(int)$this->onOddWeek.", ".(int)$this->onEvenWeek.", ".$subgroupNumber.", ".(int)$forSubgroups.", ".(int)$campusId
			.", ".(int)$instituteId.");";
			
		//echo "<br>\nquery: ".$query."\n<br>";
		mysql_query($query) or die(mysql_error());
		
		$GLOBALS['db']->exec($query);
	}
	
	public function getIdAndInsertIfNotExitsGroup($group, $campusId, $instituteId, $course)
	{
		if($this->recursionDeep >= 2)
			throw new Exception("Внезапно, почему-то не вставляется группа ".$group."в бд<br>\n");
		
		$group = trim($group);
		$queryForSelectId = "SELECT _id FROM groups WHERE name='".$group."';";
		$result = mysql_query($queryForSelectId);
		
		$returnedRecords = mysql_num_rows($result);
		echo "Получено записей: ".$returnedRecords."<br>\n";
		if($returnedRecords > 1)
		{
			throw new Exception("Групп больше чем одна");
		}
		else if($returnedRecords == 1)
		{
			$returned_id = mysql_fetch_array($result)[0];
			return $returned_id;
		}
		else if($returnedRecords == 0)
		{
			$insertQueryString = "INSERT INTO `groups` (name, status, has_subgroups, campus_id, institute_id, course) VALUES ('".$group."', 1, 0, '".$campusId."', '".$instituteId."', '".$course."');";
			$insertQuery = mysql_query($insertQueryString);
			
			//т.к. группа уже будет в бд, снова вызваем эту же фукнцию рекурсивно.
			$this->recursionDeep++;
			$id = $this->getIdAndInsertIfNotExitsGroup($group, $campusId, $instituteId, $course);
			try
			{
				$insertSqlite = "INSERT INTO `groups` (_id, name, status, has_subgroups, campus_id, institute_id, course) VALUES (".$id.",'".$group."', 1, 0, '".$campusId."', '".$instituteId."', '".$course."');";
				$GLOBALS['db']->exec($insertSqlite);
				echo $insertSqlite."<br>\n";
			}
			catch(Exception $e)
			{
				echo $e->getTraceAsString();
			}
			
			return $id;
		}
	}
}
?>