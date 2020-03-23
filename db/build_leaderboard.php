<?php
include("db/db_config.php");

// this just spits out a table.

$lbq = "select 
	t.team_name, sum(ifnull(s.Score,0)) as 'Score', t.person1, t.person2, t.person3, t.person4
	FROM teams t
	LEFT JOIN team_round_scores s on s.teamID = t.team_id
	GROUP by team_name, t.team_id
	ORDER BY sum(s.Score) desc" ;


$result = mysqli_query($conn, $lbq);
$rows = array();
while($row = $result->fetch_assoc()){
	$leaderboard[] = $row;
};

//var_dump($leaderboard);


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
	<td><?php echo $lb["Score"];?> </td>
</tr>

	<?php
}
