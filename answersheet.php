<?php 

include ("db/db_config.php");
session_start();

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

	<div class="form-group">
		<label for="teamsecret">Team Secret</label>
    <input type="text" class="form-control" id="teamsecret" name="secret" placeholder="Ssssh">
	</div>

	</div> <!-- end team name/secret row -->
<?php } ?>	
<hr>

	<div class="row">
		<p class="lead"><?php echo "Round #" .$current_round . " - " . $round_name; ?></p>
	</div>
    <hr>	
	<table class="table table-striped">
        <tr><td><strong>#</strong></td><td><strong>Header</strong></td></tr>
        <?php 
        for ($i=0; $i<9; $i++){ 
            $name = "ans".strval($i+1);
        ?>
            <tr>
                <td><?php echo strval($i+1);?></td>
                <td><input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" placeholder="<?php echo "Answer ".strval($i+1); ?>"></td>
            </tr>
        <?php } ?>

  	</table>
    <input type="hidden" id="roundnumber" name="round_number" value=<?php echo '"'.$current_round.'"'; ?>> 

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </div>
</form>