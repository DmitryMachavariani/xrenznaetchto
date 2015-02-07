<?php
class ParseClass{
    //Переменные хранящие название папки и название файла
    public $dir_name;
    public $file_name;
    protected $full_path;

    //Переменная хранящая файл
    public $_file;
    
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
            $this->_file = file_get_contents($this->full_path);
            return $this->_file;
        }
    }
    
    //Проверяем файл на корректность
    public function getIsFileCurrect(){
        $explode_1 = explode(":", $this->_file);
        
        if(count($explode_1) == 1)
            echo "Файл некорректен";
        else
            echo "Файл корректен";
    }
    
    private function firstStep(){
        $explode = explode("&", $this->_file);
        
        if(count($explode) == 1)
            echo "Файл некорректен, отсутствует &";
        else
            return $explode;
    }
    
    private function secondStep(){
        $explode = explode(">>, $this->_file");
   
        if(count($explode) == 0)
            echo "Файл неккоректен, отсутствует >>";
        else
            return $explode;
    }


    public function parseData(){
        var_dump($this->secondStep());
    }
}