<?php

// START SESSION

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



// GET CURRENT ROUND INFO

$round_qry = "SELECT roundnumber, round_label, show_video, allow_signup, youtubeID,quiz_complete, quiz_id from current_round";
$round_res = mysqli_query($conn, $round_qry);

$round_res = mysqli_fetch_row($round_res);
$current_round = $round_res[0];
$round_name = $round_res[1];
$show_video = $round_res[2];
$allow_signup = $round_res[3];
$ytID = $round_res[4];
$quiz_complete = $round_res[5];
$quiz_id = $round_res[6];