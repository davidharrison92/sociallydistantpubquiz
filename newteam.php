<?php 




include ("db/db_config.php");
include ("db/get_game_state.php");
include("db/get_teams.php");

if (isset($_POST["teamsecret"]) and isset($_POST["teamID"])){ 
    $teamID = mysqli_real_escape_string($conn, $_POST["teamID"]); // watch out! - $teamID is the form value. $team_id is the Session value.
    $teamsecret = mysqli_real_escape_string($conn,$_POST["teamsecret"]);
    include("db/check_login.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Register a Team | Socially Distant Pub Quiz</title>
        <?php include("htmlheader.php"); ?>

    </head>
    <body>
        <div class="container">
            <div class="row">
                <?php include("header.php");?>
            </div> <!-- / header row -->
            <hr>

           
            <?php include("messages.php");?>

            <?php if($allow_signup == 0) { 
                //signup forbidden
            ?>

            <div class="alert alert-danger"><p class="lead">Too slow!</p>
                <p>We're already underway, and aren't accepting any new entries into the quiz at this time. We hope we see you for the next one!</p>
                <a class="btn btn-warning btn-sm" href="index.php" role="button">Back to main page</a>
                <?php 
                } else {
                //signups allowed
                ?>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Back again?</strong></div>
                    <div class="panel-body">
                        Just log back in and we'll add you to this week's quiz
                        <?php 
                            include("loginform.php");
                        ?>
                    </div>
                    <div class="panel-heading panel-info">Or if you're new, sign up below!</div>
                </div>
                <h4>Register a new team!</h4>
                <p>Fill in your details below:</p>
                <ul>
                    <li>Teams can have up to 4 members</li>
                    <li>It's best if you nominate a captain to fill in the answer sheets</li>
                </ul>
                <hr>


                <form class="form-horizontal" method="post" action="./submit_team.php">
                    <div class="form-group">
                        <label for="team_name" class="col-sm-2 control-label">Team Name</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="team_name" name="team_name" placeholder="The General's Knowledge" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email"class="col-sm-2 control-label">Email address</label>
                            <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="team_email" placeholder="Email" required="required">
                        <input type="checkbox" value="1" name="email_opt_in"> Add me to the mailing list so I can stay up to date with what you idiots are planning...

                            

                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="names" class="col-sm-2 control-label">Team Members</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="member1" name="member1" placeholder="Team Member #1" required="required" >
                            <input type="text" class="form-control" id="member2" name="member2" placeholder="Team Member #2">
                            <input type="text" class="form-control" id="member3" name="member3" placeholder="Team Member #3">
                            <input type="text" class="form-control" id="member4" name="member4" placeholder="Team Member #4">
                            <input type="checkbox" value="1" name="livestream"><strong> We're happy for you to include us in the live video</strong> <span class="text-muted">(you'll need to provide a Gmail address so we can call you on Hangouts)</span>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="team_secret" class="col-sm-2 control-label">Team Secret</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" name="team_secret" id="team_secret" placeholder="I have the high ground" required="required" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')" >
                            <strong>Remember this, you'll need it to prove who you are.</strong></span>
                            <span id="helpBlock" class="help-block">Letters, Numbers and Spaces only<br>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Sign up!</button>   <span class="small">  By signing up, you agree to our <a href="about.php" target="_blank">terms and privacy policy</a>.</span>

                        </div>
                    </div>
                </form>

                <?php }
                ?>
            </div> <!-- /container -->
        </div> <!-- /container -->
    </body>
</html>


<?php 
  mysqli_close($conn);
?>

