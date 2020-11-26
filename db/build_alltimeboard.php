<?php
include_once("db/db_config.php");
include_once("funcs/leaderboard_functions.php");

if (!isset($current_round)){
	// this won't be needed if embeded on index, but will be needed if running in standalone window.
	include_once("db/get_game_state.php");
}



// this just spits out a table.



$alltimeqry = "select t.team_id, t.team_name, t.person1, t.person2, t.person3, t.person4,
				a.quiz_id, a.quiz_date, a.quiz_correct, a.quiz_marked,
				a.total_marked, a.total_correct, a.quizzes_played
				from alltime_leaderboard a
				join teams t on t.team_id = a.team_id
				order by a.total_correct desc, a.quiz_date asc";


$result = mysqli_query($conn, $alltimeqry);
$rows = array();
while($row = $result->fetch_assoc()){
	$alltimeboard[] = $row;
};

// Initial data for ranking scores
$prev_total = 0;
$current_rank = 0;
$repeats = 1;
$prev_team_id = "";

?>

<table id="alltimeleaderboard" class="table table-condensed dtr-inline collapsed table-hover display" >

<thead>
	<tr>
		<td><strong>#</strong></td>
		<td style="min-width: 99%;"><strong>Team Name</strong></td>
		<td><strong>Quizzes Played</strong></td>
		<td><strong>% Questions Answered Correctly</strong></td>
		<td><strong>Total Score</strong></td>
	</tr>
</thead>

<?php foreach ($alltimeboard as $lb){
	//var_dump($lb);
	if ($lb["total_correct"] != $prev_total){
		$prev_total = $lb["total_correct"];
		$current_rank = $current_rank + 1; 
	}


	if ($prev_team_id != $lb["team_id"]){
		// New Team - create a new row
		$prev_team_id = $lb["team_id"];

		// rewrite team members string

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

		// Create the Row

		?>
		<tr>
			<td>
				<?php echo $current_rank; ?>
			</td>

			<td><strong><?php echo $lb["team_name"]; ?></strong><br>
				<span class="small"><?php echo $teammembers; ?> </span>
			</td>
			
			<td>
				<?php echo $lb["quizzes_played"]; ?>
			</td>

			<td>
				<?php if ($lb["total_marked"] > 0) {
					echo round(($lb["total_correct"] / $lb["total_marked"]) * 100, 0);
				} else {
					echo "-";
				} ?>
				%
			</td>

			<td>
				<?php echo $lb["total_correct"]; ?>
			</td>

		</tr>

		
	<?php
	} // end new team ID


}

?>


</table>



  <!-- DataTables -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function() {
          $('#alltimeleaderboard').DataTable( {
              "lengthChange": false,
              "paging": false
          });
      });
  </script>