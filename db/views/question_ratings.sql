ALTER VIEW question_popularity AS

	SELECT
	    q.round_number,
		q.question_number,
		COUNT(
			r.team_id
		) as 'Likes'
	FROM
		quiz_questions q
		LEFT JOIN question_ratings r ON r.question_number = q.question_number
		AND r.round_number = q.round_number
	GROUP BY
		q.question_number,
		q.round_number
		
