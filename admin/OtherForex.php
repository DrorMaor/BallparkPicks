<?php
	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    $forex = Get_ForexData();
    $rate = PredictRate(array_reverse($forex));
	$result = round($rate[0],5);
	$result.= " (" . (($rate[1] == "UP") ? "+" : "-") . ")";
	echo $result;


    function PredictRate($forex)
    {
            $change = 0;
            for ($i=2; $i<count($forex); $i++)
                    $change += $forex[$i] - $forex[$i-1] ;
            $rate = [];
            $rate[0] = end($forex) + $change / $i;
            $rate[1] = ($change > 0) ? "UP" : "DOWN";
            return $rate;
    }

    function Get_ForexData()
    {
        $base = $_POST['base'];
        $quote = $_POST['quote'];
        $fullData = shell_exec("python py/forex.py " . $base . " " . $quote);
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
                        $rate = str_replace(" " . $quote, "", $day);
                        array_push($forex, $rate);
                }
                $counter++;
        }
        return $forex;
    }
?>
