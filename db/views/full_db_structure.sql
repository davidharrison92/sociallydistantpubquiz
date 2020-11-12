-- Database export via SQLPro (https://www.sqlprostudio.com/allapps.html)
-- Exported by davidharrison at 12-11-2020 13:28.
-- WARNING: This file may contain descructive statements such as DROPs.
-- Please ensure that you are running the script at the proper location.


-- BEGIN TABLE admin_password
CREATE TABLE `admin_password` (
  `password` char(50) NOT NULL,
  PRIMARY KEY (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE admin_password

-- BEGIN TABLE current_round
CREATE TABLE `current_round` (
  `roundnumber` int(11) DEFAULT NULL,
  `round_label` char(50) NOT NULL,
  `show_video` int(11) NOT NULL DEFAULT '0',
  `allow_signup` int(11) NOT NULL DEFAULT '0',
  `youtubeID` char(15) NOT NULL,
  `quiz_complete` int(11) NOT NULL DEFAULT '0',
  `quiz_id` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE current_round

-- BEGIN TABLE mailchimp
CREATE TABLE `mailchimp` (
  `email` char(255) NOT NULL,
  `opt_in` int(11) NOT NULL DEFAULT '0',
  `time_updated` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE mailchimp

-- BEGIN TABLE mini_leagues
CREATE TABLE `mini_leagues` (
  `league_owner` char(50) NOT NULL,
  `league_member` char(50) NOT NULL,
  PRIMARY KEY (`league_owner`,`league_member`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE mini_leagues

-- BEGIN TABLE question_ratings
CREATE TABLE `question_ratings` (
  `team_id` char(50) NOT NULL,
  `round_number` int(11) NOT NULL,
  `quiz_id` char(20) NOT NULL,
  `question_number` int(11) NOT NULL,
  PRIMARY KEY (`team_id`,`round_number`,`quiz_id`,`question_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE question_ratings

-- BEGIN TABLE quizzes
CREATE TABLE `quizzes` (
  `quiz_id` char(20) NOT NULL,
  `quiz_date` date NOT NULL,
  `quiz_title` char(50) NOT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- END TABLE quizzes

-- BEGIN TABLE quiz_questions
CREATE TABLE `quiz_questions` (
  `round_number` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `quiz_id` char(20) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `picture_question` tinyint(4) NOT NULL,
  `hint` text,
  `questiontype` char(15) NOT NULL,
  `extra_info` text NOT NULL,
  `qkey` int(11) NOT NULL,
  PRIMARY KEY (`round_number`,`question_number`,`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- END TABLE quiz_questions

-- BEGIN TABLE rounds
CREATE TABLE `rounds` (
  `round_number` int(11) NOT NULL,
  `quiz_id` char(20) NOT NULL,
  `round_title` char(75) NOT NULL,
  `round_locked` int(11) NOT NULL,
  `round_additional` text NOT NULL,
  PRIMARY KEY (`round_number`,`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE rounds

-- BEGIN TABLE submitted_answers
CREATE TABLE `submitted_answers` (
  `question_number` int(11) NOT NULL,
  `quiz_id` char(20) NOT NULL,
  `round_number` int(11) NOT NULL,
  `team_id` char(150) NOT NULL,
  `answer` char(255) NOT NULL,
  `marked` tinyint(4) NOT NULL,
  `correct` tinyint(4) NOT NULL,
  PRIMARY KEY (`question_number`,`quiz_id`,`round_number`,`team_id`),
  KEY `given_answer` (`answer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE submitted_answers

-- BEGIN TABLE taglines
CREATE TABLE `taglines` (
  `tag` char(75) NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE taglines

-- BEGIN TABLE teams
CREATE TABLE `teams` (
  `team_name` char(150) DEFAULT NULL,
  `team_id` char(150) NOT NULL,
  `secret` char(150) DEFAULT NULL,
  `person1` char(150) NOT NULL,
  `person2` char(150) NOT NULL,
  `person3` char(150) NOT NULL,
  `person4` char(150) NOT NULL,
  `team_email` char(255) NOT NULL,
  `team_cookie_hash` char(255) NOT NULL,
  `willing_livestream_participant` int(11) NOT NULL DEFAULT '0',
  `email_opt_in` int(11) NOT NULL DEFAULT '0',
  `team_registered_at` datetime NOT NULL,
  `team_active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- END TABLE teams

-- BEGIN TABLE team_round_scores
CREATE TABLE `team_round_scores` (
  `teamID` char(50) NOT NULL,
  `Round` int(11) NOT NULL,
  `Score` int(11) NOT NULL,
  PRIMARY KEY (`teamID`,`Round`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- END TABLE team_round_scores

DROP VIEW IF EXISTS complex_leaderboard;
CREATE OR REPLACE complex_leaderboard
AS
select `t`.`team_id` AS `team_id`,`t`.`team_name` AS `team_name`,`t`.`person1` AS `person1`,`t`.`person2` AS `person2`,`t`.`person3` AS `person3`,`t`.`person4` AS `person4`,coalesce(`overall`.`total_score`,0) AS `total_score`,coalesce(`overall`.`total_marked`,0) AS `total_marked`,coalesce(`r1`.`round_score`,0) AS `R1Correct`,coalesce(`r1`.`round_marked`,0) AS `R1Marked`,coalesce(`r2`.`round_score`,0) AS `R2Correct`,coalesce(`r2`.`round_marked`,0) AS `R2Marked`,coalesce(`r3`.`round_score`,0) AS `R3Correct`,coalesce(`r3`.`round_marked`,0) AS `R3Marked`,coalesce(`r4`.`round_score`,0) AS `R4Correct`,coalesce(`r4`.`round_marked`,0) AS `R4Marked`,coalesce(`r5`.`round_score`,0) AS `R5Correct`,coalesce(`r5`.`round_marked`,0) AS `R5Marked`,coalesce(`r6`.`round_score`,0) AS `R6Correct`,coalesce(`r6`.`round_marked`,0) AS `R6Marked`,coalesce(`r7`.`round_score`,0) AS `R7Correct`,coalesce(`r7`.`round_marked`,0) AS `R7Marked`,coalesce(`r8`.`round_score`,0) AS `R8Correct`,coalesce(`r8`.`round_marked`,0) AS `R8Marked`,coalesce(`r9`.`round_score`,0) AS `R9Correct`,coalesce(`r9`.`round_marked`,0) AS `R9Marked` from `quiz_staging`.`current_round` `c` join ((((((((((`quiz_staging`.`teams` `t` left join (select `sa`.`team_id` AS `team_id`,sum(`sa`.`correct`) AS `total_score`,sum(`sa`.`marked`) AS `total_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) group by `sa`.`team_id`) `overall` on((`overall`.`team_id` = `t`.`team_id`))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r1` on(((`r1`.`round_number` = 1) and (`r1`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r2` on(((`r2`.`round_number` = 2) and (`r2`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r3` on(((`r3`.`round_number` = 3) and (`r3`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r4` on(((`r4`.`round_number` = 4) and (`r4`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r5` on(((`r5`.`round_number` = 5) and (`r5`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r6` on(((`r6`.`round_number` = 6) and (`r6`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r7` on(((`r7`.`round_number` = 7) and (`r7`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r8` on(((`r8`.`round_number` = 8) and (`r8`.`team_id` = `t`.`team_id`)))) left join (select `sa`.`team_id` AS `team_id`,`sa`.`round_number` AS `round_number`,sum(`sa`.`correct`) AS `round_score`,sum(`sa`.`marked`) AS `round_marked` from (`quiz_staging`.`submitted_answers` `sa` join `quiz_staging`.`current_round` `cr` on((`cr`.`quiz_id` = `sa`.`quiz_id`))) where (`sa`.`marked` = 1) group by `sa`.`round_number`,`sa`.`team_id`) `r9` on(((`r9`.`round_number` = 9) and (`r9`.`team_id` = `t`.`team_id`)))) where (`t`.`team_active` = 1) order by coalesce(`overall`.`total_score`,0) desc,`t`.`team_id`;

DROP VIEW IF EXISTS question_difficulty;
CREATE OR REPLACE question_difficulty
AS
select `q`.`round_number` AS `round_number`,`q`.`question_number` AS `question_number`,((sum(`s`.`correct`) / sum(`s`.`marked`)) * 100) AS `pct_correct` from (`quiz_staging`.`submitted_answers` `s` join `quiz_staging`.`quiz_questions` `q` on(((`q`.`question_number` = `s`.`question_number`) and (`q`.`round_number` = `s`.`round_number`)))) group by `q`.`round_number`,`q`.`question_number`;

DROP VIEW IF EXISTS question_popularity;
CREATE OR REPLACE question_popularity
AS
select `q`.`round_number` AS `round_number`,`q`.`question_number` AS `question_number`,`q`.`quiz_id` AS `quiz_id`,count(`r`.`team_id`) AS `Likes` from (`quiz_staging`.`quiz_questions` `q` left join `quiz_staging`.`question_ratings` `r` on(((`r`.`question_number` = `q`.`question_number`) and (`r`.`round_number` = `q`.`round_number`) and (`r`.`quiz_id` = convert(`q`.`quiz_id` using utf8mb4))))) group by `q`.`question_number`,`q`.`round_number`,`q`.`quiz_id`;

DROP VIEW IF EXISTS unmarked_answers;
CREATE OR REPLACE unmarked_answers
AS
select `s`.`round_number` AS `round_number`,`s`.`question_number` AS `question_number`,`s`.`answer` AS `given_answer`,count(`s`.`team_id`) AS `freq`,`qq`.`question` AS `question`,`qq`.`answer` AS `true_answer`,`qq`.`picture_question` AS `picture_question` from ((`quiz_staging`.`submitted_answers` `s` join `quiz_staging`.`current_round` `c` on((`s`.`quiz_id` = `s`.`quiz_id`))) join `quiz_staging`.`quiz_questions` `qq` on(((`qq`.`round_number` = `s`.`round_number`) and (`qq`.`question_number` = `s`.`question_number`) and (convert(`qq`.`quiz_id` using utf8mb4) = `c`.`quiz_id`)))) where ((`s`.`marked` = 0) and (`s`.`quiz_id` = `c`.`quiz_id`)) group by `s`.`round_number`,`s`.`question_number`,`s`.`answer`,`qq`.`question`,`qq`.`answer` order by `s`.`round_number`,`s`.`question_number`,count(`s`.`team_id`) desc;

