<?php
/* @var $this yii\web\View */
$this->title = 'Schedule Parser';

require("../controllers/ScheduleParserController.php");
$parsingController = ScheduleParserController::getInstance();


function answerWordData($word, $masters, $isSubject = true)
{
	$itemValue = ($isSubject)? "предмета": "преподавателя";
	echo "<form action=\"\">";
		echo "<b>Для <font color='#CC0000'>".$itemValue."</font> ".$word.":</b><br>\n";
		echo "Выберите мастер: <br>\n";
		
		if(count($masters) === 0)
		{
			echo "Нет доступных мастеров в таблице, вставить новый как: <br>\n";
			echo "<input type='text' id='masterValue'><br>\n";
		}
		else
		{
			echo "<select name='masterId'>\n";
				echo "<option value=''>----------</option>\n";
				foreach($masters as $master)
				{
					echo "<option value='".$master['id_master']."'>".$master['meaning']."</option>\n";
				}
			echo "</select><br>\n";
			echo "Либо введите своё значение: <br>\n";
			echo "<input type='text' id='masterValue'><br>\n";
		}
		
		if($isSubject)
			echo "<input type='hidden' value='discipline' id='masterType'><br>\n";
		else
			echo "<input type='hidden' value='teacher' id='masterType'><br>\n";
		
		echo "<input type='submit' value='Ввести'>\n";
	echo "</form>\n";
	echo "<br><hr><br>\n";
}

?>
<script>
	var ajax = new XMLHttpRequest();
	
</script>
