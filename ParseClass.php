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
    			$this->getG($explode[1]);
    		else if($command == "d")
    		{
    			$explode_space = explode("|", $explode[2]);
    			foreach ($explode_space as $key => $value) {
    				$this->_lesson[$explode[1][0]][$explode[1][2]] = $value;
    			}
    		}
    	}
    }

    private function getG($buffer){
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