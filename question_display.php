<?php 
include ("db/db_config.php");
include ("funcs/pictureround.php");
session_start();

// Check Login:


if (!isset($_SESSION["admin_user"]) and isset($_POST["adminpass"])){

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


if(array_key_exists("admin_user",$_SESSION)){


    if (
            (!isset($_GET["qkey"]))
             or ($_GET["qkey"] < 1) 
             or (!is_numeric($_GET["qkey"]))
        ){
        $qkey = 1;
    } else {
        $qkey = mysqli_real_escape_string($conn,$_GET["qkey"]);      
    }

    $qkey = intval($qkey);

    $get_question_qry = "SELECT q.round_number, question_number, question, r.round_title, q.questiontype
                        FROM quiz_questions q
                        JOIN rounds r on r.round_number = q.round_number 
                        where qkey =" . $qkey . ";";
    $qdata = mysqli_query ($conn, $get_question_qry);

    $qdata = mysqli_fetch_row($qdata);

    $round_number = $qdata[0];
    $question_number = $qdata[1];
    $question = $qdata[2];
    $round_title = $qdata[3];
    $questiontype = $qdata[4];

} // no admin session
?>


<!DOCTYPE html>
<html>
    <head>
        <?php include("htmlheader.php"); ?>
        <title>Question Reader | Pub Quiz Admin</title>
    </head>

    <style>
    .jumbotron{
        background-color:#00FF00;
        color:#CCFFFF;
    }
    </style>

    <body>
        <div class="container">

        <?php if(array_key_exists("admin_user",$_SESSION)){
            //Logged in
            ?>
                <div class="jumbotron">
                    <dl class="dl-horizontal">
                        <dt>Round <?php echo $round_number; ?> </dt>
                        <dd><?php echo $round_title; ?></dd>
                        <dt>Question:</dt>
                        <dd><?php echo $question_number; ?> </dd>
                    </dl>
                    
                    <h2><?php
                    if ($questiontype == "picture") {
                        include_once("funcs/pictureround.php");
                        echo pictureround($question);
                    } else {
                        echo $question;
                    }
                    ?></h2>
                    <br><br><br><br><br><br><br><br><br><br>
                </div>
            
                <div class="row">
                <div class="col-xs-6 col-md-6">
                    <a href="question_display.php?qkey=<?php echo $qkey -1 ; ?>" role="button" class="btn btn-primary btn-lg btn-block"><span class="glyphicon glyphicon-fast-backward"></span> Back</a>
                </div> 
                <div class="col-xs-6 col-md-6">
                    <a href="question_display.php?qkey=<?php echo $qkey +1 ; ?>" role="button" class="btn btn-primary btn-lg btn-block">Next <span class="glyphicon glyphicon-fast-forward"></span></a>
                </div> 
            <?php
        } else {
            // Not logged in
            ?>
                <form class="form-inline" action="question_display.php" method="post">
                    <div class="form-group">
                        <label for="adminpass">Admin Password</label>
                        <input type="text" class="form-control" id="adminpass" name="adminpass" placeholder="sssh" required="required">
                    </div>
                    <input type="hidden" name="loginonly" value="yes">
                    <button type="submit" class="btn btn-default">Log In</button>
                </form>

        <?php
        }
        ?>

          
        </div>
        <!-- end Container -->
    <body>
</html>