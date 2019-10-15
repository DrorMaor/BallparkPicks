<div>
	<form action="" method="post" name="frmPick">
		Week:
		<select name="week">
			<?php
				for ($i=1; $i<=17; $i++)
					echo "<option value='$i'>$i</option>";
			?>
		</select>
		&nbsp;
		<input type="submit" value="Generate NFL Picks" name="submitNFLpicks">
	</form>

<?php
	class NFL_TeamData {
		public $team;

		// offense stats
		public $o_P;
		public $o_F;
		public $o_p_C;
		public $o_p_A;
		public $o_p_Y;
		public $o_p_TD;
		public $o_p_I;
		public $o_p_FD;
		public $o_r_A;
		public $o_r_Y;
		public $o_r_TD;
		public $o_r_FD;
		public $o_n_N;
		public $o_n_Y;
		public $o_n_FD;

		// defense stats
		public $d_P;
		public $d_F;
		public $d_p_C;
		public $d_p_A;
		public $d_p_Y;
		public $d_p_TD;
		public $d_p_I;
		public $d_p_FD;
		public $d_r_A;
		public $d_r_Y;
		public $d_r_TD;
		public $d_r_FD;
		public $d_n_N;
		public $d_n_Y;
		public $d_n_FD;

		function __construct ($team, $o_P, $o_F, $o_p_C, $o_p_A, $o_p_Y, $o_p_TD, $o_p_I, $o_p_FD, $o_r_A, $o_r_Y, $o_r_TD, $o_r_FD, $o_n_N, $o_n_Y, $o_n_FD, $d_P, $d_F, $d_p_C, $d_p_A, $d_p_Y, $d_p_TD, $d_p_I, $d_p_FD, $d_r_A, $d_r_Y, $d_r_TD, $d_r_FD, $d_n_N, $d_n_Y, $d_n_FD)
		{
			$this->team = $team;
			// offense stats
			$this->o_P = $o_P;
			$this->o_F = $o_F;
			$this->o_p_C = $o_p_C;
			$this->o_p_A = $o_p_A;
			$this->o_p_Y = $o_p_Y;
			$this->o_p_TD = $o_p_TD;
			$this->o_p_I = $o_p_I;
			$this->o_p_FD = $o_p_FD;
			$this->o_r_A = $o_r_A;
			$this->o_r_Y = $o_r_Y;
			$this->o_r_TD = $o_r_TD;
			$this->o_r_FD = $o_r_FD;
			$this->o_n_N = $o_n_N;
			$this->o_n_Y = $o_n_Y;
			$this->o_n_FD = $o_n_FD;

			// defense stats
			$this->d_P = $d_P;
			$this->d_F = $d_F;
			$this->d_p_C = $d_p_C;
			$this->d_p_A = $d_p_A;
			$this->d_p_Y = $d_p_Y;
			$this->d_p_TD = $d_p_TD;
			$this->d_p_I = $d_p_I;
			$this->d_p_FD = $d_p_FD;
			$this->d_r_A = $d_r_A;
			$this->d_r_Y = $d_r_Y;
			$this->d_r_TD = $d_r_TD;
			$this->d_r_FD = $d_r_FD;
			$this->d_n_N = $d_n_N;
			$this->d_n_Y = $d_n_Y;
			$this->d_n_FD = $d_n_FD;
		}
	}

	function Get_NFL_Grade($team)
	{
		// offense
		$grade =  $team->o_P * 10;
		$grade -= $team->o_F * 5;
		if ($team->o_p_A > 0)
			$grade += ($team->o_p_C / $team->o_p_A) * 1000;
		$grade += $team->o_p_Y;
		$grade += $team->o_p_TD * 10;
		$grade -= $team->o_p_I * 5;
		$grade += $team->o_p_FD * 3;
		$grade += $team->o_r_Y;
		$grade += $team->o_r_TD * 10;
		$grade -= $team->o_n_N * 5;
		$grade -= $team->o_n_Y;
		$grade -= $team->o_n_FD * 3;

		// defense
		$grade -= $team->d_P * 10;
		$grade += $team->d_F * 5;
		if ($team->d_p_A > 0)
			$grade -= ($team->d_p_C / $team->d_p_A) * 1000;
		$grade -= $team->d_p_Y;
		$grade -= $team->d_p_TD * 10;
		$grade += $team->d_p_I * 5;
		$grade -= $team->d_p_FD * 3;
		$grade -= $team->d_r_Y;
		$grade -= $team->d_r_TD * 10;
		$grade -= $team->d_n_N * 5;
		$grade -= $team->d_n_Y;
		$grade -= $team->d_n_FD * 3;

		return ceil($grade);
	}

	function Get_NFL_Score ($team, $grade)
	{
		$games = $_POST["week"] -1; // (-1 means # of games played, we're not considering Bye weeks)
		if ($games == 0)  // in order not to divide by zero
			$games = 1;
		$TD = $team->o_p_TD + $team->o_r_TD;
		$score = ($TD / $games) * 7;
		$FG = (($team->o_P / $games) - $score) / 3;
		$score += $FG * 3;
		if ($grade >=0)
			$score = (rand(85, 125) / 100) * $score;
		else
			$score = (rand(65, 105) / 100) * $score;
		$score = ceil($score);
		// take care of "unusual/irregular" scores
		if ($score >=50)
			$score = 49;
		switch ($score)
		{
			case 1:
			case 2:
				$score = 0;
				break;
			case 4:
			case 5:
				$score = 3;
				break;
		}
		return $score;
	}

	if (isset($_POST['submitNFLpicks']))
	{
		// get stats of all teams
		$fullData = shell_exec("python py/NFL.py");
		$fullData = str_replace("{", "", $fullData);
		$teams = [];
		$stats = explode("}", $fullData);

		foreach ($stats as $stat)
		{
			$statsExplode = explode(", ", $stat);
			if (sizeof($statsExplode) == 31)
			{
				$team = new NFL_TeamData("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
				foreach ($statsExplode as $statsEach)
				{
					$statsEachSplit = explode(":", $statsEach);
					$key = str_replace("'", "", $statsEachSplit[0]);
					$val = str_replace("'", "", $statsEachSplit[1]);
					switch ($key)
					{
						case "team":
							$team->team = $val;
							break;

						// offense
						case "o_P":
							$team->o_P = $val;
							break;
						case "o_F":
							$team->o_F = $val;
							break;
						case "o_p_C":
							$team->o_p_C = $val;
							break;
						case "o_p_A":
							$team->o_p_A = $val;
							break;
						case "o_p_Y":
							$team->o_p_Y = $val;
							break;
						case "o_p_TD":
							$team->o_p_TD = $val;
							break;
						case "o_p_I":
							$team->o_p_I = $val;
							break;
						case "o_p_FD":
							$team->o_p_FD = $val;
							break;
						case "o_r_A":
							$team->o_r_A = $val;
							break;
						case "o_r_Y":
							$team->o_r_Y = $val;
							break;
						case "o_r_TD":
							$team->o_r_TD = $val;
							break;
						case "o_r_FD":
							$team->o_r_FD = $val;
							break;
						case "o_n_N":
							$team->o_n_N = $val;
							break;
						case "o_n_Y":
							$team->o_n_Y = $val;
							break;
						case "o_n_FD":
							$team->o_n_FD = $val;
							break;

						// defense
						case "d_P":
							$team->d_P = $val;
							break;
						case "d_F":
							$team->d_F = $val;
							break;
						case "d_p_C":
							$team->d_p_C = $val;
							break;
						case "d_p_A":
							$team->d_p_A = $val;
							break;
						case "d_p_Y":
							$team->d_p_Y = $val;
							break;
						case "d_p_TD":
							$team->d_p_TD = $val;
							break;
						case "d_p_I":
							$team->d_p_I = $val;
							break;
						case "d_p_FD":
							$team->d_p_FD = $val;
							break;
						case "d_r_A":
							$team->d_r_A = $val;
							break;
						case "d_r_Y":
							$team->d_r_Y = $val;
							break;
						case "d_r_TD":
							$team->d_r_TD = $val;
							break;
						case "d_r_FD":
							$team->d_r_FD = $val;
							break;
						case "d_n_N":
							$team->d_n_N = $val;
							break;
						case "d_n_Y":
							$team->d_n_Y = $val;
							break;
						case "d_n_FD":
							$team->d_n_FD = $val;
							break;
					}
				}
				array_push ($teams, $team);
			}
		}

		// get this week's games
		include ("../DbConn.php");
		$sql = "
			select g.id, AwayTeam.AwayTeam, HomeTeam.HomeTeam from games g 
				inner join NFLweeks w 
					on (g.GameDate >= w.StartDate and g.GameDate <= w.EndDate) 
				inner join (select league, code, concat(city, ' ', name) AwayTeam from teams) AwayTeam 
					on AwayTeam.code = g.AwayTeam
				inner join (select league, code, concat(city, ' ', name) HomeTeam from teams) HomeTeam 
					on HomeTeam.code = g.HomeTeam
			where g.league = 'NFL' and AwayTeam.league = 'NFL' and HomeTeam.league = 'NFL'
				and w.week = ".$_POST["week"].";  ";
		$results = $conn->query($sql);
		$update_multi_sql = "";
		while ($row = $results->fetch_assoc())
		{
			$awayTeam = "";
			$awayScore = "";
			$homeTeam = 0;
			$homeScore = 0;
			$awayTeamFound = 0;
			$homeTeamFound = 0;
			foreach ($teams as $team)
			{
				if (trim($team->team) == trim($row["AwayTeam"]))
				{
					$awayTeam = $team;
					$awayGrade = Get_NFL_Grade($team);
					$awayScore = Get_NFL_Score($team, $awayGrade);
					$awayTeamFound = 1;
				}
				if (trim($team->team) == trim($row["HomeTeam"]))
				{
					$homeTeam = $team;
					$homeGrade = Get_NFL_Grade($team);
					$homeScore = Get_NFL_Score($team, $homeGrade);
					$homeTeamFound = 1;
				}
				if ($awayTeamFound == 1 && $homeTeamFound == 1)
				{
					$sql = " update games set AwayScorePick = ".$awayScore.", HomeScorePick = ".$homeScore;
					$sql.= " where id = ".$row['id'];
					$sql.= " and AwayScorePick is null and HomeScorePick is null ;  ";
					$update_multi_sql .= $sql;
					break;
				}
			}
		}
		$conn->multi_query($update_multi_sql);
		echo "These NFL games have been updated:</br>";
		echo str_replace(';', ';</br>', $update_multi_sql);
		$conn->close();
	}
?>

</div>

<hr>
