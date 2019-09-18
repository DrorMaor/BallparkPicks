<form action="" method="post" name="frmShowGamesByDate">
	<input type="text" name="ActualDate" 
		value="<?php echo date("Y-m-d", strtotime("-1 days")) ?>">	&nbsp;
	<input type="submit" value="Show Games By Date" name="submitShowGamesByDate">
</form>
<style>
	.score {
		width:40px;
	}
	.td {
		text-align:center;
	}
</style>

<?php
	include ("../DbConn.php");

	// DISPLAY THE DATE'S SCORES
	if (isset($_POST['submitShowGamesByDate']))
	{
		?>
		<form action="" method="post" name="frmAddActualScores">
			<?php echo $_POST['ActualDate']; ?>
			<table>
				<tr>
					<th>id</th>
					<th>league</th>
					<th>Away</th>
					<th>Home</th>
					<th>Score</th>
					<th>Score</th>
				</tr>
			<?php
			$sql = "select * from games
				where GameDate = '".$_POST['ActualDate']."'
				and AwayScoreActual is null and HomeScoreActual is null
				order by league ; ";
			$results = $conn->query($sql);
			while ($row = $results->fetch_assoc())
			{
				$league = $row["league"];
				$awayTeam = $row["AwayTeam"];
				$homeTeam = $row["HomeTeam"];
				echo "<tr>";
				echo "<td class='td'>" . $row["id"] . "</td>";
				echo "<td class='td'>" . $league . "</td>";
				echo "<td class='td'> <img style='height:25px;' src='/logos/".$league."/".$awayTeam.".png'> &nbsp;".$awayTeam."</td>";
                                echo "<td class='td'> <img style='height:25px;' src='/logos/".$league."/".$homeTeam.".png'> &nbsp;".$homeTeam."</td>";
				echo "<td class='td'><input class='score' name='away_".$row["id"]."' type='text'" . $row["AwayScoreActual"] . "></td>";
				echo "<td class='td'><input class='score' name='home_".$row["id"]."' type='text'" . $row["HomeScoreActual"] . "></td>";
				echo "</tr>";
			}
			?>
			</table>
			<input type="submit" value="Update Actual Scores" name="submitActualScores">
		</form>
		<hr>
		<?php
	}



	// UPDATE THE ACTUAL SCORES
	if (isset($_POST['submitActualScores']))
	{
		$multi_sql = "";
		$AwayScoreActual = "";
		$HomeScoreActual = "";
		foreach($_POST as $key => $value)
		{
			$keySplit = explode("_", $key);
			if (sizeof($keySplit) == 2)
			{
				$id = $keySplit[1];
				if ($keySplit[0] == 'away')
					$AwayScoreActual = $value;
				else
					$HomeScoreActual = $value;

				// this means we have both away & home scores
				if ($AwayScoreActual != "" && $HomeScoreActual != "")
				{
					$sql = "update games 
							set AwayScoreActual = ".$AwayScoreActual.", 
								HomeScoreActual = ".$HomeScoreActual." 
							where id = ".$id." 
								and AwayScoreActual is null and HomeScoreActual is null; ";
					$multi_sql.= $sql;
					$AwayScoreActual = "";
					$HomeScoreActual = "";
				}
			}
		}
		echo "<br>".str_replace(';', ';<br>', $multi_sql);
		$conn->multi_query($multi_sql);
	}
?>
