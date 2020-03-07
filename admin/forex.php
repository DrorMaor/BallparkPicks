
<?php
	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

	//include("../DbConn.php");
	$pairs = array("EUR/USD", "USD/JPY", "GBP/USD", "AUD/USD", "USD/CHF", "NZD/USD", "USD/CAD");
	foreach ($pairs as $pair)
	{
		$forex = Get_ForexData($pair);
		$rate = PredictRate(array_reverse($forex));
	}

	function PredictRate($forex)
	{
		$change = 0;
		for ($i=2; $i<count($forex); $i++)
			$change += (($forex[$i] - $forex[$i-1]) * $i);
		$rate[0] = end($forex) + $change / $i;
		$rate[1] = ($change > 0);  // true UP, false DOWN
		return $rate;
	}

	function Get_ForexData($pair)
	{
		$coins = explode("/", $pair);
		$fullData = shell_exec("python py/forex.py " + $pair[0] + " " + $pair[1]);
		$fullData = str_replace("[", "", $fullData);
		$fullData = str_replace("]", "", $fullData);
		$fullData = str_replace("'", "", $fullData);
		$days = explode(", ", $fullData);
		$counter = 0;
		$forex = [];
		foreach ($days as $day)
		{
			if ($counter % 2 == 1)
			{
				$rate = str_replace(" " + $pair[0], "", $day);
				array_push($forex, $rate);
			}
			$counter++;
		}
		return $forex;
	}
	//$conn->close();
?>
