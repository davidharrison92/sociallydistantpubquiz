<?php 

include ("db/db_config.php");

?>

<form class="form-inline" method="POST" action="submitanswers.php">
<hr>
	<div class="form-inline">
		
	<div class="form-group">
		<label for="teamname">Pick Team</label>
		<select class="form-control" id="teamname">
	<?php foreach($teams_list as $team){
		echo '<option value="' . $team["team_id"] . '">' . $team["team_name"] . "</option>";
	} ?>

		</select>
	</div>

	<div class="form-group">
		<label for="teamsecret">Team Secret</label>
    <input type="text" class="form-control" id="teamsecret" placeholder="Ssssh">
	</div>

	</div> <!-- end team name/secret row -->
<hr>
	<div class="row">
		<p class="lead"><?php echo "Round #" .$current_round . " - " . $round_name; ?></p>
	</div>
<hr>	
	<table class="table table-striped">
  	<tr><td><strong>#</strong></td><td><strong>Header</strong></td></tr>
  	
  	<tr>
  		<td>1</td>
  		<td><input type="text" class="form-control" id="ans1" placeholder="Answer 1"></td>
  	</tr>

	<tr>
  		<td>2</td>
  		<td><input type="text" class="form-control" id="ans2" placeholder="Answer 2"></td>
  	</tr>


	<tr>
  		<td>3</td>
  		<td><input type="text" class="form-control" id="ans3" placeholder="Answer 3"></td>
  	</tr>


	<tr>
  		<td>4</td>
  		<td><input type="text" class="form-control" id="ans4" placeholder="Answer 4"></td>
  	</tr>


	<tr>
  		<td>5</td>
  		<td><input type="text" class="form-control" id="ans5" placeholder="Answer 5"></td>
  	</tr>


	<tr>
  		<td>6</td>
  		<td><input type="text" class="form-control" id="ans6" placeholder="Answer 6"></td>
  	</tr>


	<tr>
  		<td>7</td>
  		<td><input type="text" class="form-control" id="ans7" placeholder="Answer 7"></td>
  	</tr>


	<tr>
  		<td>8</td>
  		<td><input type="text" class="form-control" id="ans8" placeholder="Answer 8"></td>
  	</tr>



	<tr>
  		<td>9</td>
  		<td><input type="text" class="form-control" id="ans9" placeholder="Answer 9"></td>
  	</tr>



	<tr>
  		<td>10</td>
  		<td><input type="text" class="form-control" id="ans10" placeholder="Answer 10"></td>
  	</tr>




  	</table>
