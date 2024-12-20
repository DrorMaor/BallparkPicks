
<?php
//	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

	include("../DbConn.php");
	$pairs = array("EUR/USD", "USD/JPY", "GBP/USD", "AUD/USD", "USD/CHF", "NZD/USD", "USD/CAD");
	foreach ($pairs as $pair)
	{
		$forex = Get_ForexData($pair);
		$rate = PredictRate(array_reverse($forex));
		$coins = explode("/", $pair);
		$sql = "insert into forex (base, quote, theDate, rate, UpDown) ";
		$sql .= "values ('" . $coins[0] . "', '" . $coins[1] . "', curdate(), " . $rate[0] . ", '" . $rate[1] . "' ); ";
		$conn->query($sql);
	}

	function PredictRate($forex)
	{
		$change = 0;
		for ($i=2; $i<count($forex); $i++)
			$change += $forex[$i] - $forex[$i-1] ;
		$rate = [];
		$rate[0] = end($forex) + $change / $i;
		$rate[0] = round($rate[0], 5);
		$rate[1] = ($change > 0) ? "UP" : "DOWN";
		return $rate;
	}

	function Get_ForexData($pair)
	{
		$coins = explode("/", $pair);
		$fullData = shell_exec("python py/forex.py " . $coins[0] . " " . $coins[1]);
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
				$rate = str_replace(" " . $coins[1], "", $day);
				array_push($forex, $rate);
			}
			$counter++;
		}
		return $forex;
	}

	$conn->close();
?>
