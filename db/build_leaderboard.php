<?php
include_once("db/db_config.php");
include_once("funcs/leaderboard_functions.php");

if (!isset($current_round)){
	// this won't be needed if embeded on index, but will be needed if running in standalone window.
	include_once("db/get_game_state.php");
}



// this just spits out a table.

$lbq = "select team_id, team_name, person1, person2, person3, person4, total_score, total_marked, R1Correct, R1Marked, R2Correct, R2Marked, R3Correct, R3Marked, R4Correct, R4Marked, R5Correct, R5Marked, R6Correct, R6Marked, R7Correct, R7Marked, R8Correct, R8Marked, R9Correct, R9Marked from complex_leaderboard" ;



$result = mysqli_query($conn, $lbq);
$rows = array();
while($row = $result->fetch_assoc()){
	$leaderboard[] = $row;
};

// Initial data for ranking scores
$prev_total = 0;
$current_rank = 0;
$repeats = 1;
?>



<table id="mainleaderboard" class="table table-condensed dtr-inline collapsed table-hover display">
	<thead>
		<tr>			
			<?php 
			if (array_key_exists("teamID", $_SESSION)){
				?>
				  <form action="leaderboard.php" method="POST">
				  <td>
				  	<button type="submit" class="btn btn-info btn-xs">Add to<br>League</button>
				  </td>
				 <?php
			} ?> 

			<td><strong>Rank</strong></td>
	        <td style="min-width: 40%;"><strong>Team Name</strong></td>
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
foreach($leaderboard as $lb){


	$teammembers = $lb["person1"];
	if (strlen($lb["person2"])>1){
		$teammembers = $teammembers . ", ". $lb["person2"];
	}

	if (strlen($lb["person3"])>1){
		$teammembers = $teammembers . ", ". $lb["person3"];
	}

	if (strlen($lb["person4"])>1){
		$teammembers = $teammembers . ", ". $lb["person4"];
	}
	
	/*
	 * If total has changed from previous line to this
	 * then increase the rank by the number of times the 
	 * previous score repeated.
	 * Otherwise just count another repeat
	 * */
	if ($prev_total != (int)$lb["total_score"]) {
		
		$prev_total = $lb["total_score"];
		$current_rank = $current_rank + $repeats;
		$repeats = 1;
	} else {
		$repeats = $repeats + 1;
	}
	?>

	<tr class="<?php if (iscurrent($lb["team_id"])) { echo 'info'; } ?> parent" role="row" >
		<?php echo add_team($lb["team_id"]); ?>
		<td><p><?php echo $current_rank; ?></p></td>
		<td><p><strong><?php echo $lb["team_name"];?></strong><br>
			<span class="small"><?php echo $teammembers; ?></span> </p>

		</td>

		<?php 
			for ($i = 1; $i <= $current_round; $i++){
				?>
				<td><?php echo $lb[FindIndex($i, 'Correct')]; ?> /<small><?php echo $lb[FindIndex($i, 'Marked')]; ?></small></td> 
			<?php 
			} // end for loop (header)
			?>	
		<td><strong><?php echo $lb["total_score"]; ?></strong> /<small><?php echo $lb["total_marked"]; ?></small></td>
	</tr>
	

<?php
}
?>

	

</table>
<?php
if (array_key_exists("teamID",$_SESSION)){
	?>
	<button type="submit" class="btn btn-info btn-xs">Add to My League</button>
</form>
<?php
	} 
?>


  <!-- DataTables -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function() {
          $('#mainleaderboard').DataTable( {
              "lengthChange": false,
              "paging": false
          });
      });
  </script>