<?php
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
    
    private function parseDay($explode)
    {
    	if($this->isTodayNoLessons($explode))
    	{
    		//TODO ЗАПИХНУТЬ ПУСТУЮ ПАРУ
    		return;
    	}
    	 
    	$this->parseDayAndLessonNumber($explode);
  		$this->parseLessons($explode);
    }
    
    private function isTodayNoLessons($explode)
    {
    	$explode[2] = trim($explode[2]);
    	return count($explode) < 3 || empty($explode[2]);
    }
    
    private function parseDayAndLessonNumber($explode)
    {
    	$dayAndLesson = explode("-", $explode[1]);
    	if(count($dayAndLesson) < 2)
    		throw new Exception("<font color='#CC0000'><b>Ошибка! Номер для недели и номер пары должен быть в формате дн-нп</b></font>");
    	 
    	//TODO ЗАПИХНУТЬ НОМЕР ПАРЫ И ДЕНЬ НЕДЕЛИ КУДА НУЖНО
    	//день недели - $dayAndLesson[0]
    	//номер пары - $dayAndLesson[1];
    }
    
    private function parseLessons($explode)
    {
    	$groups = explode("&", $explode[2]);
    	foreach($groups as $groupLesson)
    	{
    		$groupNumbersAndLessonData = explode(">>", $groupLesson);
    		$groupNumbers = explode(",", $groupNumbersAndLessonData[0]);
    		$lessonData = $groupNumbersAndLessonData[1];
    		
    		$lessonSubgroupData = explode("#", $lessonData);
    		
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
    			
    			//TODO ЗАПИХНУТЬ НАЗВАНИЕ ПРЕДМЕТОВ, ПРЕПОДАВАТЕЛЕЙ И КАБИНЕТЫ
    			/*
    			 * $lessondNameTeacherAndPlace[0] - НАЗВАНИЕ ПРЕДМЕТА
    			 * $lessondNameTeacherAndPlace[1] - ПРЕПОДАВАТЕЛЬ
    			 * $lessondNameTeacherAndPlace[2] - КАБИНЕТ
    			 */
    			var_dump($lessondNameTeacherAndPlace);
    			echo"<br><br>";
    		}	
    	}
    }

    private function getGroupNumbers($buffer){
        $explode = explode(",", $buffer);
        foreach ($explode as $key => $value) {
            array_push($this->_g, $value);
        }
    }

    public function parseData(){
        $this->getData();
        var_dump($this->_lesson);
        echo "<Br><br>";
        var_dump($this->_g);
    }
}

?>