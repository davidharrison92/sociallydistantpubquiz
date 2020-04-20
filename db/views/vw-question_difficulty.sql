CREATE VIEW question_difficulty
AS
SELECT
	q.round_number, q.question_number, (SUM(s.correct) / SUM(s.marked)) * 100 as 'pct_correct'
FROM submitted_answers s
JOIN quiz_questions q on q.question_number = s.question_number and q.round_number = s.round_number
GROUP BY q.round_number, q.question_number