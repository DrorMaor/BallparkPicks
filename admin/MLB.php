<div>
	<form action="" method="post" name="frmPick">
		<input style="width:100px;" type="text" name="PickDate" value="<?php echo date("Y-m-d"); ?>"> &nbsp;
		<input type="submit" value="Generate MLB Picks" name="submitMLBpicks">
	</form>

<?php
	class MLB_TeamData {
		public $team;

		// batting stats
		public $b_RSPG;
		public $b_PA;
		public $b_AB;
		public $b_R;
		public $b_H;
		public $b_DBL;
		public $b_TRPL;
		public $b_HR;
		public $b_RBI;
		public $b_SB;
		public $b_CS;
		public $b_BB;
		public $b_SO;
		public $b_BA;
		public $b_OBP;
		public $b_SLG;
		public $b_OPS;
		public $b_OPSP;
		public $b_TB;
		public $b_GDP;
		public $b_HBP;
		public $b_SH;
		public $b_SF;
		public $b_IBB;
		public $b_LOB;

		// pitching stats
		public $p_RGPG;
		public $p_W;
		public $p_L;
		public $p_WLP;
		public $p_ERA;
		public $p_G;
		public $p_GS;
		public $p_GF;
		public $p_CG;
		public $p_tSho;
		public $p_cSho;
		public $p_SV;
		public $p_IP;
		public $p_H;
		public $p_R;
		public $p_ER;
		public $p_HR;
		public $p_BB;
		public $p_IBB;
		public $p_SO;
		public $p_HBP;
		public $p_BK;
		public $p_WP;
		public $p_BF;
		public $p_ERAP;
		public $p_FIP;
		public $p_WHIP;
		public $p_H9;
		public $p_HR9;
		public $p_BB9;
		public $p_SO9;
		public $p_SOW;
		public $p_LOB;

		function __construct ($team, $b_RSPG, $b_PA, $b_AB, $b_R, $b_H, $b_DBL, $b_TRPL, $b_HR, $b_RBI, $b_SB, $b_CS, $b_BB, $b_SO, $b_BA, $b_OBP, $b_SLG, $b_OPS, $b_OPSP, $b_TB, $b_GDP, $b_HBP, $b_SH, $b_SF, $b_IBB, $b_LOB, $p_RGPG, $p_W, $p_L, $p_WLP, $p_ERA, $p_G, $p_GS, $p_GF, $p_CG, $p_tSho, $p_cSho, $p_SV, $p_IP, $p_H, $p_R, $p_ER, $p_HR, $p_BB, $p_IBB, $p_SO, $p_HBP, $p_BK, $p_WP, $p_BF, $p_ERAP, $p_FIP, $p_WHIP, $p_H9, $p_HR9, $p_BB9, $p_SO9, $p_SOW, $p_LOB)
		{
			$this->team = $team;
			// batting stats
			$this->b_RSPG = $b_RSPG;
			$this->b_PA = $b_PA;
			$this->b_AB = $b_AB;
			$this->b_R = $b_R;
			$this->b_H = $b_H;
			$this->b_DBL = $b_DBL;
			$this->b_TRPL = $b_TRPL;
			$this->b_HR = $b_HR;
			$this->b_RBI = $b_RBI;
			$this->b_SB = $b_SB;
			$this->b_CS = $b_CS;
			$this->b_BB = $b_BB;
			$this->b_SO = $b_SO;
			$this->b_BA = $b_BA;
			$this->b_OBP = $b_OBP;
			$this->b_SLG = $b_SLG;
			$this->b_OPS = $b_OPS;
			$this->b_OPSP = $b_OPSP;
			$this->b_TB = $b_TB;
			$this->b_GDP = $b_GDP;
			$this->b_HBP = $b_HBP;
			$this->b_SH = $b_SH;
			$this->b_SF = $b_SF;
			$this->b_IBB = $b_IBB;
			$this->b_LOB = $b_LOB;

			// pitching stats
			$this->p_RGPG = $p_RGPG;
			$this->p_W = $p_W;
			$this->p_L = $p_L;
			$this->p_WLP = $p_WLP;
			$this->p_ERA = $p_ERA;
			$this->p_G = $p_G;
			$this->p_GS = $p_GS;
			$this->p_GF = $p_GF;
			$this->p_CG = $p_CG;
			$this->p_tSho = $p_tSho;
			$this->p_cSho = $p_cSho;
			$this->p_SV = $p_SV;
			$this->p_IP = $p_IP;
			$this->p_H = $p_H;
			$this->p_R = $p_R;
			$this->p_ER = $p_ER;
			$this->p_HR = $p_HR;
			$this->p_BB = $p_BB;
			$this->p_IBB = $p_IBB;
			$this->p_SO = $p_SO;
			$this->p_HBP = $p_HBP;
			$this->p_BK = $p_BK;
			$this->p_WP = $p_WP;
			$this->p_BF = $p_BF;
			$this->p_ERAP = $p_ERAP;
			$this->p_FIP = $p_FIP;
			$this->p_WHIP = $p_WHIP;
			$this->p_H9 = $p_H9;
			$this->p_HR9 = $p_HR9;
			$this->p_BB9 = $p_BB9;
			$this->p_SO9 = $p_SO9;
			$this->p_SOW = $p_SOW;
			$this->p_LOB = $p_LOB;
		}
	}

	function Get_MLB_Grade($team)
	{
		// batting
		$grade =  ($team->b_RSPG * 100);
		$grade += ($team->b_R);
		$grade += ($team->b_H);
		$grade += ($team->b_DBL * 10);
		$grade += ($team->b_TRPL * 20);
		$grade += ($team->b_HR * 50);
		$grade += ($team->b_RBI);
		$grade += ($team->b_SB * 10);
		$grade -= ($team->b_CS * 10);
		$grade += $team->b_BB;
		$grade -= $team->b_SO;
		$grade += ($team->b_BA * 1000);
		$grade += ($team->b_OBP * 1000);
		$grade += ($team->b_SLG * 1000);
		$grade += ($team->b_TB);
		$grade -= ($team->b_GDP * 25);
		$grade += ($team->b_HBP * 10);
		$grade += ($team->b_SH * 10);
		$grade += ($team->b_SF * 10);
		$grade += ($team->b_IBB * 10);
		$grade -= $team->b_LOB;

		// pitching
		$grade -= ($team->p_RGPG * 100);
		$grade += ($team->p_W * 20);
		$grade -= ($team->p_L * 20);
		$grade += ((1.00 - $team->p_ERA) * 100);
		$grade += ($team->p_CG * 20);
		$grade += ($team->p_tSho * 50);
		$grade += ($team->p_cSho * 75);
		$grade += ($team->p_SV * 10);
		$grade -= ($team->p_H);
		$grade -= ($team->p_R);
		$grade -= ($team->p_HR * 50);
		$grade -= $team->p_BB;
		$grade -= $team->p_IBB;
		$grade += $team->p_SO;
		$grade -= ($team->p_HBP * 5);
		$grade -= ($team->p_BK * 10);
		$grade -= ($team->p_WP * 10);
		$grade -= ($team->p_FIP * 100);
		$grade -= ($team->p_WHIP * 100);
		$grade += $team->p_LOB;

		return ceil($grade);
	}

	function Get_MLB_Score ($team)
	{
		$runFactor = (rand(0, 1) == 0) ? 8 : 7;
		$runs = ceil($team->b_RSPG + rand(0, $runFactor) * $team->p_WLP);
		$runs += ceil(rand(-$runFactor, 0) * (1.00 - $team->p_WLP) );
		if ($runs < 0)
			$runs = 0;
		return $runs;
	}

	if (isset($_POST['submitMLBpicks']))
	{
		// get stats of all teams
		$fullData = shell_exec("python py/MLB.py");
		$fullData = str_replace("{", "", $fullData);
		$teams = [];
		$stats = explode("}", $fullData);

		foreach ($stats as $stat)
		{
			$statsExplode = explode(", ", $stat);
			if (sizeof($statsExplode) == 58)
			{
				$team = new MLB_TeamData("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "");
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

						// batting
						case "b_RSPG":
							$team->b_RSPG = $val;
							break;
						case "b_PA":
							$team->b_PA = $val;
							break;
						case "b_AB":
							$team->b_AB = $val;
							break;
						case "b_R":
							$team->b_R = $val;
							break;
						case "b_H":
							$team->b_H = $val;
							break;
						case "b_DBL":
							$team->b_DBL = $val;
							break;
						case "b_TRPL":
							$team->b_TRPL = $val;
							break;
						case "b_HR":
							$team->b_HR = $val;
							break;
						case "b_RBI":
							$team->b_RBI = $val;
							break;
						case "b_SB":
							$team->b_SB = $val;
							break;
						case "b_CS":
							$team->b_CS = $val;
							break;
						case "b_BB":
							$team->b_BB = $val;
							break;
						case "b_SO":
							$team->b_SO = $val;
							break;
						case "b_BA":
							$team->b_BA = $val;
							break;
						case "b_OBP":
							$team->b_OBP = $val;
							break;
						case "b_SLG":
							$team->b_SLG = $val;
							break;
						case "b_OPS":
							$team->b_OPS = $val;
							break;
						case "b_OPSP":
							$team->b_OPSP = $val;
							break;
						case "b_TB":
							$team->b_TB = $val;
							break;
						case "b_GDP":
							$team->b_GDP = $val;
							break;
						case "b_HBP":
							$team->b_HBP = $val;
							break;
						case "b_SH":
							$team->b_SH = $val;
							break;
						case "b_SF":
							$team->b_SF = $val;
							break;
						case "b_IBB":
							$team->b_IBB = $val;
							break;
						case "b_LOB":
							$team->b_LOB = $val;
							break;

						// pitching
						case "p_RGPG":
							$team->p_RGPG = $val;
							break;
						case "p_W":
							$team->p_W = $val;
							break;
						case "p_L":
							$team->p_L = $val;
							break;
						case "p_WLP":
							$team->p_WLP = $val;
							break;
						case "p_ERA":
							$team->p_ERA = $val;
							break;
						case "p_G":
							$team->p_G = $val;
							break;
						case "p_GS":
							$team->p_GS = $val;
							break;
						case "p_GF":
							$team->p_GF = $val;
							break;
						case "p_CG":
							$team->p_CG = $val;
							break;
						case "p_tSho":
							$team->p_tSho = $val;
							break;
						case "p_cSho":
							$team->p_cSho = $val;
							break;
						case "p_SV":
							$team->p_SV = $val;
							break;
						case "p_IP":
							$team->p_IP = $val;
							break;
						case "p_H":
							$team->p_H = $val;
							break;
						case "p_R":
							$team->p_R = $val;
							break;
						case "p_ER":
							$team->p_ER = $val;
							break;
						case "p_HR":
							$team->p_HR = $val;
							break;
						case "p_BB":
							$team->p_BB = $val;
							break;
						case "p_IBB":
							$team->p_IBB = $val;
							break;
						case "p_SO":
							$team->p_SO = $val;
							break;
						case "p_HBP":
							$team->p_HBP = $val;
							break;
						case "p_BK":
							$team->p_BK = $val;
							break;
						case "p_WP":
							$team->p_WP = $val;
							break;
						case "p_BF":
							$team->p_BF = $val;
							break;
						case "p_ERAP":
							$team->p_ERAP = $val;
							break;
						case "p_FIP":
							$team->p_FIP = $val;
							break;
						case "p_WHIP":
							$team->p_WHIP = $val;
							break;
						case "p_H9":
							$team->p_H9 = $val;
							break;
						case "p_HR9":
							$team->p_HR9 = $val;
							break;
						case "p_BB9":
							$team->p_BB9 = $val;
							break;
						case "p_SO9":
							$team->p_SO9 = $val;
							break;
						case "p_SOW":
							$team->p_SOW = $val;
							break;
						case "p_LOB":
							$team->p_LOB = $val;
							break;
					}
				}
				array_push ($teams, $team);
			}
		}

		// get the date's scheduled GAMES
		$sql = "select id, GameDate, AwayTeam, HomeTeam from games where GameDate = '" . $_POST["PickDate"] . "' and league = 'MLB'; ";
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
					$awayGrade = Get_MLB_Grade($team);
					$awayScore = Get_MLB_Score($team);
					$awayTeamFound = 1;
				}
				if (trim($team->team) == trim($row["HomeTeam"]))
				{
					$homeTeam = $team;
					$homeGrade = Get_MLB_Grade($team);
					$homeScore = Get_MLB_Score($team);
					$homeTeamFound = 1;
				}
				if ($awayTeamFound == 1 && $homeTeamFound == 1)
				{
					// away team won (higher grade), but they have less runs in rand()
					if ($awayGrade > $homeGrade && $awayScore < $homeScore)
					{
						// final score should be the actual RSPG
						$awayScore = ceil($awayTeam->b_RSPG);
						$homeScore = floor($homeTeam->b_RSPG);
					}
					if ($awayGrade < $homeGrade && $awayScore > $homeScore)
					{
						$awayScore = floor($awayTeam->b_RSPG);
						$homeScore = ceil($homeTeam->b_RSPG);
					}

					// tie breaker
					if ($awayScore == $homeScore)
					{
						// how many extra runs
						$extraRuns = rand(1, 2);
						// who won?
						if (rand(0, 1) == 1)
							$awayScore += $extraRuns;
						else
							$homeScore += $extraRuns;
					}
					$sql = " update games set AwayScorePick = " . $awayScore . ", HomeScorePick = " . $homeScore;
					$sql.= " where id = " . $row["id"] ;
					$sql.= " and AwayScorePick is null and HomeScorePick is null ;  ";
					$update_multi_sql .= $sql;
					break;
				}
			}
		}
		$conn->multi_query($update_multi_sql);
		echo "These have been updated:</br>";
		echo str_replace(';', ';</br>', $update_multi_sql);
	}
?>

</div>

<hr>
