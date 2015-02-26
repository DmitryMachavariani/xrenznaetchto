<?php 
require("Day.php");

class Group
{
	private $groupName;
	private $days;
	private $DAYS_PER_WEEK = 5;
	
	public function __construct($groupName)
	{
		$this->groupName = $groupName;
		$this->days = array();
		
		for($i = 0; $i <= $this->DAYS_PER_WEEK; $i++)
		{
			$this->days[(int)$i] = new Day($i, $groupName);
		}
			
	}
	
	public function getName()
	{
		return $this->groupName;
	}
	
	public function getDay($dayNumber)
	{
		$dayIndex = (int)$dayNumber - 1;
		
		if(!isset($this->days[$dayIndex]))
			throw new Exception("<font color='#CC0000'><b>Ошибка! Дня №".$dayNumber.
					" для группы ".$this->groupName." не существует!</b></font>");
			
		return $this->days[$dayIndex];
	}
	
	public function getDays()
	{
		return $this->days;
	}
}
?>