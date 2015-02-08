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
            //$this->_file = file_get_contents($this->full_path);
            $this->_file = fopen($this->full_path, "r");
            return $this->_file;
        }
    }

    private function getData($array){        
        while (!feof($this->_file)) {
            $buffer = fgets($this->_file, 4096);

            $explode = explode(":", $buffer);
            if($explode[0] == "g"){
                $explode_g = explode(",", $explode[1]);
                
                foreach($explode_g as $key => $value){
                    $this->_g[$key] = $value;
                }
            }
            
            if($explode[0] == "d"){
                $explode_d = explode("-", $explode[1]);
                
                if(trim($explode[2]) != null){
                    $this->_d[$explode_d[0]][$explode_d[1]] = $explode[2];
                    //echo $explode[2];
                }
            }
        }
        $this->parseLesson();
    }
    
    private function parseLesson(){
        for($i = 0; $i < count($this->_d); $i++){
            for($p = 1; $p <= 7; $p++){
                echo "<hr>";
                $explode = explode(" ", $this->_d[$i][$p]);
                //var_dump($explode);
                //echo $explode[0]."<br />";
                $explode_paru = explode(",", $explode[0]);
                
                foreach ($explode_paru as $key => $value) {
                    //echo $key;
                    $this->_d[$explode_d[0]][$explode_d[1]] = array('type'=>$value);
                    var_dump($this->_d[$explode_d[0]][$explode_d[1]]);
                }
            }
        }
    }

    public function parseData(){
        echo $this->getData($this->_file);
    }
}