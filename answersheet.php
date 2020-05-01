<?php 

include ("db/db_config.php");
include_once("funcs/pictureround.php");

function answer_row(int $question_number, string $question_type, string $question optional, string $prefill optional){
    //Returns the contents of a <td> for the answersheet.
    
    if (strtoupper($question_type) = "STANDARD") {
        //just return a normal box. No gimmicks.
        ?>
        <input type="text" class="form-control" id="<?php echo $question_number; ?>" name="answered_questions[<?php echo $question_number; ?>]" required="required" placeholder="<?php echo $prefill; ?>" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')">
        <?php

    } elseif (strtoupper($questiontype) = "PICTURE"){

        echo pictureround($question);
        ?>
        <input type="text" class="form-control" id="<?php echo $question_number; ?>" name="answered_questions[<?php echo $question_number; ?>]" required="required" placeholder="<?php echo $prefill; ?>" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')">
        <?php

    }


}





$questions = array();
// if ($current_round > 0) {

//     include("funcs/pictureround.php");

//     $get_questions = "SELECT picture_question,
//                       CASE WHEN picture_question = 1 THEN question ELSE 'hidden' END AS 'img_address',
//                       question_number, hint
//                     FROM
//                         quiz_questions
//                     WHERE
//                         round_number = ".$current_round." ORDER BY question_number ASC ;" ;


//     $result = mysqli_query($conn, $get_questions);

//     while ($row = $result->fetch_assoc()){
//         $questions[] = $row;
//     }

// }


//GET THE ROUNDS
    $rounds_qry = "SELECT r.round_number, round_locked, round_title, count(s.question_number) as 'qsubmitted', sum(IFNULL(s.marked,0)) as 'questionsmarked'
            FROM rounds r
            LEFT JOIN submitted_answers s on s.round_number = r.round_number and s.team_id = '5e99cb7127f21'
            GROUP BY r.round_number, round_locked, round_title
            ORDER by r.round_number ASC; ";

    // left join this to the submitted answers, get the number of marked answers.

    $result = mysqli_query($conn, $rounds_qry);

    while ($row = $result->fetch_assoc()){
        $rounds[] = $row;
    }
?>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
  <?php 
  foreach ($rounds as $round){
    $divname = 'Round'.$round["round_number"];
    ?>
    <li role="presentation" <?php if ($round["round_number"] == $current_round) { echo 'class="active"'; } ?> >
        <a href="#<?php echo $divname; ?>" aria-controls="<?php echo $divname; ?>" role="tab" data-toggle="tab">
        <?php echo 'Round #' . $round["round_number"] ;?>
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


    <?php
    // TABS

    if ($round_locked == 1){
        // Show an Error Page
    } else{
        //Get the questions - TO DO USE SESSION ID HERE

        $get_questions = "select q.round_number, q.question_number, q.question, q.questiontype
                        FROM quiz_questions q
                        left join submitted_answers s on s.question_number = q.question_number and s.round_number = q.round_number and s.team_id = '5e99cb7127f21' 
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
                    <td><strong><?php echo $round_number; ?></strong></td>
                    <td>
                        <?php 
                        if ($qdata["marked"] == 1){
                            //locked
                        }
            }



    }



?>
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