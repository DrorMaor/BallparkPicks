<?php
	include("../DbConn.php");
	$league = $_GET["league"];
	$sql = "select AwayTeam.name as AwayTeam, g.AwayScorePick, HomeTeam.name as HomeTeam, g.HomeScorePick from games g
			inner join teams AwayTeam on AwayTeam.code = g.AwayTeam and AwayTeam.league = '" . $league . "'
			inner join teams HomeTeam on HomeTeam.code = g.HomeTeam and HomeTeam.league = '" . $league . "'
		where g.GameDate = curdate() and g.league = '" . $league . "' order by g.id limit 3; ";
	$results = $conn->query($sql) or die($conn->error);
	$tweet = "Here are some #" . $league . " picks for today:\r\n\r\n";
	while ($row = $results->fetch_assoc())
		$tweet.= $row["AwayTeam"]." ".$row["AwayScorePick"]." @ ".$row["HomeTeam"]." ".$row["HomeScorePick"]."\r\n";
	$tweet.= "\r\nThe rest can be found on www.BallparkPicks.com";
	echo $tweet;
	$conn->close();
?>


