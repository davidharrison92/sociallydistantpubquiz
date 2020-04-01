<?php
include("db/db_config.php");

if (!isset($current_round)){
	// this won't be needed if embeded on index, but will be needed if running in standalone window.
	include("db/get_game_state.php");
}

function FindIndex($round, $scoretype){
	//round 1-9
	//scoretype (Marked, Correct)
	return 'R'.$round.$scoretype;
}

// this just spits out a table.

$lbq = "select team_id, team_name, person1, person2, person3, person4, total_score, total_marked, R1Correct, R1Marked, R2Correct, R2Marked, R3Correct, R3Marked, R4Correct, R4Marked, R5Correct, R5Marked, R6Correct, R6Marked, R7Correct, R7Marked, R8Correct, R8Marked, R9Correct, R9Marked from complex_leaderboard" ;


$result = mysqli_query($conn, $lbq);
$rows = array();
while($row = $result->fetch_assoc()){
	$leaderboard[] = $row;
};
?>

<table class="table table-condensed table-hover">
    <tr>
        <td><strong>Team Name</strong></td>
		<?php 
			for ($i = 1; $i <= $current_round; $i++){
				?>
				<td><strong>Rnd. <?php echo $i; ?></strong></td> 
			<?php 
			} ?> <!--  end for loop (header) -->
		<td><strong>Total Score</strong></td>
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

	?>

<tr>
	<td><p><strong><?php echo $lb["team_name"];?></strong>
		<span class="small"><?php echo $teammembers; ?></span> </p>

	</td>

	<?php 
		for ($i = 1; $i <= $current_round; $i++){
			?>
			<td><?php echo $lb[FindIndex($i, 'Correct')]; ?> /<small><?php echo $lb[FindIndex($i, 'Correct')]; ?></small></td> 
		<?php 
		} // end for loop (header)
		?>	
	<td><strong><?php echo $lb["total_score"]; ?></strong> /<small><?php echo $lb["total_marked"]; ?></small></td>
	</tr>

	<?php
}
?>
</table