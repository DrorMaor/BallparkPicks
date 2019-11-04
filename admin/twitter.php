<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include("../DbConn.php");
	$league = $_GET["league"];
	$sql = "select AwayTeam.name as AwayTeam, g.AwayScorePick, HomeTeam.name as HomeTeam, g.HomeScorePick from games g
			inner join teams AwayTeam on AwayTeam.code = g.AwayTeam and AwayTeam.league = '" . $league . "'
			inner join teams HomeTeam on HomeTeam.code = g.HomeTeam and HomeTeam.league = '" . $league . "'
		where g.GameDate = curdate() and g.league = '" . $league . "' order by g.id limit 3; ";
	$results = $conn->query($sql) or die($conn->error);
	if ($results->num_rows >0)
	{
		$tweet = "Here are some #" . $league . " picks for today:\r\n\r\n";
		while ($row = $results->fetch_assoc())
		{
			$tweet.= $row["AwayTeam"]." ".$row["AwayScorePick"]."\r\n";
			$tweet.= $row["HomeTeam"]." ".$row["HomeScorePick"]."\r\n\r\n";
		}
		$tweet.= "\r\nThe rest can be found on www.BallparkPicks.com";

		require_once('codebird.php');
		$ConsumerKey = "w5BmQ5rxszPjTt5rLG4QrwFE3";
		$ConsumerSecret = "EKqHKxcXFMtXMBl84AkdJlCyC8cfPXuPILjUd6zfoL4cqZcIzn";
		\Codebird\Codebird::setConsumerKey($ConsumerKey, $ConsumerSecret);
		$cb = \Codebird\Codebird::getInstance();
		$AccessToken = "1184552058524971008-GSiwlsaG19ZYtcr08OaOHZKh7WvJ6V";
		$AccessTokenSecret = "ArHMlt68DYmiGkblCBOXApC9L98Twbrf7qNwJn4Ap3mVR";
		$cb->setToken($AccessToken, $AccessTokenSecret);
		$params = array( 'status' => $tweet );
		$reply = $cb->statuses_update($params);
	}
	$conn->close();
?>
