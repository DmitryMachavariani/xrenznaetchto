<?php 
require("Day.php");
class Group
{
	public $groupName;
	private $days = array();
	
	public function __construct($groupName)
	{
		$this->groupName = $groupName;
	}
	
	public function getDays()
	{
		return $this->days;
	}
	
	public function getDay($dayNumber)
	{
		return $this->days[$dayNumber - 1];
	}
	
	public function isDayAre($dayNumber)
	{
		return isset($this->days[$dayNumber - 1]);
	}
	
	public function guaranteedGetDay($dayNumber)
	{
		if(! $this->isDayAre($dayNumber))
			$this->days[$dayNumber - 1] = new Day();
		
		return $this->days[$dayNumber - 1];
	}
}


?>