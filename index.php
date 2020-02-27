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
		<title>Tzefi - Accurate Predictions</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="scripts.js"></script>
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<?php 
			include "DbConn.php";
			$traffic = "insert into traffic (IP, referer, URL) values ('". $_SERVER['REMOTE_ADDR'] . "', '" . $_SERVER['HTTP_REFERER'] . "', '" . $_SERVER['REQUEST_URI'] . "');";
			$conn->query($traffic);
			if ($_SERVER['REQUEST_URI'] != '/')
				mail('dror.m.maor@gmail.com', 'URI alert', $_SERVER['REQUEST_URI']);

			include "divs.php";
		?>
		<br>
		<img src="images/tzefi.png" />
		<br>
<!--
		<table>
			<tr>
				<td>
					<img src="images/banner.png" style="vertical-align: middle;">
				</td>
				<td>&nbsp;</td>
				<td>
					<div id="btnAbout" class="button">About</div>
					<div id="btnContact" class="button">Contact</div>
					<div id="btnExport" class="button">Export</div>
				</td>
				<td id="tdRecord">
					<?php // include "record.php"; ?>
				</td>

			</tr>
		</table>
-->
		</br>
		<div class="heading">
			Computerized predictions for 
			<?php
				$NYdate = new DateTime("now", new DateTimeZone('America/New_York') );
				echo $NYdate->format("l, F jS, Y");
			?>
		</div>
		</br>
<?php
	// NFL
	$WeekSQL = "select StartDate, EndDate, week from NFLweeks where curdate() between StartDate and EndDate; ";
	$weeks = $conn->query($WeekSQL);
	$week = $weeks->fetch_assoc();
	$title = "<div class='heading'>NFL Week " . $week["week"] . "</div>";
	$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
		from games g
			inner join teams away on away.code = g.AwayTeam and away.league = 'NFL'
			inner join teams home on home.code = g.HomeTeam and home.league = 'NFL'
		where g.league = 'NFL' and g.GameType <> '--'
			and GameDate between '" . $week["StartDate"] . "' and '" . $week["EndDate"] . "'
		order by g.GameDate, g.id	; ";
	drawGameHTML($conn, $GamesSQL, $title);



	// other leagues (with standard daily schedule)
	$leagues = array("MLB", "NBA", "NHL");
	foreach ($leagues as $league)
	{
		$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
			from games g
				inner join teams away on away.code = g.AwayTeam and away.league = '$league'
				inner join teams home on home.code = g.HomeTeam and home.league = '$league'
			where g.GameDate = curdate() and g.GameType <> '--' and g.league = '$league'; ";
		drawGameHTML($conn, $GamesSQL, "<div class='heading'>$league</div>");
	}



	$conn->close();


	// ----------------------- //


	function drawGameHTML($conn, $GamesSQL, $title)
	{
		$games = $conn->query($GamesSQL);
		if ($games->num_rows > 0)
		{
			$counter = 0;
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

				if ($counter % 4 == 0 && $counter > 0)
				{
					$counter = 0;
					$HTML .= "<tr> ";
				}
				$GameOverStyle = "";
				if ($game["league"] == "NFL" && $game["HomeScoreActual"] != "" && $game["AwayScoreActual"] != "")
					$GameOverStyle = " background-color:#dbdbdb;";
				$GameTodayStyle = "";
				if ($game["league"] == "NFL" && $game["GameDate"] == date("Y-m-d") && $game["HomeScoreActual"] == "" && $game["AwayScoreActual"] == "")
					$GameTodayStyle = " border: 2px gray solid;";
				$HTML .= "<td class='game' style='" . $GameOverStyle . $GameTodayStyle."'>";
				$HTML .= "<table style='width:100%;'>";
				$HTML .= "<tr>";
				$HTML .= "	<td> <img class='logo' src='logos/".$game['league']."/".$game["AwayTeam"].".png'></td>";
				$HTML .= "	<td class='".$awayClass."'>".trim($game["AwayTeamName"])."</td>";
				$HTML .= "	<td>&nbsp;</td>";
				$HTML .= "	<td class='".$awayClass." score'>".$game["AwayScorePick"];
				$HTML .= "</tr>";
				$HTML .= "<tr>";
				$HTML .= "	<td> <img class='logo' src='logos/".$game['league']."/".$game["HomeTeam"].".png'></td>";
				$HTML .= "	<td class='".$homeClass."'>".trim($game["HomeTeamName"])."</td>";
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
	}

?>
	</body>
</html>
