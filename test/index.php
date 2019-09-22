<html>
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-20157082-8"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-20157082-8');
		</script>
		<title>Ballpark Picks</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<table>
			<tr>
				<td>
					<img src="banner.png" style="vertical-align: middle;">
				</td>
				<td>
					<div class="button">About</div>
					<div class="button">Contact</div>
					<div class="button">Export</div>
				</td>
			</tr>
		</table>
		
		</br>
		<div class="heading">
			Computerized picks for games on
			<?php 
				$NYdate = new DateTime("now", new DateTimeZone('America/New_York') );
				echo $NYdate->format("l, F jS, Y");
			?>
		</div>
		</br>
		<table>
			<tr>
				<td>
<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	include "DbConn.php";

	// NFL
	$WeekSQL = "select StartDate, EndDate, week from NFLweeks where curdate() between StartDate and EndDate; ";
	$weeks = $conn->query($WeekSQL);
	$week = $weeks->fetch_assoc();
	$title = "<div class='heading'>NFL Week " . $week["week"] . "</div>";
	$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
		from games g
			inner join teams away on away.code = g.AwayTeam and away.league = 'NFL'
			inner join teams home on home.code = g.HomeTeam and home.league = 'NFL'
		where g.league = 'NFL'
			and GameDate between '" . $week["StartDate"] . "' and '" . $week["EndDate"] . "' ; ";
	drawGameHTML($conn, $GamesSQL, $title);
	

	// MLB
	$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
		from games g
			inner join teams away on away.code = g.AwayTeam and away.league = 'MLB'
			inner join teams home on home.code = g.HomeTeam and home.league = 'MLB'
		where g.GameDate = curdate() and g.league = 'MLB'; ";
	drawGameHTML($conn, $GamesSQL, "<div class='heading'>MLB</div>");


	$conn->close();


	// ----------------------- //


	function drawGameHTML($conn, $GamesSQL, $title)
	{
		$games = $conn->query($GamesSQL);
		$counter = 1;
		$HTML = "<table> ";
		$HTML .= "<tr><td colspan='4' style='text-align:center;'>".$title."</td></tr>";
		while ($game = $games->fetch_assoc())
		{
			if ($game["AwayScorePick"] > $game["HomeScorePick"])
			{
				$awayClass = "team winner";
				$homeClass = "team";
			}
			elseif ($game["HomeScorePick"] > $game["AwayScorePick"])
			{
				$homeClass = "team winner";
				$awayClass = "team";
			}
			else
			{
				$homeClass= "team";
				$awayClass= "team";
			}

			if ($counter % 5 == 0 && $counter > 1)
			{
				$counter = 1;
				$HTML .= "<tr> ";
			}
			$HTML .= "<td class='game'>";
			$HTML .= "<table style='width:100%;'>";
			$HTML .= "<tr>";
			$HTML .= "	<td> <img class='logo' src='logos/".$game['league']."/".$game["AwayTeam"].".png'></td>";
			$HTML .= "	<td class='".$awayClass."'> ".trim($game["AwayTeamName"])."</td>";
			$HTML .= "	<td>&nbsp;</td>";
			$HTML .= "	<td class='".$awayClass." score'>".$game["AwayScorePick"];
			$HTML .= "</tr>";
			$HTML .= "<tr>";
			$HTML .= "	<td> <img class='logo' src='logos/".$game['league']."/".$game["HomeTeam"].".png'></td>";
			$HTML .= "	<td class='".$homeClass."'> ".trim($game["HomeTeamName"])."</td>";
			$HTML .= "	<td>&nbsp;</td>";
			$HTML .= "	<td class='".$homeClass." score'>".$game["HomeScorePick"]."</td>";
			$HTML .= "</tr>";
			$HTML .= "</table>";
			$HTML .= "</td>";
			if ($counter % 5 == 0 && $counter > 1)
				$HTML .= "</tr> ";
			$counter++;
		}
		$HTML .= "</table> <br>";
		echo $HTML;
	}

?>
				</td>
				<td>
					<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- BallparkPicks -->
					<ins class="adsbygoogle"
						 style="display:block"
						 data-ad-client="ca-pub-9172347417963561"
						 data-ad-slot="2097354615"
						 data-ad-format="auto"
						 data-full-width-responsive="true"></ins>
					<script>
						 (adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</td>
			</tr>
		</table>
	</body>
</html>