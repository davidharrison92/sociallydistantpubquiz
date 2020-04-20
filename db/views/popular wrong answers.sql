select q.round_number, q.question, s.answer,  sum(marked) from submitted_answers s 
JOIN quiz_questions q on q.question_number = s.question_number and q.round_number = s.round_number
where correct = 0 and marked =1
group by q.round_number, q.question, s.answer


