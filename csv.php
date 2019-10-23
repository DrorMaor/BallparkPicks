<?php
	mail("dror.m.maor@gmail.com", "BPP csv", "");

//	error_reporting(E_ALL);
//	ini_set('display_errors', 1);

	GetAllData();

	download_send_headers("BallparkPicks_" . date("Y-m-d") . ".csv");


	function GetAllData()
	{
		include "DbConn.php";

		// NFL
		$WeekSQL = "select StartDate, EndDate, week from NFLweeks where curdate() between StartDate and EndDate; ";
		$weeks = $conn->query($WeekSQL);
		$week = $weeks->fetch_assoc();
		$title = "NFL Week " . $week["week"];
		$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
			from games g
				inner join teams away on away.code = g.AwayTeam and away.league = 'NFL'
				inner join teams home on home.code = g.HomeTeam and home.league = 'NFL'
			where g.league = 'NFL'
				and GameDate between '" . $week["StartDate"] . "' and '" . $week["EndDate"] . "' ; ";
		GetLeagueData($conn, $GamesSQL, $title);

		// all other leagues
		$leagues = array("MLB", "NBA", "NHL");
		foreach ($leagues as $league)
		{
			$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
				from games g
					inner join teams away on away.code = g.AwayTeam and away.league = '".$league."'
					inner join teams home on home.code = g.HomeTeam and home.league = '".$league."'
				where g.GameDate = curdate() and g.league = '".$league."'; ";
			GetLeagueData($conn, $GamesSQL, $league);
		}

		$conn->close();
	}

	function GetLeagueData($conn, $GamesSQL, $title)
	{
		$games = $conn->query($GamesSQL);
		if (mysqli_num_rows($games) > 0)
		{
			echo "--------------\r\n" . $title . "\r\n--------------\r\n";
			while ($game = $games->fetch_assoc())
			{
				echo $game["AwayTeamName"] . "\t" . $game["AwayScorePick"] . "\r\n";
				echo $game["HomeTeamName"] . "\t" . $game["HomeScorePick"] . "\r\n\r\n";
			}
			echo "\r\n";
		}
	}

	function download_send_headers($filename) 
	{
		// disable caching
		$now = gmdate("D, d M Y H:i:s");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");

		// force download  
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
?>
