<?php 

include ("db/db_config.php");

// save marked answers first. 
$error = -1;
$errormsg = "";
if ( !empty($_POST) ) {

//var_dump($_POST);

	$score = mysqli_real_escape_string($conn,$_POST["score"]);
	$teamID = mysqli_real_escape_string($conn, $_POST["teamUUID"]);
	$round = mysqli_real_escape_string($conn,$_POST["roundnumber"]);
	$secret = mysqli_real_escape_string($conn,$_POST["adminpass"]);

	 $pwdqry = "SELECT COUNT(*) as 'Count' from admin_password where password = '$secret'";

            $checkpwd = $conn->query($pwdqry);
           $pwd_res = $checkpwd->fetch_assoc();
                    
           
       if (($pwd_res["Count"] * 1) >= 1) {
         
       		//mark it.	

           	$ins_score = "INSERT INTO team_round_scores(teamID, Round, Score) VALUES ('$teamID', $round, $score)";

           	echo $ins_score;

           	 if (mysqli_query($conn,$ins_score)){
		        $error= 2;

		      } else {
		  		  $error = 1;

		       $errormsg = "Failed database insert. Tell dave about this: " . $ins_score;
			}

       } else {
          
          //reject password
       		$error = 1;
       		$errormsg = "Invalid admin password. Idiot.";

       }

} // end of _POST processing

// GET List of teams
$to_mark_teams_list = array();


$teams_qry = "select distinct s.team_id, t.team_name, s.round_number from submitted_answers s
	JOIN teams t on s.team_id = t.team_id
	LEFT JOIN team_round_scores r on r.teamID = s.team_id
	WHERE r.score IS NULL;";


    $result = mysqli_query($conn,$teams_qry);

    $rows = array();

    while($row = $result->fetch_assoc()){
        $to_mark_teams_list[] = $row;

    };

//var_dump($to_mark_teams_list);

// fetch the teams who have submitted, but not marked.

// something like select distinct teamID from submitted_answers with a missing round_scores value.


?>

<html>
<head>
    <title>Socially Distant Pub Quiz | Admin</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h3>Mark answers:</h3>


<?php
	if ($error == 2){
	
		echo '<div class="alert alert-success">Successfully saved score</div>';
	
	} elseif ($error == 1) {
		echo '<div class="alert alert-danger">'.$errormsg.'</div>';
	}

if (count($to_mark_teams_list) == 0){
	echo "Nothing to mark!";
}

// for each of the teamIDs:

foreach ($to_mark_teams_list as $teamdata){

	// fetch their answers into an array.


$answers_query = "SELECT question_number, answer FROM submitted_answers where team_id = '" .$teamdata["team_id"] . "' and round_number = '" . $teamdata["round_number"] . "' ";

    $result = mysqli_query($conn,$answers_query);

    $rows = array();
    unset($answers_data);

    while($row = $result->fetch_assoc()){
        $answers_data[] = $row;

    };

    //var_dump($answers_data);


?>

<p class="lead"><?php echo $teamdata["team_name"]; ?></p>
<table class="table table-condensed">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Answer</strong></td>
	</tr>

<?php foreach($answers_data as $ans){
	?>
	<tr>
		<td><?php echo $ans["question_number"];?></td>
		<td><?php echo $ans["answer"];?></td>
	</tr>
<?php
}
?>	
</table>

<form class="form-inline" action="mark_answers.php" method="post">
  <div class="form-group">
    <label for="exampleInputName2">Score</label>
    <input type="text" class="form-control" id="score" name="score" placeholder="/10">
  </div>

  <div class="form-group">
    <label for="adminpass">Admin Password</label>
    <input type="text" class="form-control" id="adminpass" name="adminpass" placeholder="sssh">
  </div>
    <input type="hidden" id="teamUUID" name="teamUUID" value=<?php echo '"'. $teamdata["team_id"] . '"'; ?>> 
    <input type="hidden" id="roundnumber" name="roundnumber" value=<?php echo '"'. $teamdata["round_number"] . '"';?> >

  <button type="submit" class="btn btn-default">Save score</button>
</form>


<?php 



} // end of foreach - team data

?>




</div><!-- /container -->
</body>
</html>