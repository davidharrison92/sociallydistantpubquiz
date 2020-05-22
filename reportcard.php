<?php
include("db/db_config.php");
include("funcs/pictureround.php");

$team_exists = FALSE;  // TRUE means that the teamID from session/get was found in DB
$my_report = TRUE; //this is the default, FALSE indicates that it's been forced in GET

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

    // Allow TeamID from URL to overrule the Logged in Session.
    if (isset($_GET["teamID"])) {
        $teamID = mysqli_real_escape_string($conn, $_GET["teamID"]);
        $my_report = false; // viewing another team's answers
    } else {
        $teamID = mysqli_real_escape_string($conn, $_SESSION["teamID"]);
        $my_report = true; //logged in team's answers
    }

    $tdata_query = "SELECT team_name, team_ID from teams where team_id = '". $teamID ."'";
    $tdata_res = mysqli_query($conn,$tdata_query);
    $tdata_res = mysqli_fetch_row($tdata_res);

    if(count($tdata_res) == 0){
        // Somehow session had an invalid team ID in it? theoretically impossible. I bet it happens.

        if ($my_report) {
            //Somehow there's a bad session in. Kill that.
            unset($_SESSION["teamID"]);
        }
        
        $team_exists = FALSE;

    } else {
        //must be some results
        $team_exists = TRUE;
    }


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

if ($team_exists == FALSE or (!array_key_exists("teamID", $_SESSION))){

    //Need to login. 
    if ($my_report){
        echo "<h3>Log in to view your report card...</h3>";
    } else {
        echo "<h3>Invalid Team ID Specified</h3>";
    }
} else {
    ?>
    
    <h3>Report Card - <?php echo $team_name;?></h3> <br>
    <?php if($my_report){
        include("db/get_teams.php");
        ?>

            <button type="button" id="showpeek" class="btn btn-link"><span class="glyphicon glyphicon-chevron-down"></span> View Another Team</button>

            <div style="display:none" id="peekform">
                <button type="button" id="hidepeek" class="btn btn-link"><span class="glyphicon glyphicon-chevron-up"></span> Hide</button>
            
            <form class="form-inline" method="GET" action="your_answers.php">
            
                <select class="form-control pickteamname" id="teamname" name="teamID">
                    <option value="" disabled selected>Whose answers do you want to spy on?</option>
                    <?php foreach($teams_list as $team){
                        //only include the other teams.
                        if  ($team["team_id"] !== $_SESSION["teamID"]){
                            echo '<option value="' . $team["team_id"] . '">' . $team["team_name"] . "</option>";
                        }
                    } ?>
                </select>
                
                <button type="submit" class="btn btn-info">Peek!</button>
                
                <script>
                    $(document).ready(function() {
                        $('.pickteamname').select2();
                    });
                </script>
            </form>
            </div>


            <script>

            $(document).ready(function(){
            $("#hidepeek").click(function(){
                $("#peekform").hide(400);
                $("#showpeek").show(400);
            });
            $("#showpeek").click(function(){
                $("#peekform").show(400);
                $("#showpeek").hide(400);
            });
            });

            </script>

        <?php
    } else {
        ?>
             <p class="lead"><span class="label label-info">You're viewing another team's answers.</span> <a href="your_answers.php"><button type="button" class="btn btn-link">Back to my team</button></a> </p>
        <?php
    } 
    ?>
   
    <?php
}



if (!isset($question_data)){
    ?>
    <div class="alert alert-warning"><strong>No answers to see yet!</strong><br>Come back after each round is marked, and see how you did!</div>
<?php
    } else {

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
                    <td width="10%">
                    <strong>#</strong>
                       
                    </td>
                    <td width="30%"><strong>Question</strong></td>
                    <td width="30%"><strong>
                        <?php if($my_report){
                            echo "You Said...";
                        } else {
                            echo "They Said...";
                        }?>
                    </strong></td>
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
                <td>
                    <p class="lead"><strong><?php echo $q_detail["question_number"]; ?></strong></p>
                    
                    <?php 
                    if ($my_report){
                        $formID = "r".$qloop[$i]["round_number"]."q".$q_detail["question_number"];
                        $allforms[] = "#".$formID;
                    
                        ?>
                            <form action="ajax_thup.php" method="post" id="<?php echo $formID; ?>">
                                <input type="hidden" name="thup_round" value="<?php echo $qloop[$i]["round_number"]; ?>">
                                <input type="hidden" name="thup_question" value="<?php echo $q_detail["question_number"]; ?>">
                                <button type="submit"> <span class="glyphicon glyphicon-thumbs-up" data-toggle="tooltip" data-placement="right" title="We liked this question!"></span></button>
                            </form>
                        <?php
                    } // end my form
                    ?>
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

<script>
            jQuery(document).ready(function() {
                
                // Bootstrap Tooltips...
                    $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                    });

                //AJAX Form Submit (this is witchcraft of the highest order!)
                    function submitForm(form){
                        var url = form.attr("action");
                        var formData = $(form).serializeArray();
                        $.post(url, formData).done(function (data) {
                        
                        });
                    };
                    $("<?php echo implode(', ',$allforms); ?>").submit(function(event) {
                        event.preventDefault();
                        submitForm($(this));
                        $(this).append(" Thanks!");
                        return false;
                    });
            
                });

            </script>
</body>
</html>

