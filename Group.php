<?php 
require("Day.php");

$DAYS_PER_WEEK = 5;

class Group
{
	private $groupName;
	private $days;
	
	public function __construct($groupName)
	{
		$this->groupName = $groupName;
		$this->days = array();
		
		for($i = 1; $i <= $DAYS_PER_WEEK; $i++)
			$this->days = new Day($i, $groupName);
	}
	
	public function getName()
	{
		return $this->groupName;
	}
	
	public function getDay($dayNumber)
	{
		$dayIndex = $dayNumber - 1;
		
		if(!isset($this->days[$dayIndex]))
			throw new Exception("<font color='#CC0000'><b>Ошибка! Дня №".$dayNumber.
					" для группы ".$this->groupName." не существует!</b></font>");
			
		return $this->days[$dayIndex];
	}
}


?>