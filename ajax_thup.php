<?php

include_once("db/db_config.php");

if(!isset($_SESSION)) {
    //Start Session if there's not one already
     session_start();
}


if (isset($_POST["thup_question"]) AND array_key_exists("teamID",$_SESSION)){

    // clean up the inputs

    $qno = mysqli_real_escape_string($conn,$_POST["thup_question"]);
    $qno = htmlspecialchars($qno);

    $quizid = mysqli_real_escape_string($conn,$_POST["thup_question"]);
    $quizid = htmlspecialchars($qno);

    $rno = mysqli_real_escape_string($conn,$_POST["thup_round"]);
    $rno = htmlspecialchars($rno);

    $tid = $_SESSION["teamID"];

    $commit = "INSERT IGNORE INTO question_ratings (question_number, round_number, team_id, quiz_id)
               VALUES ($qno, $rno, '$tid', '$quizid')";
    
    // echo $commit;

    if (mysqli_query($conn,$commit)){
         echo "Saved";
    } else {
        echo "Error: couldn't save to database";
    }


} else {
    // Either missing Session or POST
    echo "Error: Insufficient data";
}