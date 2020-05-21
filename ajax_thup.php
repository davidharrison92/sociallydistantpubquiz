<?php

include_once("db/db_config.php");

if(!isset($_SESSION)) {
    //Start Session if there's not one already
     session_start();
}


if (isset($_POST) AND array_key_exists("teamID",$_SESSION)){

    // clean up the inputs

    $qno = mysqli_real_escape_string($conn,$_POST["thup_question"]);
    $qno = htmlspecialchars($qno);

    $rno = mysqli_real_escape_string($conn,$_POST["thup_round"]);
    $rno = htmlspecialchars($qno);

    $tid = $_SESSION("team_id");

    $commit = "INSERT IGNORE INTO queston_ratings (question_number, round_number, team_id)
               VALUES ($qno, $rno, '$tid')";


} else {
    // Either missing Session or POST
}