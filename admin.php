<?php 

include ("db/db_config.php");
session_start();

if (!array_key_exists("admin_user",$_SESSION) and (array_key_exists("adminpass", $_POST))){

        $secret = mysqli_real_escape_string($conn,$_POST["adminpass"]);

        $pwdqry = "SELECT COUNT(*) as 'Count' from admin_password where password = '$secret'";
        $checkpwd = $conn->query($pwdqry);
        $pwd_res = $checkpwd->fetch_assoc();
               
        if (($pwd_res["Count"] * 1) >= 1) {
            // admin user is logged in. Set session.
            $_SESSION["admin_user"] = "Administrator";
        } else {
            // kill the session
            unset($_SESSION["admin_user"]);
            $failedlogin = 1;

            $error = 1;
            $errormsg = "Invalid admin password. Idiot.";
        }
}



if (array_key_exists("admin_user", $_SESSION)){

    include ("db/get_game_state.php");

    // Fetch list of rounds into array
    
    $rounds_query = "SELECT round_number, round_title FROM rounds";
    $result = mysqli_query($conn,$rounds_query);

    while($row = $result->fetch_assoc()){
        $rounds_list[] = $row;

    };

    // Get number of teams currently registered

    $regteamsqry = "SELECT count(*) from teams";
    $registered_teams = mysqli_query($conn, $regteamsqry);
    $registered_teams = mysqli_fetch_row($registered_teams);
    $registered_teams = $registered_teams[0];


 //   var_dump($rounds_list);

}
?>

<html>
    <head>
        <title>Socially Distant Pub Quiz | Admin</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">
        <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>

    </head>

    <body>

        <div class="container">

            <?php

            if (array_key_exists("admin_user", $_SESSION)){
                ?>

            <h1>Pub Quiz - Admin Panel</h1>
            <p class="lead">Use this page to change settings and control the quiz</p>

            <div class="col-xs-12 col-md-12" id="quiz_status">
                <h3>Current Quiz Status</h3>
                <?php 
                if ($current_round == 0) {
                    echo '<div class="alert alert-info"><strong>Quiz not yet started</strong></div>';              
                } else {
                    echo '<div class="alert alert-warning"><strong>Quiz is underway</strong></div>';
                }  ?>
                <dl class="dl-horizontal">
                  <dt>Current Round:</dt>
                    <dd><?php echo $current_round; ?></dd>
                  <dt>Round Title</dt>
                    <dd><?php echo $round_name; ?></dd>
                  <dt>Teams Currently Registered</dt>
                    <dd><?php echo $registered_teams; ?></dd>
                  <dt>Round Title</dt>
                    <dd><?php echo $round_name; ?></dd>
                  <dt>Round Title</dt>
                    <dd><?php echo $round_name; ?></dd>

                </dl>

            </div>

            <div class="col-xs-12 col-md-6" id="round">
                <h3>Round</h3>
            </div>

            <div class="col-xs-12 col-md-6" id="signup">
                <h3>Allow New Teams?</h3>
                <?php if ($allow_signup == 1) {
                    echo '<div class="alert alert-info"><strong>New teams are being accepted</strong></div>';              
                 } else {
                    echo '<div class="alert alert-warning"><strong>The quiz is closed to new teams</strong></div>';
                 } ?>

               
            </div>

            <div class="col-xs-12 col-md-4" id="complete">
                <h3>Quiz Finished?</h3>
            </div>

            <div class="col-xs-12 col-md-4" id="videoenabled">
                <h3>Show Video</h3>
            </div>

            <div class="col-xs-12 col-md-4" id="vid_id">
                <h3>YouTube Video ID</h3>
            </div>





                


        <?php 
            } else { // NOT "admin_user", $_SESSION
                // Admin User is NOT logged in.
            ?>
                <form class="form-inline" action="draw_raffle.php" method="post">
                    <div class="form-group">
                        <label for="adminpass">Admin Password</label>
                        <input type="text" class="form-control" id="adminpass" name="adminpass" placeholder="sssh" required="required">
                    </div>
                    <input type="hidden" name="loginonly" value="yes">
                    <button type="submit" class="btn btn-default">Log In</button>
                </form>


            <?php
            } // end of Session IF.





                mysqli_close($conn);
                ?>

        </div><!-- /container -->
    </body>
</html>