ALTER VIEW alltime_leaderboard AS

select s.team_id, q.quiz_id, date_format(q.quiz_date,'%W %D %M') as 'quiz_date', q.quiz_title,
	sum(s.correct) as 'quiz_correct', sum(s.marked) as 'quiz_marked', 
	st.total_marked, st.total_correct, st.quizzes_played
from quizzes q
JOIN submitted_answers s on s.quiz_id = q.quiz_id -- this is the quiz by quiz data
JOIN (
	select team_id, sum(marked) as 'total_marked', sum(correct) as 'total_correct' , count(distinct quiz_id) as 'quizzes_played'
		from submitted_answers group by team_id
	) st -- this is the summary per team
	ON st.team_id = s.team_id

group by s.team_id, s.team_id, s.quiz_id
ORDER BY q.quiz_date asc