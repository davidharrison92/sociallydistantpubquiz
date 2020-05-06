<?php 

include ("db/db_config.php");
include_once("funcs/pictureround.php");

//login if needed.
if (isset($_POST["teamsecret"]) and isset($_POST["teamID"])){ 
    $teamID = mysqli_real_escape_string($conn, $_POST["teamID"]); // watch out! - $teamID is the form value. $team_id is the Session value.
    $teamsecret = mysqli_real_escape_string($conn,$_POST["teamsecret"]);
    include("db/check_login.php");
}

function answer_row(int $question_number, 
                    string $question_type,
                    string $question, 
                    string $prefill,
                    string $hint) {
    //Returns the contents of a <td> for the answersheet.

    if (strtoupper($question_type) == "PICTURE"){
        echo pictureround($question).'<br>';

    }
    
        ?>
        <input type="text" class="form-control" id="<?php echo $question_number; ?>" name="answered_questions[<?php echo $question_number; ?>]" required="required" value="<?php echo $prefill; ?>" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')">
        <?php

     if (strlen($hint)>=1){
        echo '<br><span class="text-info">'.$hint.'</span>';
     }

} // end answer_row fn. def


if (array_key_exists("teamID", $_SESSION)){
    //Team Logged in
    $teamID = $_SESSION["teamID"];


//GET THE ROUNDS
    $rounds_qry = "SELECT t.team_name, t.person1, t.person2, t.person3, t.person4, r.round_number, round_locked, round_title, count(s.question_number) as 'qsubmitted', sum(IFNULL(s.marked,0)) as 'qmarked'
            FROM teams t, rounds r
            LEFT JOIN submitted_answers s on s.round_number = r.round_number and s.team_id = '$teamID'
            WHERE t.team_id = '$teamID'
            GROUP BY r.round_number, round_locked, round_title
            ORDER by r.round_number ASC; ";

    // left join this to the submitted answers, get the number of marked answers.

    $result = mysqli_query($conn, $rounds_qry);

    while ($row = $result->fetch_assoc()){
        $rounds[] = $row;
    }

    $team_name = $rounds[0]["team_name"];

    $team_members = $rounds[0]["person1"];

    if (strlen($rounds[0]["person2"]) > 0){
        $team_members = $team_members . ', ' . $rounds[0]["person2"];
    }
    if (strlen($rounds[0]["person3"]) > 0){
        $team_members = $team_members . ', ' . $rounds[0]["person3"];
    }
    if (strlen($rounds[0]["person4"]) > 0){
        $team_members = $team_members . ', ' . $rounds[0]["person4"];
    }

?>

    <h3><?php echo $team_name; ?>  <small>(<?php echo $team_members;?>)</small></h3>
    <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/release_session.php' ?>" class="text-small">Not You?</a>

    <hr>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
  <?php 
  foreach ($rounds as $round){
    $divname = 'Round'.$round["round_number"];
    ?>
    <li role="presentation" <?php if ($round["round_number"] == $current_round) { echo 'class="active"'; } ?> >
        <a href="#<?php echo $divname; ?>" aria-controls="<?php echo $divname; ?>" role="tab" data-toggle="tab">
        
            <?php echo 'Round #' . $round["round_number"] . ' ' ;

                if ($round["round_locked"] == 1){
                    //Round Locked
                    $round_status[$round["round_number"]] = 'locked';
                    echo '<span class="glyphicon glyphicon-lock"></span>';

                } elseif (($round["qsubmitted"] > 0 or $round["round_number"] < $current_round) AND $round["qmarked"] == 0) {
                    //submitted, or historical round - not locked
                    $round_status[$round["round_number"]] = 'pending';
                    echo '<span class="glyphicon glyphicon-upload"></span>';
                } elseif ($round["qmarked"] > 0){
                    //marking has started - lock
                    $round_status[$round["round_number"]] = 'locked';
                    echo '<span class="glyphicon glyphicon-lock"></span>';
                } else {
                    $round_status[$round["round_number"]] = 'enabled';
                    if ($round["round_number"] > $current_round){
                        //future round
                    echo '<span class="glyphicon glyphicon-hourglass"></span>';
                    } else {
                        // current or expired round
                        echo '<span class="glyphicon glyphicon-pencil"></span>';

                    }
                } 

            ?>

        </a>
    </li>
    <?php 
    } //end of the first foreach round
    ?>
  
  </ul>

  <!-- Tab panes -->
<div class="tab-content">
<?php
// Write Out the Questions
foreach($rounds as $round){

    // keep these to hand
    $round_number = $round["round_number"];
    $round_title = $round["round_title"];
    $round_locked = $round["round_locked"];
    $divname = 'Round'.$round_number;

    ?>
    <!-- Build the NAV for the start of each round -->
    <div role="tabpanel" class="tab-pane fade <?php if ($current_round == $round_number){ echo 'active in'; } ?>" id="<?php echo $divname; ?>">

        <h4><?php echo 'Round #'. $round_number . " - " . $round_title; ?> </h4>

        <?php if($round_status[$round_number] == "pending"){
            ?>
            <div class="panel panel-info">
              <div class="panel-heading">Submitted</div>
              <div class="panel-body">
                You've already submitted some answers below. You've got the option to change your answers, but be quick!
              </div>
            </div> 
        <?php
        }


    
    // TABS

    if ($round_locked == 1){
        // Show an Error Page
        ?>
        <div class="panel panel-warning">
            <div class="panel-heading">This Round is Locked</div>
            <div class="panel-body">   
                We've probably got something special planned, and don't want to spoil the surprise
            </div>
        </div>
        <?php
    } else{
        //Get the questions - TO DO USE SESSION ID HERE

        $get_questions = "select q.round_number, q.question_number, q.question, q.questiontype, ifnull(s.answer,'') as 'submittedanswer', q.hint
                        FROM quiz_questions q
                        left join submitted_answers s on s.question_number = q.question_number and s.round_number = q.round_number and s.team_id = '$teamID'  
                        where q.round_number = " . $round_number . ";" ;

            


        $q_result = mysqli_query($conn,$get_questions);

        $round_questions = array();

        while($row = $q_result->fetch_assoc()){
            $round_questions[$round_number][] = $row;
        }


        // create a form, with hidden values to identify the round.
        ?>
        <form class="form-inline" method="POST" action="submitanswers.php">
            <input type="hidden" id="roundnumber" name="round_number" value=<?php echo '"'.$round_number.'"'; ?>> 

            <table class="table table-striped">

            <?php
            // foreach of the questions, create a form. 
            foreach ($round_questions[$round_number] as $qdata){
                ?>
                <tr>
                    <td width="35"><strong><?php echo $qdata["question_number"]; ?></strong></td>
                    <td><?php
                        if ($round_status[$round_number] == "enabled" OR $round_status[$round_number] == "pending"){
                            //call function, build the answer box
                            echo answer_row($qdata["question_number"], 
                                            $qdata["questiontype"], 
                                            $qdata["question"], 
                                            $qdata["submittedanswer"], 
                                            $qdata["hint"]);
                        } else {
                            // just spit out the answer they gave, if it's empty then maybe some placeholder?
                            echo $qdata["submittedanswer"];
                        }
                        
            }



    }



    ?>
     </table>
    <?php 
    if ($round_status[$round_number] == 'enabled'){
        echo '<button type="submit" class="btn btn-default">Submit</button>';
    }

    if ($round_status[$round_number] == 'pending'){
        echo '<button type="submit" class="btn btn-default">Update Answers</button>';
    }

    if ($round_status[$round_number] == 'locked'){
        echo '<button type="submit" class="btn btn-default disabled">Submit</button>';
        echo '<span class="text-danger">Sorry, this round is locked and can\'t be edited any more</span>';
    }
    ?>
    </form>
     </div> <!-- End for the round panel -->

    <?php
    } // end foreach
    ?>




      </div> <!-- End of the tab-content div -->

    </div> <!-- End of  overall Nav Div  -->



    <script>
        $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })
    </script>
<?php

} // end of IF team logged in
else { //when a team is not logged in - show the login form

    ?>
    <div class="panel panel-danger">
        <div class="panel-heading">You need to login <strong>before</strong> you can play.</div>
        <div class="panel-body">
            <p>We've made some improvements to our quiz this week. We'll explain all before we get going. </p>
            <p>If you have any issues, please <a href="mailto:hello@sociallydistant.pub">email us</a>, or send us a tweet (<a href="https:///twitter.com/pubquizstreams">@PubQuizStreams</a>)</p>
        </div>
    </div>

    <?php 
    include("loginform.php");



}



$questions = array();


