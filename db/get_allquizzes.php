<?php 
// GET List of teams
$quizzes_list = array();


$quizzes_qry = "SELECT quiz_id, quiz_date, quiz_title from quizzes ORDER BY quiz_date DESC";


    $result = mysqli_query($conn,$quizzes_qry);

    $rows = array();

    while($row = $result->fetch_assoc()){
        $quizzes_list[] = $row;

    };
