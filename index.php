<html>
    <head>
        <meta charset="utf-8">
    </head>
</html>
<?php

$debug = true;

if($debug)
{
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}

require("ParseClass.php");

$class = new ParseClass('file', 'descr.txt');

$class->loadFile();
//$class->getIsFileCurrect();
$class->parse();

?>