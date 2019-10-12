<?php
	//$NYdate = new DateTime("now", new DateTimeZone('America/New_York') );
	//echo $NYdate->format("l F jS, Y");

	echo "NBA <br>";
	$fullData = shell_exec("python ../admin/py/NBA.py");
	echo $fullData;



?>
