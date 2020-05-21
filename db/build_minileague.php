<?php
include_once("db/db_config.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("db/get_game_state.php");

include_once("funcs/leaderboard_functions.php");


// this just spits out a table.

if (array_key_exists("teamID", $_SESSION) ){

	$team_id = $_SESSION["teamID"];

	$mlq = "select team_id, team_name, person1, person2, person3, person4, total_score, total_marked, R1Correct, R1Marked, R2Correct, R2Marked, R3Correct, R3Marked, R4Correct, R4Marked, R5Correct, R5Marked, R6Correct, R6Marked, R7Correct, R7Marked, R8Correct, R8Marked, R9Correct, R9Marked from complex_leaderboard cl JOIN mini_leagues ml on cl.team_id = ml.league_member WHERE league_owner = '$team_id' ORDER BY total_score desc" ;

	$result = mysqli_query($conn, $mlq);
	

	while($row = $result->fetch_assoc()){
		$minileague[] = $row;
	};


}






// Initial data for ranking scores
$prev_total = 0;
$current_rank = 0;
$repeats = 1;
?>
<form action="leaderboard.php" method="POST">

	<table id="minileague" width="100%" class="table table-condensed dtr-inline collapsed table-hover display">
	   <thead> 
	    <tr><td style="max-width: 20px"><span class="text-muted">Remove</span></td>
			<td><strong>Rank</strong></td>
	        <td style="min-width: 40%"><strong>Team Name</strong></td>
			<?php 
				for ($i = 1; $i <= $current_round; $i++){
					?>
					<td><strong>Rnd. <?php echo $i; ?></strong></td> 
				<?php 
				} ?> <!--  end for loop (header) -->
			<td><strong>Total Score</strong></td>
		</tr>
	   </thead>
	<?php
	foreach($minileague as $ml){

		$teammembers = $ml["person1"];
		if (strlen($ml["person2"])>1){
			$teammembers = $teammembers . ", ". $ml["person2"];
		}

		if (strlen($ml["person3"])>1){
			$teammembers = $teammembers . ", ". $ml["person3"];
		}

		if (strlen($ml["person4"])>1){
			$teammembers = $teammembers . ", ". $ml["person4"];
		}
		
		/*
		 * If total has changed from previous line to this
		 * then increase the rank by the number of times the 
		 * previous score repeated.
		 * Otherwise just count another repeat
		 * */
		if ($prev_total != (int)$ml["total_score"]) {
			
			$prev_total = $ml["total_score"];
			$current_rank = $current_rank + $repeats;
			$repeats = 1;
		} else {
			$repeats = $repeats + 1;
		}
		?>

	<tr <?php if (iscurrent($ml["team_id"])) { echo 'class="info"'; } ?>>
		<?php echo remove_team($ml["team_id"]); ?>

		<td><p><?php echo $current_rank; ?></p></td>
		<td><p><strong><?php echo $ml["team_name"];?></strong>   <?php echo reportlink($lb["team_id"]);?><br>
			<span class="small"><?php echo $teammembers; ?></span> </p>

		</td>


		<?php 
			for ($i = 1; $i <= $current_round; $i++){
				?>
				<td><?php echo $ml[FindIndex($i, 'Correct')]; ?> /<small><?php echo $ml[FindIndex($i, 'Marked')]; ?></small></td> 
			<?php 
			} // end for loop (header)
			?>	
		<td><strong><?php echo $ml["total_score"]; ?></strong> /<small><?php echo $ml["total_marked"]; ?></small></td>
		</tr>

		<?php
	}
	?>
	</table>
<button type="submit" class="btn btn-danger btn-xs">Remove from League</button> <span class="text-muted">You can always add them back later...</span>
</form>

<!-- DataTables -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function() {
          $('#minileague').DataTable( {
              "lengthChange": false,
              "paging": false
          });
      });
  </script>