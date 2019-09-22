<div style="text-decoration: underline; margin:5px;">Our Record</div>
<?php
	$leagues = array("NFL", "MLB", "NHL", "NBA");
	foreach ($leagues as $league)
	{
		$recordSql = "
		SELECT korrect.k, alle.a
		FROM   (
			SELECT count(id) k, 1 j
			FROM   games
			WHERE  ( (AwayScorePick-HomeScorePick < 0 AND AwayScoreActual-HomeScoreActual < 0)
				 OR  (HomeScorePick-AwayScorePick < 0 AND HomeScoreActual-AwayScoreActual < 0) )
			AND    AwayScoreActual IS NOT NULL AND HomeScoreActual IS NOT NULL
			AND    league = '$league'
			) korrect
			LEFT JOIN
			(
			SELECT count(id) a, 1 j
			FROM   games
			WHERE  AwayScoreActual IS NOT NULL AND HomeScoreActual IS NOT NULL
			AND    league = '$league'
			) alle
			ON korrect.j = alle.j;
		";
		$recordSet = $conn->query($recordSql);
		$record = $recordSet->fetch_assoc();
		if ($record["a"] > 0)
			echo $league.": ".round($record["k"] / $record["a"] * 100, 2) . "% </br>" ;
	}
?>