<?
class Dictionary
{
	private $db;
	
	public function __construct()
	{
		$this->db = Yii::$app->db;
	}
	
	public function getWord($word)
	{
		$word = trim($word);
		
		if(empty($word))
			return null;
		
		$query = $this->db->createCommand("SELECT id, id_master, master_type FROM dictionary WHERE meaning LIKE '%".$word."%';");
		$wordRecord = $query->queryAll();
		
		return $wordRecord;
	}
	
	public function addWord($word, $master_type, $id_master)
	{
		$word = trim($word);
		if(empty($word))
			return;
		
		$query = $this->db->createCommand("INSERT INTO dictionary (id_master, master_type, meaning, status) 
  					VALUES (\"".$id_master."\", \"".$master_type."\", \"".$word."\", 0);");
		$query->execute();
					
	}
	
	public function getMastersForSubjects()
	{
		$query = $this->db->createCommand("SELECT DISTINCT id_master, meaning FROM dictionary WHERE master_type='discipline' ORDER BY id DESC;");
		$wordRecord = $query->queryAll();
		
		return $wordRecord;
	}
	
	public function insertLesson($subject, $teacher, $room, $onOdd, $onEven)
	{
		//TODO 
		$query = $this->db->createCommand("");
		$query->execute();
	}
}



?>