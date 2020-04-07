<?php 

include ("db/db_config.php");

if ($current_round > 0) {

    include("funcs/pictureround.php");

    $get_questions = "SELECT picture_question,
                      CASE WHEN picture_question = 1 THEN question ELSE 'hidden' END AS 'img_address',
                      question_number
                    FROM
                        quiz_questions
                    WHERE
                        round_number = ".$current_round." ORDER BY question_number ASC ;" ;

    $result = mysqli_query($conn, $get_questions);

    while ($row = $result->fetch_assoc()){
        $questions[] = $row;
    }

}

var_dump($questions);


?>

<form class="form-inline" method="POST" action="submitanswers.php">
    <hr>
	<div class="form-inline">
    <?php 
    if (array_key_exists("teamID", $_SESSION)){ ?>
	    <div class="form-group">
		    <label for="teamname">Team Name: <?php echo $_SESSION["teamName"]?></label>
		    <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/release_session.php' ?>">Not you?</a>
	    </div>
        <?php } else { ?>	
	    <div class="form-group">
		    <label for="teamname">Team Name</label>
		    <select class="form-control" id="teamname" name="teamID">
            <?php foreach($teams_list as $team){
                echo '<option value="' . $team["team_id"] . '">' . $team["team_name"] . "</option>";
            } ?>
		    </select>
	    </div>
	</div> <!-- end form header row -->
    <?php } ?>	
    <hr>
	<div class="row">
		<p class="lead"><?php echo "Round #" .$current_round . " - " . $round_name; ?></p>
	</div>
    <hr>	
	<table class="table table-striped">
        <tr><td><strong>#</strong></td><td><strong>Header</strong></td></tr>
        <?php 
        foreach ($questions as $qline){ 
            $name = "ans".strval($qline["question_number"]);
        ?>
            <tr>
                <td><?php echo $qline["question_number"];?></td>
                <td><?php if($qline["picture_question"] == "1") { 
                        echo pictureround($qline["img_address"]); 
                    } ?> 
                    <input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" required="required" placeholder="<?php echo "Answer ". $qline["question_number"]; ?>" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')"></td>
                    <!-- ^ Script should remove any -->
            </tr>
        <?php } ?>

  	</table>
    <input type="hidden" id="roundnumber" name="round_number" value=<?php echo '"'.$current_round.'"'; ?>> 
    <div class="form-inline">
        <div class="form-group">
            <label for="teamsecret">Team Secret</label>
            <input type="text" class="form-control" id="teamsecret" name="secret" placeholder="Ssssh" required="required" onkeyup="this.value = this.value.replace(/[^A-z 0-9]/, '')">
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </div> <!-- end footer row -->
</form>