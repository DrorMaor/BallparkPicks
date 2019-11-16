<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	include("../DbConn.php");
	// SQL for MNF & TNF (sunday is different, later on)
	$sql = "select AwayTeam.name as AwayTeam, g.AwayScorePick, HomeTeam.name as HomeTeam, g.HomeScorePic$
						from games g
							   inner join teams AwayTeam on AwayTeam.code = g.AwayTeam and AwayTeam.league $
							   inner join teams HomeTeam on HomeTeam.code = g.HomeTeam and HomeTeam.league $
							   inner join NFLweeks w on g.GameDate between w.StartDate and w.EndDate
						where g.league = 'NFL' and g.GameDate = curdate()
						order by g.id ; ";
	$tweet = "";
	$weekday = date("w");

	switch ($weekday)
	{
		case 4: // thursday
			$tweet = "Here is the #NFL pick for #TNF:\r\n\r\n";
			break;
		case 1: // monday
			$tweet = "Here is the #NFL pick for #MNF:\r\n\r\n";
			break;
		case 0: // regular Sunday
			$tweet = "Here are some #NFL picks for today:\r\n\r\n";
			$sql = "select AwayTeam.name as AwayTeam, g.AwayScorePick, HomeTeam.name as HomeTeam, g.HomeScorePick
						from games g
						inner join teams AwayTeam on AwayTeam.code = g.AwayTeam and AwayTeam.league = 'NFL'
						inner join teams HomeTeam on HomeTeam.code = g.HomeTeam and HomeTeam.league = 'NFL'
						inner join NFLweeks w on g.GameDate between w.StartDate and w.EndDate
					where g.league = 'NFL' and curdate() between w.StartDate and w.EndDate and curdate() = g.GameDate
					order by g.id limit 3; ";
			break;
	}
	$results = $conn->query($sql) or die($conn->error);
	if ($results->num_rows >0)
	{
		while ($row = $results->fetch_assoc())
		{
			$tweet.= $row["AwayTeam"]." ".$row["AwayScorePick"]."\r\n";
			$tweet.= $row["HomeTeam"]." ".$row["HomeScorePick"]."\r\n\r\n";
		}

		if ($weekday == 0)
			$tweet.= "\r\nThe rest can be found on ";

		$tweet.= "www.BallparkPicks.com";

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

		//echo $tweet;
	}
	$conn->close();
?>
