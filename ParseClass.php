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
    
    private $groups = array();
    
    //Законсим первоначальные значения
    public function __construct($dir_name, $file_name) {
        $this->dir_name = $dir_name;
        $this->file_name = $file_name;
        
        $this->full_path = __DIR__.DIRECTORY_SEPARATOR.$this->dir_name.DIRECTORY_SEPARATOR.$this->file_name;
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
    		$this->groups[] = new Group($key);
    	}
    }
    
    private function parseDay($explode)
    {
    	$dayNumber = $this->parseDayAndLessonNumber($explode);
    	
    	if($this->isAtThatTimeNoLessons($explode))
    	{
    		//TODO ЗАПИХНУТЬ ПУСТУЮ ПАРУ
    		$emptyLesson = new Lesson();
    		$emptyLesson->subject = "-";
    		foreach($this->groups as $group)
    		{
    			$group->days[$dayNumber]->lessons[0][0] = $emptyLesson;
    		}
    		return;
    	}
    	
  		$this->parseLessons($explode, $dayNumber);
    }
    
    private function parseDayAndLessonNumber($explode)
    {
    	$dayAndLesson = explode("-", $explode[1]);
    	if(count($dayAndLesson) < 2)
    		throw new Exception("<font color='#CC0000'><b>Ошибка! Номер для недели и номер пары должен быть в формате дн-нп</b></font>");
    	 
    	foreach($this->groups as $group)
    	{
    		if(count($group->days) <= $dayAndLesson[0])
    			$group->days[] = new Day();
    		
    		$group->days[$dayAndLesson[0]]->lessons[0][0] = $dayAndLesson[1];
    	}
    	
    	return $dayAndLesson[0];
    
    	//TODO ЗАПИХНУТЬ НОМЕР ПАРЫ И ДЕНЬ НЕДЕЛИ КУДА НУЖНО
    	//день недели - $dayAndLesson[0]
    	//номер пары - $dayAndLesson[1];
    }
    
    private function isAtThatTimeNoLessons($explode)
    {
    	$explode[2] = trim($explode[2]);
    	return count($explode) < 3 || empty($explode[2]);
    }
    
    private function parseLessons($explode, $dayNumber)
    {
    	$groups = explode("&", $explode[2]);
    	foreach($groups as $groupLesson)
    	{
    		$groupNumbersAndLessonData = explode(">>", $groupLesson);
    		$groupNumbers = explode(",", $groupNumbersAndLessonData[0]);
    		$lessonData = $groupNumbersAndLessonData[1];
    		
    		$lessonSubgroupData = explode("#", $lessonData);
    		
    		$subgroupCounter = 0;
    		foreach($lessonSubgroupData as $subgroupData)
    		{
    			$subgroupData = trim($subgroupData);
    			if(empty($subgroupData))
    			{
    				//у подгруппы нет занятий
    				continue;
    			}
    			
    			$onEvenAndOddWeekAndLessonsDetails = explode("/", $subgroupData);
    			$onEvenWeek = strpos($onEvenAndOddWeekAndLessonsDetails[0], "ч") !== false;
    			$onOddWeek = strpos($onEvenAndOddWeekAndLessonsDetails[0], "н") !== false;
    			
    			$lessondNameTeacherAndPlace = explode("|", $onEvenAndOddWeekAndLessonsDetails[1]);
    			
    			$lesson = new Lesson();
    			$lesson->subject = $lessondNameTeacherAndPlace[0];
    			$lesson->teacher = $lessondNameTeacherAndPlace[1];
    			$lesson->room = $lessondNameTeacherAndPlace[2];
    			$lesson->onEven = $onEvenWeek;
    			$lesson->onOdd = $onOddWeek;
    			
    			foreach($groupNumbers as $groupNumber)
    			{
    				try 
    				{
    					$this->groups[$groupNumber]->days[$dayNumber]->lessons[$subgroupCounter][] = $lesson;
    				}
    				catch(Exception $e)
    				{
    					$e->getMessage();
    				}
    				
    			}
    			
    			
    			$subgroupCounter++;
    			//TODO ЗАПИХНУТЬ НАЗВАНИЕ ПРЕДМЕТОВ, ПРЕПОДАВАТЕЛЕЙ И КАБИНЕТЫ
    			/*
    			 * $lessondNameTeacherAndPlace[0] - НАЗВАНИЕ ПРЕДМЕТА
    			 * $lessondNameTeacherAndPlace[1] - ПРЕПОДАВАТЕЛЬ
    			 * $lessondNameTeacherAndPlace[2] - КАБИНЕТ
    			 */
    			//var_dump($lessondNameTeacherAndPlace);
    			//echo"<br><br>";
    		}	
    	}
    }

    public function parseData(){
        $this->getData();
        
        foreach($this->groups as $group)
        {
        	echo "Группа: ".$group->groupName."<br>";
        	
        	for($i = 0; $i < count($group->days); $i++)
        	{
        		echo "&#9;День ".$i."<br>";
        		foreach($group->days[$i]->lessons as $lesson)
        		{
        			foreach($lesson as $subgroupLesson)
        			{
        				echo "&#9;&#9;Пара: ".$subgroupLesson->subject."; Препод: ".$subgroupLesson->teacher."; Кабинет: ".$subgroupLesson->room."; ч нч".$subgroupLesson->onEven." ".$subgroupLesson->onOdd."<br>";
        			}
        		}
        	}
        }
    }
}

?>