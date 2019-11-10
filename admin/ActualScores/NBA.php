<?php

	$month = $_GET["month"];
	$day = $_GET["day"];
	$year = $_GET["year"];
    /*
    $date = new DateTime(); // For today/now, don't pass an arg.
    $date->modify("-1 day");
    $month = $date->format('m');
    $day = $date->format('d');
    $year = $date->format('Y');
    */
	$html = file_get_contents("https://www.basketball-reference.com/boxscores/?month=".$month."&day=".$day."&year=".$year);
	$start = strpos($html, "Find Games");
	$end =   strpos($html, "Division Standings");
	$games =  substr($html, $start, $end-$start);

	$pattern = '#<td><a href="/teams/(.*?)/2020.html#';
	preg_match_all($pattern, $games, $teams);

	$pattern = '#<td class="right">(.*?)</td>#';
	preg_match_all($pattern, $games, $scores);

	$sql = "";
	for ($i=0; $i < sizeof($teams[1]); $i++)
	{
		if ($i % 2 == 0)
		{
            if ($teams[1][$i] != $awayTeam)
            {
    			$awayScore = $scores[1][$i];
    			$awayTeam = $teams[1][$i];
            }
		}
		else
		{
            if ($teams[1][$i] != $homeTeam)
            {
    			$homeScore = $scores[1][$i];
                $homeTeam = $teams[1][$i];

    			// the game ends here, so save it
    			$sql .= "update games set AwayScoreActual = " . $awayScore . ", HomeScoreActual = " . $homeScore;
    	                $sql .= " where AwayTeam = '" . $awayTeam . "' and HomeTeam = '" . $homeTeam . "' ";
            	        $sql .= " and league = 'NBA' and GameDate = '" . $year . "-" . $month . "-" . $day . "'" ;
    			$sql .= " and AwayScoreActual is null and HomeScoreActual is null; ";
            }
        }
	}
    //echo $sql;
	include("../../DbConn.php");
    $result = $conn->multi_query($sql);
    $conn->close();
?>
