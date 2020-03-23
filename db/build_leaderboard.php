<?php
include("db/db_config.php");

// this just spits out a table.

$lbq = "select 
	t.team_name, sum(s.Score) as 'Score'
	FROM teams t
	JOIN team_round_scores s on s.teamID = t.team_id
	GROUP by team_name, t.team_id
	ORDER BY sum(s.Score) desc" ;


$result = mysqli_query($conn, $lbq);
$rows = array();
while($row = $result->fetch_assoc()){
	$leaderboard[] = $row;
};



foreach($leaderboard as $lb){
	?>

<tr>
	<td><?php echo $lb["team_name"];?></td>
	<td><?php echo $lb["Score"];?> </td>
</tr>

	<?php
}
