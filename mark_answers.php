<?php 

include ("db/db_config.php");
session_start();



// save marked answers first. 
$error = -1;
$errormsg = "";
if ( !empty($_POST) ) {

    if (!isset($_SESSION["admin_user"])){

        echo "bar";

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

    if( (!isset($_POST["loginonly"])) AND (isset($_SESSION["admin_user"]))) { // only do this if it's sucessfully logged in (but not JUST a login)
    echo "foo";
    //mark it.	

    $mark_round = mysqli_real_escape_string($conn,$_POST["round_number"]);
    $mark_question = mysqli_real_escape_string($conn, $_POST["question_number"]);

        //marked answers
            foreach($_POST["markedanswers"] as $markans){
                $markedanswers[] = "'" . mysqli_real_escape_string($conn,$markans) ."'";
            }
            $mark_list = '(' . implode("," , $markedanswers) . ')'; // creates ('a', 'sql', 'friendly', 'list')


        //correct answers
            foreach($_POST["correctanswers"] as $corrans){
                $correctanswers[] = "'" . mysqli_real_escape_string($conn,$corrans) ."'";
            }
            $corr_list = '(' . implode("," , $correctanswers) . ')'; // creates ('a', 'sql', 'friendly', 'list')

        // Save changes to database      


        $mark_sql = "UPDATE submitted_answers set marked = 1 WHERE round_number=".$mark_round." and question_number=".$mark_question;
        $mark_sql .= " and answer in " .$mark_list ;

        
        if (mysqli_query($conn,$mark_sql)){
            $error= 2;
        } else {
            $error = 1;
            $errormsg = "Failed database to set answers to marked. Tell dave about this: " . $ins_score;
        }


        $corr_sql = "UPDATE submitted_answers set correct = 1 WHERE round_number=".$mark_round." and question_number=".$mark_question;
        $corr_sql .= " and answer in " .$corr_list ;

        
        if (mysqli_query($conn,$corr_sql)){
            $error= 2;
        } else {
            $error = 1;
            $errormsg = "Failed database to set answers to marked. Tell dave about this: " . $ins_score;
        }


    } else {
        //no answers being marked.

    }

} // end of _POST processing


// check if this user is administrator.
if (array_key_exists("admin_user", $_SESSION)){

    // fetch answers that need marking.
    $questions_to_mark = array();
    $questions_query = "SELECT distinct round_number, question_number, question, true_answer FROM unmarked_answers";

    $result = mysqli_query($conn,$questions_query);
    $rows = array();

    while($row = $result->fetch_assoc()){
        $questions_to_mark[] = $row;
    };
}



//var_dump($to_mark_teams_list);

// fetch the teams who have submitted, but not marked.

// something like select distinct teamID from submitted_answers with a missing round_scores value.
?>

<html>
    <head>
        <title>Socially Distant Pub Quiz | Admin</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">

    </head>

    <body>

        <div class="container">
            <h3>Mark answers:</h3>

            <?php
            if ($error == 1) { // this is most likey going to be a failed login.
                echo '<div class="alert alert-danger">'.$errormsg.'</div>';

            }

            if (array_key_exists("admin_user", $_SESSION)){


                if ($error == 2){
                
                    echo '<div class="alert alert-success">Successfully saved score</div>';
                
                } elseif ($error == 1) {
                    echo '<div class="alert alert-danger">'.$errormsg.'</div>';
                }

                if (count($questions_to_mark) == 0){
                    echo "Nothing to mark!";
                }

                // for each of the teamIDs:

                foreach ($questions_to_mark as $qdata){
                    $answers_query = "select given_answer, freq  FROM unmarked_answers WHERE round_number = " . $qdata["round_number"] . " and question_number = ". $qdata["question_number"] ;

                    $result = mysqli_query($conn,$answers_query);

                    $rows = array();
                    unset($answers_data);

                    while($row = $result->fetch_assoc()){
                        $answers_data[] = $row;

                    };

                ?>
              
                <form class="form-inline" action="mark_answers.php" method="post">
                <p class="lead"><?php echo "<strong>R" . $qdata["round_number"] . " Q". $qdata["question_number"] . ":</strong> " . $qdata["question"]; ?>
                <br><?php echo $qdata["true_answer"]; ?></p>
                <table class="table table-condensed">
                    <tr>
                        <td><strong>#</strong></td>
                        <td><strong>Answer</strong></td>
                    <td>Correct?</td>
                    </tr>

                <?php foreach($answers_data as $ans){
                    ?>
                    <tr>
                        <td><?php echo $ans["freq"];?></td>
                        <td><?php echo $ans["given_answer"];?></td>
                        <!-- Tickbox for answers which are deemed correct by the marker -->
                        <td>
                            <input type="checkbox" name="correctanswers[]" value=<?php echo '"'. $ans["given_answer"] . '"';?> >
                            <span class="small"><?php echo "(" . $ans["freq"] . " teams)" ; ?>
                        </td>
                        <!-- Hidden value submits regardless, these will be used to indicate values that are marked, but not correct. It's possible answers have been submitted between pageload and form submit -->
                        <input type="hidden" name="markedanswers[]" value=<?php echo '"'. $ans["given_answer"] . '"';?> >
                    </tr>
                <?php
                }
                ?>  
                </table>


                <input type="hidden" id="round_number" name="round_number" value=<?php echo '"'. $qdata["round_number"] . '"';?> >
                <input type="hidden" id="question_number" name="question_number" value=<?php echo '"'. $qdata["question_number"] . '"';?> >

                    <button type="submit" class="btn btn-default">Mark Answers</button>
                </form>

                <?php 
               } // end of foreach - team data

            } else { // NOT "admin_user", $_SESSION
                // Admin User is NOT logged in.
            ?>
                <form class="form-inline" action="mark_answers.php" method="post">
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