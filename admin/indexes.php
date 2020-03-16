<?php
//      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

        include("../DbConn.php");
        $indexes = array("IXIC", "DJI", "GSPC", "RUT");
        foreach ($indexes as $index)
        {
                $data = Get_IndexData($index);
                $rate = PredictRate(array_reverse($data));
                $sql = "insert into indexes (name, theDate, rate, UpDown) ";
                $sql .= "values ('" . $index . "', curdate(), " . $rate[0] . ", '" . $rate[1] . "'); ";
     		$conn->query($sql);
	}

        function PredictRate($data)
        {
		$change = 0;
		for ($i=2; $i<count($data); $i++)
			$change += $data[$i] - $data[$i-1] ;
		$rate = [];
		$rate[0] = end($data) + $change / $i;
		$rate[0] = round($rate[0], 5);
		$rate[1] = ($change > 0) ? "UP" : "DOWN";
		return $rate;
        }

        function Get_IndexData($index)
        {
		$fullData = shell_exec("sudo python py/indexes.py " . $index);
		$fullData = str_replace("[", "", $fullData);
		$fullData = str_replace("]", "", $fullData);
		$fullData = str_replace("'", "", $fullData);
		$days = explode(", ", $fullData);
		$data = [];
		foreach ($days as $day)
			array_push($data, $day);

		return $data;
        }

	$conn->close();
?>



