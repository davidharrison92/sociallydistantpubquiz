<?php
// var_dump($_POST);
include ("db/db_config.php");
include ("db/get_game_state.php");
include("db/get_teams.php");


// This page has a login form - check if it has been submitted, and if needed, log the team in.
if (isset($_POST["teamsecret"]) and isset($_POST["teamID"])){ 
    $teamID = mysqli_real_escape_string($conn, $_POST["teamID"]); // watch out! - $teamID is the form value. $team_id is the Session value.
    $teamsecret = mysqli_real_escape_string($conn,$_POST["teamsecret"]);
    include("db/check_login.php");
}


if(isset($_SESSION["teamID"])){
    $team_id = $_SESSION["teamID"];
}

$success = "";
$error = "";
// Changes to data

    IF (isset($_GET["change"])){

        IF ($_GET["change"] == "secret"){
            // Sanitise inputs

            $old_secret = mysqli_real_escape_string($conn, $_POST["current_secret"]);
            $new_secret = mysqli_real_escape_string($conn, $_POST["new_secret"]);
            
            // check match of new and old secret

            $name_query = "SELECT COUNT(*) as 'Count' from teams where team_id = '$team_id' and secret = '$old_secret'";
            $check = $conn->query($name_query);
            $check = $check->fetch_assoc();
                    
        
            if (($check["Count"] * 1) >= 1) {
                // passes test - set new secret
            
            $update_secret = "UPDATE teams set secret = '$new_secret' where team_id = '$team_id'";
            mysqli_query($conn,$update_secret);

                $success = "Your Team Secret has been changed.";


            } else {
                $error = "Your original team secret was incorrect. Your secret has not been changed.";
            } // end login check



        } elseif ($_GET["change"] == "team"){
            // Sanitise Inputs
            $new_name = mysqli_real_escape_string($conn, $_POST["teamName"]);

            $new_pers1 = mysqli_real_escape_string($conn, $_POST["teamMember1"]);
            $new_pers2= mysqli_real_escape_string($conn, $_POST["teamMember2"]);
            $new_pers3 = mysqli_real_escape_string($conn, $_POST["teamMember3"]);
            $new_pers4 = mysqli_real_escape_string($conn, $_POST["teamMember4"]);

            $new_email = mysqli_real_escape_string($conn, $_POST["teamEmail"]);
            $new_optin = mysqli_real_escape_string($conn, $_POST["email_opt_in"]) * 1;


            //Build Query

            $update_team = "UPDATE teams SET
                                team_name = '$new_name',
                                person1 = '$new_pers1',
                                person2 = '$new_pers2',
                                person3 = '$new_pers3',
                                person4 = '$new_pers4',
                                team_email = '$new_email',
                                email_opt_in = $new_optin
                            WHERE team_id = '$team_id';" ;


           // Run Query
           mysqli_query($conn,$update_team);
        }


    }



// Get team data

if(isset($_SESSION["teamID"])){



    $team_qry = "SELECT team_name, person1, person2, person3, person4, team_email, email_opt_in from teams where team_id = '$team_id' limit 1";
    $team_qry = mysqli_query($conn, $team_qry);



    $team_data = mysqli_fetch_row($team_qry);

    $team_name = $team_data[0]; //team name

    //team members
        $person1 = $team_data[1];
        $person2 = $team_data[2];
        $person3 = $team_data[3];
        $person4 = $team_data[4];

    $team_email = $team_data[5];
    $email_opt_in = $team_data[6];

}




// Get Team Performance Data
    $scoreqry = "SELECT * FROM alltime_leaderboard where team_id = '$team_id'" ;


    $result = mysqli_query($conn, $scoreqry);
    $rows = array();
    while($row = $result->fetch_assoc()){
        $team_scores[] = $row;
    };

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Team | Socially Distant Pub Quiz</title>
        <?php include("htmlheader.php"); ?>

    </head>

    <body>
        <div class="container">
                <div class="row">
                    <?php include("header.php");?>
                </div> <!-- / header row -->
                <hr>
                <div class="row"> <!-- main content -->
                <?php if (array_key_exists("teamID", $_SESSION)){ ?>
                    <div class="page-header">
                        <h1>Edit Team: <small><?php echo $team_name; ?></small></h1>
                        <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/release_session.php' ?>" class="text-small">Log out</a>
                    </div>

                <?php 
                    if (strlen($error)>1) {
                        ?> 
                            <div class="alert alert-danger"?> <?php echo $error; ?> </div>
                        <?php
                    }

                    if (strlen($success)>1) {
                        ?> 
                            <div class="alert alert-success"?> <?php echo $success; ?> </div>
                        <?php
                    } ?>


                <div class="col-md-5" id="teamscores">
                <h3>Scores so far...</h3>

                <?php if (empty($team_scores)){
                    ?>
                    <div class="alert alert-info">
                        You don't have any scores yet. Once we mark some of your answers, they'll appear here...
                    </div>
                <?php
                } else {
                    //display some scores
                    ?>
                    <table class="table table-condensed">

					<thead>
						<tr>
							<td><strong>Date</td>
							<td><strong>Quiz</td>
							<td><strong>Score</td>
						</tr>
					</thead>
                <?php
                    foreach($team_scores as $tsq){
                        ?>
                        <tr>
                            <td><?php echo $tsq["quiz_date"]; ?> </td>
                            <td><?php echo $tsq["quiz_title"]; ?> </td>
                            <td><strong><?php echo $tsq["quiz_correct"];?></strong> <span class="small">/<?php echo $tsq["quiz_marked"]; ?></span>
                                        <span class="small epull-right">	
                                            <a href="your_answers.php?quizID=<?php echo $tsq["quiz_id"];?>&teamID=<?php echo $tsq["team_id"];?>"><span class="glyphicon glyphicon-book"></span>  See Answers</a> 
                                        </span>
                            </td>
                        </tr>

                <?php
                    }
                    ?>
                    </table>
            <?php
                } ?>


                </div> <!-- end of scores -->



                <div class="col-md-6" id="teaminfo">
                    <h3>Team Details</h3> <button href="#" class="btn btn-info swapscript"><span class="glyphicon glyphicon-pencil"></span> Edit</button>

                    <dl class="dl-horizontal">
                        <dt>Team Name</dt>
                            <dd><?php echo $team_name;?></dd>
                        <dt>Team Members</dt>
                            <dd><?php echo $person1;?></dd>
                            <dd><?php echo $person2;?></dd>
                            <dd><?php echo $person3;?></dd>
                            <dd><?php echo $person4;?></dd>
                        <dt>Team Email</dt>
                            <dd><?php echo $team_email;?></dd>
                        <dt>Hear from us?</dt>
                            <dd><?php if($email_opt_in == 1){
                                $glyph = "thumbs-up";
                            } else {
                                $glyph = "thumbs-down";
                            } ?>
                            <span class="glyphicon glyphicon-<?php echo $glyph; ?>"></span>
                            
                    </dl>
                    

                    
                </div>

                <div class="col-md-6 hidden" id="teamedit">

                    <h3>Change Team Detail</h3> 
                    
                    <form class="form-horizontal" action="manage_team.php?change=team" method="POST">
                        <div class="form-group">
                            <label for="teamName" class="col-sm-2 control-label">Team Name</label>
                            <div class="col-sm-10">
                            <input type="text" class="form-control" name="teamName" placeholder="<?php echo $team_name; ?>" value="<?php echo $team_name;?> " required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="teamMembers" class="col-sm-2 control-label">Team Members</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="teamMember1" placeholder="<?php echo $person1;?>" value="<?php echo $person1; ?>" required="required">
                                <input type="text" class="form-control" name="teamMember2" placeholder="<?php echo $person2;?>" value="<?php echo $person2; ?>">
                                <input type="text" class="form-control" name="teamMember3" placeholder="<?php echo $person3;?>" value="<?php echo $person3; ?>">
                                <input type="text" class="form-control" name="teamMember4" placeholder="<?php echo $person4;?>" value="<?php echo $person4; ?>">

                            </div>
                            
                        </div>

                        <form class="form-horizontal">
                        <div class="form-group">
                            <label for="teamEmail" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                            <input type="email" class="form-control" name="teamEmail" placeholder="<?php echo $team_email; ?>" value="<?php echo $team_email;?> "required="required">
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" name="email_opt_in" value="1" <?php if( $email_opt_in == "1") { echo "checked" ; } ?> > I want to hear about future quizzes and other shenanigans.
                                </label>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Save changes</button>
                            </div>
                        </div>
                        </form>                    
                    
                        <hr>
                        <h3>Change Team Secret</h3> 

                        <form class="form-inline" method="POST" action="manage_team.php?change=secret">
                            <div class="form-group">
                                <label for="current_secret">Current Secret:</label>
                                <input type="text" class="form-control" name="current_secret" placeholder="Hello There...">
                            </div>
                            <div class="form-group">
                                <label for="new_secret">New Secret</label>
                                <input type="text" class="form-control" name="new_secret" placeholder="General Kenobi...">
                            </div>
                            <button type="submit" class="btn btn-default">Change Secret</button>
                            </form>
                    
                    <button href="#" class="btn btn-link swapscript"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                </div>

                <script>
                    $(".swapscript").click(function(){
                        $('#teaminfo').toggleClass("hidden");
                        $('#teamedit').toggleClass("hidden");
                    });
                    
                </script>
                
                <?php } else { ?>
                        <!-- no team logged in -->

                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">You must be logged in before you can make changes to your team.</h3>
                            </div>
                            <div class="panel-body">
                            If you want to register a new team, please <a href="newteam.php">click here</a>.
                            </div>
                        </div>
                        <?php
                            include("loginform.php");
                        }
                    ?>

                </div> <!-- /main content -->

    </div> <!-- End Container -->
    </body>
</html>