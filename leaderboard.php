<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("db/db_config.php");
include("db/get_game_state.php");



if (isset($_POST["teamsecret"]) and isset($_POST["teamID"])){ 
    $teamID = mysqli_real_escape_string($conn, $_POST["teamID"]); // watch out! - $teamID is the form value. $team_id is the Session value.
    $teamsecret = mysqli_real_escape_string($conn,$_POST["teamsecret"]);
    include("db/check_login.php");
}




//Build Mini League on form submit

    if (array_key_exists("addteam", $_POST) and array_key_exists("teamID",$_SESSION)){
        
        foreach($_POST["addteam"] as $entry) {
            $ins_array[] = "('".$_SESSION["teamID"]."' , '".$entry."')";
        }
        $ins_array[] = "('".$_SESSION["teamID"]."' , '".$_SESSION["teamID"]."')"; // add to own league.

        $ins_qry = 'INSERT IGNORE mini_leagues(league_owner,league_member) VALUES' . implode(" , ", $ins_array) ;

        if(mysqli_query($conn, $ins_qry)){
            // inserted succesfully
        } else {
            // failed to insert - raise an error
        }

    }



//Build Mini League on form submit

    if (array_key_exists("deleteteam", $_POST) and array_key_exists("teamID",$_SESSION)){
        
        foreach($_POST["deleteteam"] as $entry) {
            $ins_array[] = "'" . mysqli_real_escape_string($conn,$entry) . "'";
        }

        $ins_qry = "DELETE FROM mini_leagues where league_owner = '". $_SESSION["teamID"]. "' AND league_member in (" . implode(",", $ins_array) . ") ;" ;

        if(mysqli_query($conn, $ins_qry)){
            // inserted succesfully
        } else {
            // failed to insert - raise an error
        }

    }
// Check if there's a Mini League for this team ID

if (array_key_exists("teamID", $_SESSION)){
    $qry_check_ML = "SELECT COUNT(*) as 'Count' FROM mini_leagues where league_owner = '".$_SESSION["teamID"]."'; ";

    $check_ML = $conn->query($qry_check_ML);
    $check_ML = $check_ML->fetch_assoc();

    if ($check_ML["Count"] * 1  >= 1) {
        $mini_league_exists = true;
    } else {
        $mini_league_exists = false;
    }

} else {
    //no team logged in
    $mini_league_exists = false;
}

?>  
<!DOCTYPE html>
<head>
    <title>Leaderboard | Socially Distant Pub Quiz</title>
    <?php include("htmlheader.php"); ?>
    <!-- DataTables CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
</head>
<body>
    <div class="container">


<div>

  <?php include_once("header.php");
    ?>


  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Full Leaderboard</a></li>
    <li role="presentation"><a href="#mini" aria-controls="mini" role="tab" data-toggle="tab">My League</a></li>
    <li role="presentation"><a href="#alltime" aria-controls="alltime" role="tab" data-toggle="tab">Lockdown Leaderboard</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">

        <h2>Main Leaderboard:</h2>
        <h4>Socially Distanced Pub Quiz - Overall</h4>

        <?php
        if ($current_round > 1){
         include("db/build_leaderboard.php"); 
        } else {
        ?>
        <div class="alert alert-warning"><p><strong>The results aren't in yet</strong></p>
            <p>The leaderboard will become available after the first round has been marked</p>
        </div>
        <?php
        }
        ?>
            
    </div>


    <div role="tabpanel" class="tab-pane" id="mini">



        <h2>Mini Leaderboard:</h2>
        <h4>Compete against the teams that matter...</h4>

        <?php if (!array_key_exists("teamID",$_SESSION)) {
            include_once("db/get_teams.php");
            // no team logged in
            ?>

            <div class="alert alert-warning">
                <p class="lead">You're not logged in</p>
                <p>Log in below to see your rivals</p>
            </div>

        <?php include("loginform.php");
        
        } elseif ($mini_league_exists == false) {
            ?>
            <div class="alert alert-info">You don't have your own league yet...</div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">How to build your own league table...</h3>
              </div>
              <div class="panel-body">
                <ol>
                    <li>Go to the "Full Leaderboard" tab</li>
                    <li>Tick the names of your rivals</li>
                    <li>Click the button to add to your league</li>
                </ol>
                You can add more rivals to the league table any time you like...
              </div>
            </div>


            <?php
        } else {
            //League exists!
            if ($current_round > 1){
             include("db/build_minileague.php"); 
            } else {
            ?>
            <div class="alert alert-warning"><p><strong>The results aren't in yet</strong></p>
                <p>The leaderboard will become available after the first round has been marked</p>
            </div>
            <?php
            }
        } // end: else  


        ?>

    </div> <!-- End of tab: my league div -->

    <div role="tabpanel" class="tab-pane" id="alltime">
        <?php include("db/build_alltimeboard.php"); ?>
    </div> <!-- End of tab panel - all time -->

  </div>

</div>




    </div> <!-- end container -->
</body>

<?php 
	mysqli_close($conn);
?>

