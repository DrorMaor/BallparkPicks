
<?php
	include("../DbConn.php");
	Get_NBA_Picks($conn);

	class NBA_TeamData {
		public $team;
		public $W;
		public $L;
		public $PSG;
		public $PAG;

		function __construct ($team, $W, $L, $PSG, $PAG)
		{
			$this->team = $team;
			$this->W = $W;
			$this->L = $L;
			$this->PSG = $PSG;
			$this->PAG = $PAG;
		}
	}

	function Get_NBA_Grade($team)
	{
		$grade = ($team->W * 10) - ($team->L * 10);
		$grade += $team->PSG - $team->PAG;
		return $grade;
	}

	function Get_NBA_Score ($team)
	{
		if (strlen(trim($team->team)) == 3)
		{
			$points = ceil($team->PSG);
			$points += rand(-10, 10);
			if ($points >= 130)
				$points -= rand(3, 5);
			return $points;
		}
	}

	function Get_NBA_Picks($conn)
	{
		$GameDate = date("Y-m-d");
		if (isset($_POST['submitNBApicks']))
			$GameDate = $_POST["PickDate"];

		// get stats of all teams
		$fullData = shell_exec("python py/NBA.py");
		$fullData = str_replace("{", "", $fullData);
		$teams = [];
		$stats = explode("}", $fullData);

		foreach ($stats as $stat)
		{
			$statsExplode = explode(", ", $stat);
			if (sizeof($statsExplode) == 5)
			{
				$team = new NBA_TeamData("", "", "", "", "");
				foreach ($statsExplode as $statsEach)
				{
					$statsEachSplit = explode(":", $statsEach);
					$key = trim(str_replace("'", "", $statsEachSplit[0]));
					$val = trim(str_replace("'", "", $statsEachSplit[1]));
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
						case "PSG":
							$team->PSG = $val;
							break;
						case "PAG":
							$team->PAG = $val;
							break;
					}
				}
				array_push ($teams, $team);
			}
		}

		// get this week's games
		$sql = "select * from games where GameDate = '" . $GameDate . "' and league = 'NBA' and GameType = 'RS'; ";
		$results = $conn->query($sql) or die($conn->error);
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
					$awayGrade = Get_NBA_Grade($team);
					$awayScore = Get_NBA_Score($team);
					$awayTeamFound = 1;
				}
				if (trim($team->team) == trim($row["HomeTeam"]))
				{
					$homeTeam = $team;
					$homeGrade = Get_NBA_Grade($team);
					$homeScore = Get_NBA_Score($team);
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
					$sql.= " where id = ".$row['id']."; ";
					//$sql.= " and AwayScorePick is null and HomeScorePick is null ;  ";
					$update_multi_sql .= $sql;
					break;
				}
			}
		}

		$result = $conn->multi_query($update_multi_sql);
		if (isset($_POST['submitNBApicks']))
		{
			echo "These NBA games have been updated:</br>";
			echo str_replace(';', ';</br>', $update_multi_sql);
		}
	}
	$conn->close();
?>
