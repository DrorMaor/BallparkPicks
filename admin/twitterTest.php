<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include("../DbConn.php");
	$league = $_GET["league"];
	$sql = "
		select AwayTeam.name as AwayTeam, g.AwayScorePick, HomeTeam.name as HomeTeam, g.HomeScorePick from games g
			inner join teams AwayTeam on AwayTeam.code = g.AwayTeam and AwayTeam.league = '" . $league . "'
			inner join teams HomeTeam on HomeTeam.code = g.HomeTeam and HomeTeam.league = '" . $league . "'
		where g.GameDate = curdate() and g.league = '" . $league . "' order by g.id limit 3; ";
	$results = $conn->query($sql) or die($conn->error);
	if ($results->num_rows >0)
	{
		$hashtags = "";
		$tweet = "Here are some #" . $league . " picks for today:\r\n\r\n";
		while ($row = $results->fetch_assoc())
		{
			$awayScorePick = $row["AwayScorePick"];
			$homeScorePick = $row["HomeScorePick"];
			$tweet.= $row["AwayTeam"]." ".$awayScorePick;
			$hashtags.= "#". str_replace(" ", "", $row["AwayTeam"]) ." ";
			if ($awayScorePick > $homeScorePick)
				$tweet.=" !";
			$tweet.="\r\n";
			$tweet.= $row["HomeTeam"]." ".$homeScorePick;
                        $hashtags.= "#". str_replace(" ", "", $row["HomeTeam"]) ." ";
			if ($homeScorePick > $awayScorePick)
				$tweet.=" !";
			$tweet.="\r\n\r\n";
		}
		$tweet.= "\r\nThe rest can be found on https://www.tzefi.com \r\n\r\n";
		$tweet.= $hashtags;
		/*
		require_once('codebird.php');
		$ConsumerKey = "DJ5CrbI7bEv8IZXAW7h3U219Q";
		$ConsumerSecret = "46bcGUhHj9hSUVWcPky3k1nI85lTf7x6euu09RdJ10lbIbK2S9";
		\Codebird\Codebird::setConsumerKey($ConsumerKey, $ConsumerSecret);
		$cb = \Codebird\Codebird::getInstance();
		$AccessToken = "1231554667496329218-LAxlxwtKdN3dMymAY16cD2lvuiapqE";
		$AccessTokenSecret = "iHBtMCZTWklmxN19BYmaHHl4CniL8hfrm8Er3zWhxdRtO";
		$cb->setToken($AccessToken, $AccessTokenSecret);
		$params = array( 'status' => $tweet );
		$reply = $cb->statuses_update($params);
		*/
		echo $tweet;
	}
	$conn->close();
?>
