select korrect.k, alle.a
from
(
SELECT count(id) k, 11 j
FROM   games
WHERE  ( 
		(AwayScorePick-HomeScorePick < 0 AND AwayScoreActual-HomeScoreActual < 0)
		OR
		(HomeScorePick-AwayScorePick < 0 AND HomeScoreActual-AwayScoreActual < 0) 
		)
	) korrect
LEFT JOIN
(
SELECT count(id) a, 11 j
FROM   games
) alle
ON korrect.j = alle.j;
