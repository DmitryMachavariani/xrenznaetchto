<?php
require("Group.php");


class ParseClass{
    //Переменные хранящие название папки и название файла
    public $dir_name;
    public $file_name;
    protected $full_path;

    //Переменная хранящая файл
    public $_file;
    
    protected $_g = array();
    protected $_count = array();
    protected $_lesson = array();
    
    private $groups;
    
    //Законсим первоначальные значения
    public function __construct($dir_name, $file_name) {
        $this->dir_name = $dir_name;
        $this->file_name = $file_name;
        
        $this->full_path = __DIR__.DIRECTORY_SEPARATOR.$this->dir_name.DIRECTORY_SEPARATOR.$this->file_name;
        $this->groups = array();
    }
    
    //Проверяем папку
    protected function checkFolder(){
        if($this->dir_name != '' && is_dir($this->dir_name))
            return true;
        else
            return false;
    }
    
    //Проверяем файл
    protected function checkFile(){
        if(file_exists($this->full_path) && is_file($this->full_path))
            return true;
        else
            return false;
    }
    
    //Загружаем файл
    public function loadFile(){
        if($this->checkFolder() && $this->checkFile()){
            $this->_file = fopen($this->full_path, "r");
            return $this->_file;
        }
    }

    private function getData()
    {
    	//while has next line
    	while(!feof($this->_file))
    	{
    		$buffer = fgets($this->_file);
    		$explode = explode(":", $buffer);
    		
    		//is line contains colon and something else
    		if(count($explode) == 0)
    			continue;
    		
    		$command = $explode[0];
    		
    		if($command == "g")
    			$this->getGroupNumbers($explode[1]);
    		else if($command == "d")
    		{
    			$this->parseDay($explode);
    		}
    	}
    }
    
    private function getGroupNumbers($buffer)
    {
    	$explode = explode(",", $buffer);
    	foreach ($explode as $key) 
    	{
    		$this->putNewGroup($key);
    	}
    }
    
    private function putNewGroup($groupName)
    {
    	array_push($this->groups, new Group($groupName));
    }
    
    
    
    private function parseDay($explode)
    {
    	$dayAndLesson = $this->parseDayAndLessonNumber($explode);
    	$dayNumber = $dayAndLesson[0];
    	$lessonNumber = $dayAndLesson[1];
    	
    	if($this->isAtThatTimeNoLessons($explode))
    	{
    		foreach($this->groups as $group)
    		{
    			$group->guaranteedGetDay($dayNumber)->addLesson($lessonNumber);
    		}
    		return;
    	}
    	
  		$this->parseLessons($explode, $dayNumber, $lessonNumber);
    }
    
    private function parseDayAndLessonNumber($explode)
    {
    	$dayAndLesson = explode("-", $explode[1]);
    	if(count($dayAndLesson) < 2)
    		throw new Exception("<font color='#CC0000'><b>Ошибка! Номер для недели и номер пары должен быть в формате дн-нп</b></font>");
    	 
    	foreach($this->groups as $group)
    	{
    		$group->guaranteedGetDay($dayAndLesson[0]);
    	}
    	
    	return $dayAndLesson;
    
    	//TODO ЗАПИХНУТЬ НОМЕР ПАРЫ И ДЕНЬ НЕДЕЛИ КУДА НУЖНО
    	//день недели - $dayAndLesson[0]
    	//номер пары - $dayAndLesson[1];
    }
    
    private function isAtThatTimeNoLessons($explode)
    {
    	try
    	{
    		$explode[2] = trim($explode[2]);
    	}
    	catch(Exception $e)
    	{
    		$e->getMessage();
    	}
    	
    	return count($explode) < 3 || empty($explode[2]);
    }
    
    private function parseLessons($explode, $dayNumber, $lessonNumber)
    {
    	$groups = explode("&", $explode[2]);
    	foreach($groups as $groupLesson)
    	{
    		$groupNumbersAndLessonData = explode(">>", $groupLesson);
    		$groupNumbers = explode(",", $groupNumbersAndLessonData[0]);
    		$lessonData = $groupNumbersAndLessonData[1];
    		
    		$lessonSubgroupData = explode("#", $lessonData);
    		
    		if(count($lessonSubgroupData) == 1)
    		{
    			$this->addLesson($lessonSubgroupData[0], $dayNumber, $lessonNumber, $groupNumbers);
    		}
    		else
    		{
    			foreach($lessonSubgroupData as $subgroupLesson)
    			{
    				$subgroupLesson = trim($subgroupLesson);
    				if(empty($subgroupLesson))
    				{
    					return;
    				}
    				
    				$onEvenAndOddWeekAndLessonsDetails = explode("/", $subgroupLesson);
    				$onEvenWeek = strpos($onEvenAndOddWeekAndLessonsDetails[0], "ч") !== false;
    				$onOddWeek = strpos($onEvenAndOddWeekAndLessonsDetails[0], "н") !== false;
    				
    				$lessondNameTeacherAndPlace = explode("|", $onEvenAndOddWeekAndLessonsDetails[1]);
    				
    				$subject = $lessondNameTeacherAndPlace[0];
    				$teacher = $lessondNameTeacherAndPlace[1];
    				$room = $lessondNameTeacherAndPlace[2];
    				
    				foreach($groupNumbers as $groupNumber)
    				{
    					try
    					{
    						$this->groups[(int)$groupNumber]->
    						guaranteedGetDay($dayNumber)->
    						addSubgroupLesson((int)$lessonNumber, $teacher, $subject, $room, $onEvenWeek, $onOddWeek);
    					}
    					catch(Exception $e)
    					{
    						$e->getMessage();
    					}
    					 
    				}
    			}
    		}	
    		
    	}
    }
    
    private function addLesson($lessonData, $dayNumber, $lessonNumber, $groupNumbers)
    {
    	var_dump($lessonData);
    	$lessonData = trim($lessonData);
    	if(empty($lessonData))
    	{
    		return;
    	}
    	 
    	$onEvenAndOddWeekAndLessonsDetails = explode("/", $lessonData);
    	$onEvenWeek = strpos($onEvenAndOddWeekAndLessonsDetails[0], "ч") !== false;
    	$onOddWeek = strpos($onEvenAndOddWeekAndLessonsDetails[0], "н") !== false;
    	 
    	$lessondNameTeacherAndPlace = explode("|", $onEvenAndOddWeekAndLessonsDetails[1]);
    	 
    	$subject = $lessondNameTeacherAndPlace[0];
    	$teacher = $lessondNameTeacherAndPlace[1];
    	$room = $lessondNameTeacherAndPlace[2];
    	 
    	foreach($groupNumbers as $groupNumber)
    	{
    		try
    		{
    			$this->groups[(int)$groupNumber]->
    			guaranteedGetDay($dayNumber)->
    			addLesson($lessonNumber, $teacher, $subject, $room, $onEvenWeek, $onOddWeek);
    		}
    		catch(Exception $e)
    		{
    			$e->getMessage();
    		}
    		 
    	}
    	 
    	//TODO ЗАПИХНУТЬ НАЗВАНИЕ ПРЕДМЕТОВ, ПРЕПОДАВАТЕЛЕЙ И КАБИНЕТЫ
    	/*
    	 * $lessondNameTeacherAndPlace[0] - НАЗВАНИЕ ПРЕДМЕТА
    	 * $lessondNameTeacherAndPlace[1] - ПРЕПОДАВАТЕЛЬ
    	 * $lessondNameTeacherAndPlace[2] - КАБИНЕТ
    	 */
    	 //var_dump($lessondNameTeacherAndPlace);
    	//echo"<br><br>";
    }
    

    public function parseData(){
        $this->getData();
        
        foreach($this->groups as $group)
        {
        	echo "Группа: ".$group->groupName."<br>";
        	
        	for($i = 0; $i < count($group->getDays()); $i++)
        	{
        		echo "&nbsp;&nbsp;&nbsp;&nbsp;День ".$i."<br>";
        		$lessonCounter = 0;
        		foreach($group->guaranteedGetDay($i)->getLessons() as $lesson)
        		{
        			$lesson->show();	
        		}
        			
        		$lessonCounter++;
        	}
        }
    }
}

?>