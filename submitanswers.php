<?php

include("db/db_config.php");
session_start();
include("funcs/pictureround.php");

include("db/get_game_state.php");

$field_template =   '<td><input type="text" class="form-control" id="ansQQQ" name="answered_questions[QQQ]" placeholder="Answer QQQ" value="ZZZ"></td>';
$teamKnownBool = (array_key_exists("teamID", $_SESSION));

// clean the results

if ( empty($_POST) ) {
  $error = true;
  $error_reason[] = "Please enter the details on the form below";
} else {


	// build answers array and fill it. We know that they have value ans1-ans10
	$answers = array();
    foreach($_POST["answered_questions"] as $qno => $ans) {
		$answers[$qno] = mysqli_real_escape_string($conn,$ans);
    }
    
	if ($teamKnownBool){
		$teamID = $_SESSION["teamID"];
        $team_secret_pass = 1;
	} else {
		$teamID = mysqli_real_escape_string($conn, $_POST["teamID"]);
		$teamsecret = mysqli_real_escape_string($conn, $_POST["secret"]);
	}
    $round = mysqli_real_escape_string($conn,$_POST["round_number"]);
} // end of if POST empty.


if (!$teamKnownBool){
    include("db/check_login.php");

    } else {
	$team_secret_pass = 1;
}
?>

<html>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-161589071-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-161589071-1');
        </script>

        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">
            <title>Socially Distant Pub Quiz</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
            <?php include("header.php");?>

            <hr>

            <?php 
            if ($team_secret_pass == 1) {
            ?>

            <div class="alert alert-success"> 
                <p>Thanks for submitting your answers...</p>   
                <p><a class="btn btn-success" href="index.php" role="button">Go back to the main page</a></p>
            </div>

            <?php
            } else {
            include("db/get_teams.php"); // this will fetch the team names, as it does on the homepage.
            ?>

            <div class="alert alert-danger">Your team secret was incorrect, please try again!</div>
            <form class="form-inline" method="POST" action="submitanswers.php">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="teamname">Pick Team</label>
                        <select class="form-control" id="teamname" name="teamID">
                            <?php foreach($teams_list as $team){
                                echo '<option value="' . $team["team_id"] . '">' . $team["team_name"] . "</option>";
                            } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="teamsecret">Team Secret</label>
                        <input type="text" class="form-control" id="teamsecret" name="secret" placeholder="Ssssh">
                    </div>

            <!-- end team name/secret row -->
            <?php

            } //header - team_secret_pass check
            ?>
                    <table class="table table-striped">
                        <tr><td><strong>#</strong></td><td><strong>Header</strong></td></tr>
                        <?php 

                        $question_number = 1;
                        foreach ($answers as $qno => $ans){
                            echo "<td>". $question_number."</td>";

                            if ($team_secret_pass == 1) {
                                echo "<td> Submitted: ";
                                $check_exists_qry = "SELECT COUNT(*) as 'Count' from submitted_answers where question_number ='$question_number' and round_number = '$round' and team_id = '$teamID'";
                                $exists = $conn->query($check_exists_qry);
                                $exists = $exists->fetch_assoc();

                                if (($exists["Count"] * 1) >= 1) {
                                    // yes - update it
                                    $ans_query = "UPDATE submitted_answers set answer = '$ans' where question_number ='$question_number' and round_number = '$round' and team_id = '$teamID'";
                                } else {
                                    // no - insert it
                                    $ans_query = "INSERT INTO submitted_answers (team_id, round_number, question_number, answer) VALUES ('$teamID', '$round', '$question_number', '$ans');";
                                }


                                if (mysqli_query($conn,$ans_query)){
                                    echo $ans . "</td>";
                                } else {
                                    //error condition
                                    $retry_field = str_replace("QQQ", $question_number, $field_template);
                                    $retry_field = str_replace("ZZZ", $ans, $retry_field);
                                    echo $retry_field;

                                }
                                // spew it back out into a table
                                $ans_query = "";

                            } else {
                                // this code runs if the team secret is NOT matched. Allow retry.
                                $retry_field = str_replace("QQQ", $question_number, $field_template);
                                $retry_field = str_replace("ZZZ", $ans, $retry_field);
                                echo $retry_field;
                                }
                                $question_number++;
                                echo "</tr>";
                            }
                        ?>
                    </table>

                    <?php if ($team_secret_pass == 0) {
                    // close off the form elements
                    ?>

                    <input type="hidden" id="roundnumber" name="round_number" value=<?php echo '"'.$round.'"'; ?> required="required">
    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>


<?php 
    mysqli_close($conn);
?>

