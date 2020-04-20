ALTER VIEW unmarked_answers AS
SELECT
    s.round_number AS round_number,
	s.question_number AS question_number,
	s.answer AS given_answer,
	COUNT(s.team_id) AS freq,
	qq.question AS question,
	qq.answer AS true_answer,
	qq.picture_question AS picture_question
FROM
	(
		submitted_answers s
		JOIN quiz_questions qq ON(
			(
				(qq.round_number = s.round_number)
				AND (
					qq.question_number = s.question_number
				)
			)
		)
	)
WHERE
	(s.marked = 0)
GROUP BY
	s.round_number,
	s.question_number,
	s.answer,
	qq.question,
	qq.answer
ORDER BY
	s.round_number,
	s.question_number,
	COUNT(s.team_id) DESC;