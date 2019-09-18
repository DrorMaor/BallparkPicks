<?php
	$NYdate = new DateTime("now", new DateTimeZone('America/New_York') );
	echo $NYdate->format("l F jS, Y");
	/*
	echo "NFL <br>";
	$fullData = shell_exec("python py/NFL.py");
	echo $fullData;
	*/


?>
