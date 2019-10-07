<div>
	<form action="" method="post" name="frmAddGame">
		<select name="league">
			<?php
				$results = $conn->query("select * from terms where type='league' order by id;");
				while ($row = $results->fetch_assoc())
					echo "<option value='".$row["code"]."'>".$row["code"]."</option>";
			?>
		</select>
		<select name="GameType">
			<?php
				$results = $conn->query("select * from terms where type='game' order by id;");
				while ($row = $results->fetch_assoc())
				{
					$selected = ($row["code"] == "PO") ? " selected " : "";
					echo "<option value='".$row["code"]."'".$selected.">".$row["name"]."</option>";
				}
			?>
		</select>
		<select name="AwayTeam">
			<?php
				$results = $conn->query("select * from teams order by league, city;");
				while ($row = $results->fetch_assoc())
					echo "<option value='".$row["code"]."'>(".$row["league"].") ".$row["city"]." ".$row["name"]."</option>";
			?>
		</select>
		@
		<select name="HomeTeam">
			<?php
				$results = $conn->query("select * from teams order by league, city;");
				while ($row = $results->fetch_assoc())
					echo "<option value='".$row["code"]."'>(".$row["league"].") ".$row["city"]." ".$row["name"]."</option>";
			?>
		</select>
		<input class="datepicker" style="width:100px;" type="text" name="GameDate" value="<?php echo date("Y-m-d"); ?>"> &nbsp;
		<input type="submit" value="Add Game" name="submitAddGame">
	</form>

<?php

	if (isset($_POST['submitAddGame']))
	{
		$sql = "insert into games (league, GameType, GameDate, AwayTeam, HomeTeam) ";
		$sql .= "values ('".$_POST['league']."', '".$_POST['GameType']."', '".$_POST['GameDate']."', '".$_POST['AwayTeam']."', '".$_POST['HomeTeam']."'); ";
		$results = $conn->query($sql);
		echo "This game has been added:</br>";
		echo $sql;
	}
?>

</div>

<hr>
