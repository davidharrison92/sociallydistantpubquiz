
ALTER VIEW complex_leaderboard AS	
select t.team_id, t.team_name, t.person1, t.person2, t.person3, t.person4,
		COALESCE(overall.total_score,0) as 'total_score', COALESCE(overall.total_marked,0) as 'total_marked',
		COALESCE(r1.round_score,0) as 'R1Correct', COALESCE(r1.round_marked,0) as 'R1Marked',
		COALESCE(r2.round_score,0) as 'R2Correct', COALESCE(r2.round_marked,0) as 'R2Marked',
		COALESCE(r3.round_score,0) as 'R3Correct', COALESCE(r3.round_marked,0) as 'R3Marked',
		COALESCE(r4.round_score,0) as 'R4Correct', COALESCE(r4.round_marked,0) as 'R4Marked',
		COALESCE(r5.round_score,0) as 'R5Correct', COALESCE(r5.round_marked,0) as 'R5Marked',
		COALESCE(r6.round_score,0) as 'R6Correct', COALESCE(r6.round_marked,0) as 'R6Marked',
		COALESCE(r7.round_score,0) as 'R7Correct', COALESCE(r7.round_marked,0) as 'R7Marked',
		COALESCE(r8.round_score,0) as 'R8Correct', COALESCE(r8.round_marked,0) as 'R8Marked',
		COALESCE(r9.round_score,0) as 'R9Correct', COALESCE(r9.round_marked,0) as 'R9Marked'
from 
	teams t
	LEFT JOIN
		(select team_id, sum(correct) as 'total_score', sum(marked) as 'total_marked' from submitted_answers group by team_id) overall
		ON overall.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r1
		on r1.round_number = 1 
		and r1.team_id = t.team_id
		LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r2
		on r2.round_number = 2
		and r2.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r3
		on r3.round_number = 3
		and r3.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r4
		on r4.round_number = 4 
		and r4.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r5
		on r5.round_number = 5
		and r5.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r6
		on r6.round_number = 6
		and r6.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r7
		on r7.round_number = 7
		and r7.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r8
		on r8.round_number = 8
		and r8.team_id = t.team_id
	LEFT JOIN
	(select team_id, round_number, sum(correct) as 'round_score', sum(marked) as 'round_marked' from submitted_answers where marked = 1  group by round_number, team_id) r9
		on r9.round_number = 9 
		and r9.team_id = t.team_id

	ORDER by total_score desc, t.team_id