
<?php
//	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

/*
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
*/

	$index = Get_IndexData();
	$rate = PredictRate($index);
	print_r( $rate);

	function PredictRate($index)
	{
		$change = 0;
		for ($i=2; $i<count($index); $i++)
			$change += $index[$i] - $index[$i-1] ;
		$rate = [];
		$rate[0] = end($index) + $change / $i;
		$rate[0] = round($rate[0], 5);
		$rate[1] = ($change > 0) ? "UP" : "DOWN";
		return $rate;
	}

	function Get_IndexData()
	{
		$fullData = shell_exec("sudo python py/nasdaq.py");
		print_r($fullData);
		$fullData = str_replace("[", "", $fullData);
		$fullData = str_replace("]", "", $fullData);
		$days = explode(", ", $fullData);
		$counter = 0;
		$index = [];
		foreach ($days as $day)
			array_push($index, $day);

		return $index;
	}

//	$conn->close();
?>
