<html>
    <head>
        <meta charset="utf-8">
    </head>
</html>
<?php
require("ParseClass.php");

$class = new ParseClass('file', 'descr.txt');

$class->loadFile();
//$class->getIsFileCurrect();
$class->parseData();