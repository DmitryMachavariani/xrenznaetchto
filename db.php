<?
$dbConnect = mysql_connect('localhost', 'root', '');
mysql_select_db("schedule", $dbConnect);

mysql_query('SET NAMES `utf8`');


$GLOBALS['db'] = new SQLite3('pattern.db');
$GLOBALS['db']->busyTimeout(5000);
?>