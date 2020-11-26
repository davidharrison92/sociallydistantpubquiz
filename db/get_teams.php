<?php 
// GET List of teams
$teams_list = array();

$teams_qry = "SELECT team_name, team_id, team_active from teams ORDER BY team_name ASC";

    $result = mysqli_query($conn,$teams_qry);
    $rows = array();

    while($row = $result->fetch_assoc()){
        $teams_list[] = $row;

    };