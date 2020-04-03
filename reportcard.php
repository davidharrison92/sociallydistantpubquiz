<?php
include("db/db_config.php");
session_start();


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
    $qdata_query = "SELECT s.round_number, r.round_title, s.question_number, s.team_id, s.answer as 'asub', correct , q.answer, q.question
        from submitted_answers s 
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

?>





<?php

if (!isset($question_data)){
    ?>
    <div class="alert alert-warning"><strong>No answers to give out yet!</strong><br>Come back after each round is marked, and see how you did!</div>
<?php
    } else {

    if (array_key_exists("teamID", $_SESSION)){

        $i = 1;
        while(isset($question_data[$i])){

                $qloop = $question_data[$i] ;
            ?>
            <p class="lead">Round Number <?php echo $qloop[$i]["round_number"] . ' - ' . $qloop[$i]["round_title"]; ?> </p>
            <table class="table table-striped">
                <tr>
                    <td width="5%"><strong>#</strong></td>
                    <td width="33%"><strong>Question</strong></td>
                    <td width="28%"><strong>You said...</strong></td>
                    <td width="34%"><strong>Correct?</strong></td>
                </tr>


            <?php
            foreach($qloop as $q_detail){
                if ($q_detail["correct"] == "1"){
                    echo '<tr class="success">';
                    } else {
                    echo '<tr class="danger">';
                }
            
                ?>
                <td><strong><?php echo $q_detail["question_number"]; ?></strong></td>
                <td><?php echo utf8_encode($q_detail["question"]); ?> </td>
                <td><?php echo $q_detail["asub"]; ?> </td>
                <td> <?php       
                    if ($q_detail["correct"] == "1"){
                        //tick
                        echo '<span class="glyphicon glyphicon-ok"></span>';
                    } else {
                        //wrong - show correct answer.
                        echo '<span class="glyphicon glyphicon-remove"></span> '. $q_detail["answer"];
                    } ?>
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
    <form class="form-inline" method="POST" action="your_answers.php">
        <div class="form-group">
            <label for="teamname">Team Name</label>
            <select class="form-control" id="teamname" name="teamID">
            <?php foreach($teams_list as $team){
                echo '<option value="' . $team["team_id"] . '">' . $team["team_name"] . "</option>";
            } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="teamsecret">Team Secret</label>
            <input type="text" class="form-control" id="teamsecret" name="teamsecret" placeholder="Ssssh" required="required" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')">
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>

    </form>


<?php
}



?>
<?php 
	mysqli_close($conn);
?>
</html>

