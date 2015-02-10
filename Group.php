<?php 
require("Day.php");
class Group
{
	public $groupName;
	public $days = array();
	
	public function __construct($groupName)
	{
		$this->groupName = $groupName;
	}
}


?>