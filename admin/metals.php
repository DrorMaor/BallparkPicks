<?php
//      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

        include("../DbConn.php");
        $metals = array("GC", "SI", "PL");  // they will be shown in reverse order
        foreach ($metals as $metal)
        {
                $data = Get_MetalData($metal);
                $rate = PredictRate(array_reverse($data));
                $sql = "insert into metals (name, theDate, rate, UpDown) ";
                $sql .= "values ('" . $metal . "', curdate(), " . $rate[0] . ", '" . $rate[1] . "'); ";
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

        function Get_MetalData($metal)
        {
		$fullData = shell_exec("sudo python py/metals.py " . $metal);
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
