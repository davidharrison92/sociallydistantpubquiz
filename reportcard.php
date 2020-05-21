<?php
include("db/db_config.php");
include("funcs/pictureround.php");


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["teamsecret"]) and isset($_POST["teamID"])){ 
    $teamID = mysqli_real_escape_string($conn, $_POST["teamID"]); // watch out! - $teamID is the form value. $team_id is the Session value.
    $teamsecret = mysqli_real_escape_string($conn,$_POST["teamsecret"]);
    include("db/check_login.php");
}


// by now session  will exist if either:
//  - team was already logged in
//          or
//  - team has just logged in succesfully.

if (array_key_exists("teamID",$_SESSION)){
    // Get team name from teams

    $tdata_query = "SELECT team_name, team_ID from teams where team_id = '". mysqli_real_escape_string($conn,$_SESSION["teamID"]) ."'";
    $tdata_res = mysqli_query($conn,$tdata_query);
    $tdata_res = mysqli_fetch_row($tdata_res);

    if(count($tdata_res) == 0){
        // Somehow session had an invalid team ID in it? theoretically impossible. I bet it happens.

        unset($_SESSION["teamID"]);
        $teamExists = FALSE;


    }

    $teamExists = TRUE;

    $team_name = $tdata_res[0];
    $team_ID = $tdata_res[1];


    // Get Answers
    $qdata_query = "SELECT s.round_number, r.round_title, r.round_additional, s.question_number, s.team_id, s.answer as 'asub', correct , q.answer, q.question, q.picture_question, q.extra_info, d.pct_correct
        from submitted_answers s 
            LEFT JOIN question_difficulty d on d.round_number = s.round_number and d.question_number = s.question_number
        JOIN quiz_questions q on q.question_number = s.question_number and q.round_number = s.round_number
        JOIN rounds r on r.round_number = s.round_number
        where s.marked = 1 and s.team_id = '" . $team_ID . "';";

//    echo $qdata_query;

    $qdata_res = mysqli_query($conn, $qdata_query);


    $qdata = array();
    while ($row = $qdata_res->fetch_assoc()){
        $qdata[] = $row;
    }

    $question_data = array();
    foreach($qdata as $q){
    
        // Make it searchable by round number.
        $question_data[$q["round_number"]][] = $q;

    }



} //end of session set.

if (!array_key_exists("teamID", $_SESSION)){
    //Need to login. 
    $teamExists = FALSE;
    echo "<h3>Log in to view your report card...</h3>";
} else {
    ?>
    <h3>Report Card - <?php echo $team_name;?> <span class="small"><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/release_session.php' ?>">Not you?</a></span></h3>
    <?php
}



if (!isset($question_data)){
    ?>
    <div class="alert alert-warning"><strong>No answers to give out yet!</strong><br>Come back after each round is marked, and see how you did!</div>
<?php
    } else {

    ?>
    <!-- HAVE SOME AJAX (You'll need this later) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>
    <?php
    if (array_key_exists("teamID", $_SESSION)){
        foreach($question_data as $i => $qloop){

            // $qloop = $question_data[$i] ;

            ?>
            <p class="lead">Round Number <?php echo $qloop[$i]["round_number"] . ' - ' . $qloop[$i]["round_title"]; ?> </p>

            <?php
            if(strlen($qloop[$i]["round_additional"]) > 0){
                //additional round info.
                ?>
                <p class="small text-info">
                    <?php echo $qloop[$i]["round_additional"]; ?>
                </p>
            <?php
            }
            ?>


            <table class="table table-striped">
                <tr>
                    <td width="10%"><strong>#</strong></td>
                    <td width="30%"><strong>Question</strong></td>
                    <td width="30%"><strong>You said...</strong></td>
                    <td width="30%"><strong>Correct?</strong></td>
                </tr>


            <?php
            foreach($qloop as $q_detail){
                if ($q_detail["correct"] == "1"){
                    echo '<tr class="success">';
                    } else {
                    echo '<tr class="danger">';
                }
            
                ?>
                <td><p class="lead"><strong><?php echo $q_detail["question_number"]; ?></strong></p>
                
                <!-- Thumbs up? -->
                <form action="ajax_thup.php" method="post" id="ajax-form">
                    <input type="hidden" name="thup_round" value="<?php echo $qloop[$i]["round_number"]; ?>">
                    <input type="hidden" name="thup_question" value="<?php echo $q_detail["question_number"]; ?>">
                    <button type="submit"> <span class="glyphicon glyphicon-thumbs-up" data-toggle="tooltip" data-placement="right" title="We liked this question!"></span></button>
                </form>
                </td>
                <td><?php if ($q_detail["picture_question"] == 1){
                                echo pictureround($q_detail["question"]);
                            } else {
                                echo utf8_encode($q_detail["question"]);
                            } ?>
                        </td>
                <td><?php echo $q_detail["asub"]; ?> </td>
                <td><p> <?php       
                    if ($q_detail["correct"] == "1"){
                        //tick
                        echo '<span class="lead glyphicon glyphicon-ok"></span> <strong>'. $q_detail["answer"] . '</strong>';;
                    } else {
                        //wrong - show correct answer.
                        echo '<span class="lead glyphicon glyphicon-remove"></span> <strong>'. $q_detail["answer"]. '</strong>';
                    } ?>
                    </p>

                    <?php
                    if (strlen($q_detail["extra_info"]) >1){
                        // extra info
                        echo '<p>'.$q_detail["extra_info"].'</p>';
                    }
                    ?>

                    <p class="text-muted">Guessed by: 
                     <?php if ($q_detail["pct_correct"] > 67){
                            // easy question
                            echo '<span class="label label-success">'. round($q_detail["pct_correct"]).'% of teams';
                        } elseif ($q_detail["pct_correct"]  < 67 and $q_detail ["pct_correct"]  > 45){
                            // med question
                            echo '<span class="label label-info">'. round($q_detail["pct_correct"]).'% of teams';
                        } else {
                            // hard question
                            echo '<span class="label label-danger">'. round($q_detail["pct_correct"]).'% of teams';
                        } ?>
                    </p>
                </td>
            <?php
            }
            ?>

            </table>
           
        <?php
        $i++;
        } //end while
        ?>


        <script>
            // Bootstrap Tooltips...
            $(function () {
            $('[data-toggle="tooltip"]').tooltip()
            })

        //AJAX Form Submit (this is witchcraft of the highest order!)
        function submitForm(form){
            var url = form.attr("action");
            var formData = $(form).serializeArray();
            $.post(url, formData).done(function (data) {
                alert(data);
            });
        }
        $("#ajax-form").submit(function() {
        submitForm($(this));
        return false;
        });
        </script>

    <?php
    } // end check for sessionkey
} // end check questions exist

if(!array_key_exists("teamID",$_SESSION)){
    //login form
    include("db/get_teams.php");
    ?>
        <p class="lead">To view your report card, you need to log back in...</p>
    <?php
    include("loginform.php");
}



?>
<?php 
	mysqli_close($conn);
?>
</html>

