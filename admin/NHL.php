
<?php
	class NHL_TeamData {
		public $team;
		public $W;
		public $L;
		public $P;
		public $G;
		public $GA;

		function __construct ($team, $W, $L, $P, $G, $GA)
		{
			$this->team = $team;
			$this->W = $W;
			$this->L = $L;
			$this->P = $P;
			$this->G = $G;
			$this->GA = $GA;
		}
	}

	function Get_NHL_Grade($team)
	{
		$grade = $team->W * 10;
		$grade -= $team->L * 10;
		$grade += $team->P;
		$grade += $team->G * 3;
		$grade -=$team->GA * 3;
		return $grade;
	}

	function Get_NHL_Score ($team)
	{
		// average goals per game
		$games = $team->W + $team->L;
		if ($games == 0)
			$games = 1;
		$goals = ceil($team->G / $games);
		$goals += rand(-$goals, $goals);
		return $goals;
	}

	function Get_NHL_Picks()
	{
		$GameDate = date("Y-m-d");
		if (isset($_POST['submitNHLpicks']))
			$GameDate = $_POST["PickDate"];

		// get stats of all teams
		$fullData = shell_exec("python py/NHL.py");
		$fullData = str_replace("{", "", $fullData);
		$teams = [];
		$stats = explode("}", $fullData);

		foreach ($stats as $stat)
		{
			$statsExplode = explode(", ", $stat);
			if (sizeof($statsExplode) == 6)
			{
				$team = new NHL_TeamData("", "", "", "", "", "");
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
						case "W":
							$team->W = $val;
							break;
						case "L":
							$team->L = $val;
							break;
						case "P":
							$team->P = $val;
							break;
						case "G":
							$team->G = $val;
							break;
						case "GA":
							$team->GA = $val;
							break;
					}
				}
				array_push ($teams, $team);
			}
		}

		// get this week's games
		$sql = "select * from games where GameDate = '" . $GameDate . "' and league = 'NHL'; ";
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
					$awayGrade = Get_NHL_Grade($team);
					$awayScore = Get_NHL_Score($team);
					$awayTeamFound = 1;
				}
				if (trim($team->team) == trim($row["HomeTeam"]))
				{
					$homeTeam = $team;
					$homeGrade = Get_NHL_Grade($team);
					$homeScore = Get_NHL_Score($team);
					$homeTeamFound = 1;
				}
				if ($awayTeamFound == 1 && $homeTeamFound == 1)
				{
					if ($awayScore == $homeScore)
					{
						if ($awayGrade > $homeGrade)
							$awayScore ++;
						else
							$homeScore ++;
					}
					$sql = " update games set AwayScorePick = ".$awayScore.", HomeScorePick = ".$homeScore;
					$sql.= " where id = ".$row['id'] . "; ";
					//$sql.= " and AwayScorePick is null and HomeScorePick is null ;  ";
					$update_multi_sql .= $sql;
					break;
				}
			}
		}
		$conn->multi_query($update_multi_sql);
		if (isset($_POST['submitNHLpicks']))
			{
			echo "These NHL games have been updated:</br>";
			echo str_replace(';', ';</br>', $update_multi_sql);
		}
	}
?>
