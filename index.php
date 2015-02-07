<html>
    <head>
        <meta charset="utf-8">
    </head>
</html>
<?php
require("ParseClass.php");

$class = new ParseClass('file', '1test.txt');

$class->loadFile();
//$class->getIsFileCurrect();
$class->parseData();