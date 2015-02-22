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

$directoryWithFilesForParse = "file";
$filesArray = readDirectory($directoryWithFilesForParse);

foreach($filesArray as $currentFile)
{
	if(!is_array($currentFile))
	{
		$class = new ParseClass($directoryWithFilesForParse, $currentFile);
		$class->loadFile();
		$class->parse();
	}
		
}

function readDirectory($dir)
{
	$result[] = array();
	if ($handle = opendir($dir))
	{
		while ($file = readdir($handle))
			if ($file != "." && $file != "..")
				array_push($result, $file);
			
		closedir($handle);
	}
	
	return $result;
}

?>
