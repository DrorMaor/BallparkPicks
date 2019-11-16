
<?php
	include("../DbConn.php");
	Get_NBA_Picks($conn);

	class NBA_TeamData {
		public $team;
		public $G;
		public $Pt2P;
		public $Pt3P;
		public $FTP;
		public $RB;
		public $STL;
		public $BLK;
		public $TOV;
		public $PF;
		public $P;

		function __construct ($team, $G, $Pt2P, $Pt3P, $FTP, $RB, $STL, $BLK, $TOV, $PF, $P)
		{
			$this->team = $team;
			$this->G = $G;
			$this->Pt2P = $Pt2P;
			$this->Pt3P = $Pt3P;
			$this->FTP = $FTP;
			$this->RB = $RB;
			$this->STL = $STL;
			$this->BLK = $BLK;
			$this->TOV = $TOV;
			$this->PF = $PF;
			$this->P = $P;
		}
	}

	function Get_NBA_Grade($team)
	{
		$grade = ($team->Pt2P * 1000);
		$grade += ($team->Pt3P * 1000);
		$grade += ($team->FTP * 1000);
		$grade += $team->RB;
		$grade += $team->STL;
		$grade += $team->BLK;
		$grade += $team->TOV;
		$grade -= ($team->PF * 2);
		$grade += $team->P;
		return $grade;
	}

	function Get_NBA_Score ($team)
	{
		if (strlen(trim($team->team)) == 3)
		{
			// average points per game
			$points = ceil($team->P / $team->G);
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
			if (sizeof($statsExplode) == 11)
			{
				$team = new NBA_TeamData("", "", "", "", "", "", "", "", "", "", "");
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
						case "G":
							$team->G = $val;
							break;
						case "Pt2P":
							$team->Pt2P = $val;
							break;
						case "Pt3P":
							$team->Pt3P = $val;
							break;
						case "FTP":
							$team->FTP = $val;
							break;
						case "RB":
							$team->RB = $val;
							break;
						case "STL":
							$team->STL = $val;
							break;
						case "BLK":
							$team->BLK = $val;
							break;
						case "TOV":
							$team->TOV = $val;
							break;
						case "PF":
							$team->PF = $val;
							break;
						case "P":
							$team->P = $val;
							break;
					}
				}
				array_push ($teams, $team);
			}
		}

		// get this week's games
		$sql = "select * from games where GameDate = '" . $GameDate . "' and league = 'NBA'; ";
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
					$sql.= " where id = ".$row['id'];
					$sql.= " and AwayScorePick is null and HomeScorePick is null ;  ";
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
