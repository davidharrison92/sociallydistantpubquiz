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
				a.total_marked, a.total_correct
				from alltime_leaderboard a
				join teams t on t.team_id = a.team_id";


$result = mysqli_query($conn, $alltimeqry);
$rows = array();
while($row = $result->fetch_assoc()){
	$leaderboard[] = $row;
};

// Initial data for ranking scores
$prev_total = 0;
$current_rank = 0;
$repeats = 1;


var_dump($leaderboard);

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