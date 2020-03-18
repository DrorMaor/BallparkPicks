<?php
	if ($_SERVER['REQUEST_URI'] != "/" && $_SERVER['REQUEST_URI'] != "/index1.php")
        	die();
?>

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
		<script data-ad-client="ca-pub-9172347417963561" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

		<title>Tzefi - Accurate Predictions</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="styles.css">

		<!-- make responsive -->
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="HandheldFriendly" content="true">

		<script>
			function appendTab(title) {
			        var tab = "<tab id='tab" + title + "' class='tabs heading' ";
			        tab += "onclick='$(\".tabs\").removeClass(\"activeTab\"); ";
			        tab += "$(\"#tab" + title + "\").addClass(\"activeTab\"); ";
			        tab += "$(\".tabContent\").hide(); $(\"#" + title + "\").fadeIn(200);' ";
			        tab += ">" + title + "</tab> ";
			        $("#tabs").append(tab);
			}

			$(document).ready(function()
			{
				$("#imgSwitch").click(function() {
					var base = $("#selBase").val();
                                        var quote = $("#selQuote").val();
					$("#selBase").val(quote);
					$("#selQuote").val(base);
				});

				$("#btnGetPick").click(function() {
					var base = $("#selBase").val();
					var quote = $("#selQuote").val();
					if (base == quote)
						alert("You have chosen the same currency");
					else
					{
                    			    $.ajax({
			                            type: "POST",
							url: "admin/OtherForex.php",
							data: "base=" + base + "&quote=" + quote,
							success: function(result) {
			                                	$("#divOtherForex").text(result);
                        			    	}
	                       			});
					}
				});
			});
		</script>
	</head>
	<body>
		<?php
			include "DbConn.php";
			$traffic = "insert into traffic (IP, referer, URL) values ('". $_SERVER['REMOTE_ADDR'] . "', '" . $_SERVER['HTTP_REFERER'] . "', '" . $_SERVER['REQUEST_URI'] . "');";
			$conn->query($traffic);
		?>
		<img src="images/tzefi.png" />
		&nbsp;
		<a href="https://twitter.com/tzefi2?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-size="large" data-show-screen-name="false" data-show-count="false">Follow @tzefi2</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
		<br>
		<div class="heading" style="padding-top:7px;">
			Computerized <span style="border:2px solid #0F1C46; border-radius:3px; padding:3px;">predictions</span> for
			<?php
				$NYdate = new DateTime("now", new DateTimeZone('America/New_York') );
				echo $NYdate->format("l, F jS, Y");
			?>
			<div style="font-size:12px; font-style: italic; padding-top:5px;">
				Disclaimer: This site is for informational use only. Our algorithms are not prophetic. Be careful if investing financially.
			</div>
			<div style="font-size:14px; color:red; font-weight:bold; padding-top:7px;">
				Due to the Coronovirus, both the NBA and NHL seasons have been suspended (as well as the beginning of the MLB season)
			</div>
		</div>
		<br>
		<div id="tabs"></div>
		<br>
		<div>&nbsp;</div>
		<br>
<?php
	// # of displayed divs
	// (the first one by default will be shown, but the others are hidden unless clicked)
	$numDisplayedDivs = 0;


	// NFL
	$WeekSQL = "select StartDate, EndDate, week from NFLweeks where curdate() between StartDate and EndDate; ";
	$weeks = $conn->query($WeekSQL);
	$week = $weeks->fetch_assoc();
	$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
		from games g
			inner join teams away on away.code = g.AwayTeam and away.league = 'NFL'
			inner join teams home on home.code = g.HomeTeam and home.league = 'NFL'
		where g.league = 'NFL' and g.GameType <> '--'
			and GameDate between '" . $week["StartDate"] . "' and '" . $week["EndDate"] . "'
		order by g.GameDate, g.id	; ";
	drawGameHTML($conn, $GamesSQL, "NFL Week " . $week["week"]);



	// other leagues (with standard daily schedule)
	$leagues = array("MLB", "NBA", "NHL");
	foreach ($leagues as $league)
	{
		$GamesSQL = "select g.*, away.name as AwayTeamName, home.name as HomeTeamName
			from games g
				inner join teams away on away.code = g.AwayTeam and away.league = '$league'
				inner join teams home on home.code = g.HomeTeam and home.league = '$league'
			where g.GameDate = curdate() and g.GameType not in ('CV', '--') and g.league = '$league'; ";
		drawGameHTML($conn, $GamesSQL, $league);
	}

	drawIndexesHTML($conn);
	drawForexHTML($conn);
	drawMetalsHTML($conn);

	$conn->close();



	// ----------------------- //



	function drawGameHTML($conn, $GamesSQL, $title)
	{
		$games = $conn->query($GamesSQL);
		if ($games->num_rows > 0)
		{
			echo "<script> appendTab('".$title."'); </script>";
			$GLOBALS['numDisplayedDivs'] ++ ;

			$counter = 0;
			$HTML = "<div class='tabContent' id='".$title."' ";
			if ($GLOBALS['numDisplayedDivs'] == 1)
				$HTML.="style='display:inline;'";
			else
				$HTML.="style='display:none;'";
			$HTML.=">";

			$HTML .= "<table>";
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
			$HTML .= "</table> </div>";
			echo $HTML;
			if ($GLOBALS['numDisplayedDivs'] == 1)
				echo "<script> $('#tab".$title."').addClass('activeTab'); </script> ";
		}
	}


	function drawForexHTML($conn)
	{
		$SQL = $conn->query("select * from forex order by id desc limit 7;"); // this will always show the last Major 7, even on non transaction days
		echo "<script> appendTab('Forex'); </script>";
		$GLOBALS['numDisplayedDivs'] ++ ;

		$counter = 0;
		$HTML = "<div class='tabContent' id='Forex' ";
		if ($GLOBALS['numDisplayedDivs'] == 1)
			$HTML.="style='display:inline;'";
		else
			$HTML.="style='display:none;'";
		$HTML .= ">";

		$HTML .= "<table>";
		while ($row = $SQL->fetch_assoc())
			$rows[] = $row;
		$rows = array_reverse($rows, true);
		foreach ($rows as $row)
		{
			if ($counter % 4 == 0 && $counter > 0)
			{
				$counter = 0;
				$HTML .= "<tr> ";
			}
			$HTML .= "<td class='game'>";
			$HTML .= " <table>";
			$HTML .= "  <tr>";
			$HTML .= "   <td>";
			$HTML .= "    <img class='flag' src='logos/flags/".$row["base"].".png'> <br>";
			$HTML .= "    <img class='flag' src='logos/flags/".$row["quote"].".png'>";
			$HTML .= "   </td>";
			$HTML .= "   <td>";
			$HTML .= "    <table style='width:100%;'>";
			$HTML .= "     <tr>";
			$HTML .= "      <td class='team'>".$row['base']."/".$row['quote']."</td>";
			$HTML .= "     </tr>";
			$HTML .= "     <tr>";
			$HTML .= "      <td class='team" . (($row["UpDown"] == "UP") ? " winner" : "") . "'>";
			$HTML .=         $row["rate"] . " &nbsp;";
			$HTML .=         ($row["UpDown"] == "UP") ? "&uarr;" : "&darr;";
			$HTML .= "      </td>";
			$HTML .= "     </tr>";
			$HTML .= "    </table>";
			$HTML .= "   </td>";
			$HTML .= "  </tr>";
			$HTML .= " </table>";
			$HTML .= "</td>";

			if ($counter % 5 == 0 && $counter > 1)
				$HTML .= "</tr> ";
			$counter++;
		}
		$HTML .= "<td class='game team'>";
		$HTML .= AddAllForex($conn, 'Base');
		$HTML .= AddAllForex($conn, 'Quote');
		$HTML .= "<img class='image' id='imgSwitch' src='images/switch.png' alt='Switch Currencies'> &nbsp;";
		$HTML .= "<img class='image' id='btnGetPick' src='images/go.png' alt='Get Prediction'> &nbsp;";
		$HTML .= "<span id='divOtherForex'>";
		$HTML .= "<td> </table> </div>";
		echo $HTML;
		if ($GLOBALS['numDisplayedDivs'] == 1)
			echo "<script> $('#tabForex').addClass('activeTab'); </script> ";
	}

	function AddAllForex($conn, $id)
	{
		$HTML = "<select style='max-width:150px;' id = 'sel".$id."'>";
		$rows = $conn->query("select * from AllCurrencies order by full;");
		while ($row = $rows->fetch_assoc())
			$HTML .= "<option value='". $row["short"]. "'>".$row["full"]."</option>";
		$HTML.= "</select> <br>";
		return $HTML;
	}




        function drawIndexesHTML($conn)
        {
		$sql  = "select t.name name, i.name code, i.rate, i.UpDown ";
		$sql .= "from indexes i ";
		$sql .= " inner join terms t on t.code = i.name ";
		$sql .= "order by i.id desc limit 4; ";
                $SQL = $conn->query($sql); // this will always show the last set of indexes
                echo "<script> appendTab('Indexes'); </script>";
                $GLOBALS['numDisplayedDivs'] ++ ;

                $counter = 0;
                $HTML = "<div class='tabContent' id='Indexes' ";
                if ($GLOBALS['numDisplayedDivs'] == 1)
                        $HTML.="style='display:inline;'";
                else
                        $HTML.="style='display:none;'";
                $HTML .= ">";

                $HTML .= "<table>";
                while ($row = $SQL->fetch_assoc())
                        $rows[] = $row;
                $rows = array_reverse($rows, true);
                foreach ($rows as $row)
                {
                        if ($counter % 4 == 0 && $counter > 0)
                        {
                                $counter = 0;
                                $HTML .= "<tr> ";
                        }
                        $HTML .= "<td class='game'>";
                        $HTML .= " <table>";
                        $HTML .= "  <tr>";
                        $HTML .= "   <td>";
                        $HTML .= "    <table style='width:100%;'>";
                        $HTML .= "     <tr>";
                        $HTML .= "      <td class='team'>";
			$HTML .= "       <img src='logos/indexes/".$row['code'].".png' class='indexLogo'>";
			$HTML .= "       <br>".$row['name'];
			$HTML .= "      </td>";
                        $HTML .= "     </tr>";
                        $HTML .= "     <tr>";
                        $HTML .= "      <td class='team" . (($row["UpDown"] == "UP") ? " winner" : "") . "'>";
                        $HTML .=         $row["rate"] . " &nbsp;";
                        $HTML .=         ($row["UpDown"] == "UP") ? "&uarr;" : "&darr;";
                        $HTML .= "      </td>";
                        $HTML .= "     </tr>";
                        $HTML .= "    </table>";
                        $HTML .= "   </td>";
                        $HTML .= "  </tr>";
                        $HTML .= " </table>";
                        $HTML .= "</td>";

                        if ($counter % 5 == 0 && $counter > 1)
                                $HTML .= "</tr> ";
                        $counter++;
                }
                $HTML .= "</table> </div>";
                echo $HTML;
                if ($GLOBALS['numDisplayedDivs'] == 1)
                        echo "<script> $('#tabIndexes').addClass('activeTab'); </script> ";
        }


	function drawMetalsHTML($conn)
	{
		$sql  = "select t.name name, i.name code, i.rate, i.UpDown ";
		$sql .= "from metals i ";
		$sql .= " inner join terms t on t.code = i.name ";
		$sql .= "order by i.id desc limit 3; ";
		$SQL = $conn->query($sql); // this will always show the last set of metals
		echo "<script> appendTab('Metals'); </script>";
		$GLOBALS['numDisplayedDivs'] ++ ;

		$counter = 0;
		$HTML = "<div class='tabContent' id='Metals' ";
		if ($GLOBALS['numDisplayedDivs'] == 1)
			$HTML.="style='display:inline;'";
		else
			$HTML.="style='display:none;'";
		$HTML .= ">";

		$HTML .= "<table>";
		while ($row = $SQL->fetch_assoc())
			$rows[] = $row;
		$rows = array_reverse($rows, true);
		foreach ($rows as $row)
		{
			if ($counter % 4 == 0 && $counter > 0)
			{
				$counter = 0;
				$HTML .= "<tr> ";
			}
			$HTML .= "<td class='game'>";
			$HTML .= " <table>";
			$HTML .= "  <tr>";
			$HTML .= "   <td>";
			$HTML .= "    <table style='width:100%;'>";
			$HTML .= "     <tr>";
			$HTML .= "      <td class='team' style='font-weight:bold; color:".$row['name']."'>".$row['name']."</td>";
			$HTML .= "     </tr>";
			$HTML .= "     <tr>";
			$HTML .= "      <td class='team" . (($row["UpDown"] == "UP") ? " winner" : "") . "'>";
			$HTML .= "        $" . round($row["rate"],2) . " &nbsp;";
			$HTML .=         ($row["UpDown"] == "UP") ? "&uarr;" : "&darr;";
			$HTML .= "      </td>";
			$HTML .= "     </tr>";
			$HTML .= "    </table>";
			$HTML .= "   </td>";
			$HTML .= "  </tr>";
			$HTML .= " </table>";
			$HTML .= "</td>";

			if ($counter % 5 == 0 && $counter > 1)
				$HTML .= "</tr> ";
			$counter++;
		}
		$HTML .= "</table> </div>";
		echo $HTML;
		if ($GLOBALS['numDisplayedDivs'] == 1)
			echo "<script> $('#tabMetals').addClass('activeTab'); </script> ";
	}


?>
	</body>
</html>

