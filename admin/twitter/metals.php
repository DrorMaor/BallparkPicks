<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include("../../DbConn.php");
        $sql  = "select t.name name, m.rate, m.UpDown ";
        $sql .= "from metals m ";
        $sql .= " inner join terms t on t.code = m.name ";
	$sql .= "where m.theDate = curdate(); ";

	$results = $conn->query($sql) or die($conn->error);
	if ($results->num_rows >0)
	{
		$hashtags = "";
		$tweet = "Here are some #PreciousMetals predictions for today:\r\n\r\n";
		while ($row = $results->fetch_assoc())
		{
			$tweet .= $row['name'] . ": $" . round($row["rate"],2) . " ";
			$tweet .= "(".(($row["UpDown"] == "UP") ? "+" : "-") . ")\r\n";
		}
		$tweet.= "\r\nThe rest can be found on https://www.tzefi.com \r\n\r\n";
		$tweet.= "$"."gold"."$"."silver #platinum";

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
	}
	$conn->close();
?>
