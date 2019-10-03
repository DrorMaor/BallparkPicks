<div>
	<form action="" method="post" name="frmAddGame">
		<select name="league">
			<option value="NFL">NFL</option>
			<option value="MLB">MLB</option>
			<option value="NBA">NBA</option>
			<option value="NHL">NHL</option>
		</select>
		<select name="AwayTeam">
			<?php
				$results = $conn->query("select * from teams order by league, city;");
				while ($row = $results->fetch_assoc())
					echo "<option value='".$row["code"]."'>".$row["city"]." ".$row["name"]."</option>";
			?>
		</select>
		@
		<select name="HomeTeam">
			<?php
				$results = $conn->query("select * from teams order by league, city;");
				while ($row = $results->fetch_assoc())
					echo "<option value='".$row["code"]."'>".$row["city"]." ".$row["name"]."</option>";
			?>
		</select>
		<input class="datepicker" style="width:100px;" type="text" name="GameDate" value="<?php echo date("Y-m-d"); ?>"> &nbsp;
		<input type="submit" value="Add Game" name="submitAddGame">
	</form>

<?php

	if (isset($_POST['submitAddGame']))
	{
		$sql = "insert into games (league, GameDate, AwayTeam, HomeTeam) ";
		$sql .= "values ('".$_POST['league']."', '".$_POST['GameDate']."', '".$_POST['AwayTeam'].", '".$_POST['HomeTeam']."')";
		$results = $conn->query($sql);
		echo "This game has been added:</br>";
		echo $sql;
	}
?>

</div>

<hr>
