<?php
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-161589071-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-161589071-1');
</script>

include("db/db_config.php");

// clean the results

var_dump($_POST);
if ( empty($_POST) ) {

  $error = true;
  $error_reason[] = "Please enter the details on the form below";

  echo "nothing set";
} else {

// CLEAN IT, BUILD AN ARRAY

	$answers[0] = mysqli_real_escape_string($conn,$_POST["ans1"]);
	$answers[1] = mysqli_real_escape_string($conn,$_POST["ans2"]);
	$answers[2] = mysqli_real_escape_string($conn,$_POST["ans3"]);
	$answers[3] = mysqli_real_escape_string($conn,$_POST["ans4"]);
	$answers[4] = mysqli_real_escape_string($conn,$_POST["ans5"]);
	$answers[5] = mysqli_real_escape_string($conn,$_POST["ans6"]);
	$answers[6] = mysqli_real_escape_string($conn,$_POST["ans7"]);
	$answers[7] = mysqli_real_escape_string($conn,$_POST["ans8"]);
	$answers[8] = mysqli_real_escape_string($conn,$_POST["ans9"]);
	$answers[9] = mysqli_real_escape_string($conn,$_POST["ans10"]);


	$teamID = mysqli_real_escape_string($conn, $_POST["teamID"]);
	$teamsecret = mysqli_real_escape_string($conn, $_POST["secret"]);
	$round = mysqli_real_escape_string($conn,$_POST["round_number"]);


	} // end of if POST empty.



// TODO: CHECK SECRET AND TEAM ID

 //CHECK THE TEAM FOR VALIDITY
$name_query = "SELECT COUNT(*) as 'Count' from teams where team_id = '$teamID' and secret = '$teamsecret'";
$check = $conn->query($name_query);
$check = $check->fetch_assoc();
        

if (($check["Count"] * 1) >= 1) {
$team_secret_pass = 1;

} else {
  //new (allowable) teamname
	$team_secret_pass = 0;
}

print $team_secret_pass;

//var_dump($answers);

$field_template = 	'<td><input type="text" class="form-control" id="ansQQQ" name="ansQQQ" placeholder="Answer QQQ" value="ZZZ"></td>'


?>

<html>
<head>
    <title>Socially Distant Pub Quiz</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">


<div class="row">
  <div class="col-xs-12 col-md-8"><h1>Socially Distant Pub Quiz</h1>
<h3><em>Now with 100% less human contact</em></h3></div>


</div>
<hr>

<?php if ($team_secret_pass == 1) {
echo '<div class="alert alert-success"> Thanks for submitting your answers...</div>';
} else {
include("db/get_teams.php"); // this will fetch the team names, as it does on the homepage.

	?>
<div class="alert alert-danger">Your team secret was incorrect, please try again!</div>
<form class="form-inline" method="POST" action="submitanswers.php">
	
	<div class="form-inline">
		
	<div class="form-group">
		<label for="teamname">Pick Team</label>
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

	 <!-- end team name/secret row -->


<?php

} //header - team_secret_pass check
?>


<table class="table table-striped">

  	<tr><td><strong>#</strong></td><td><strong>Header</strong></td></tr>

<?php 

$question_number = 1;
foreach ($answers as $ans){

echo "<td>". $question_number."</td>";

if ($team_secret_pass == 1) {
echo "<td> Submitted: ";
	$check_exists_qry = "SELECT COUNT(*) as 'Count' from submitted_answers where question_number ='$question_number' and round_number = '$round' and team_id = '$teamID'";
            $exists = $conn->query($check_exists_qry);
           $exists = $exists->fetch_assoc();


      if (($exists["Count"] * 1) >= 1) {
		// yes - update it

      $ans_query = "UPDATE submitted_answers set answer = '$ans' where question_number ='$question_number' and round_number = '$round' and team_id = '$teamID'";


      } else {
      	// no - insert it

      $ans_query = "INSERT INTO submitted_answers (team_id, round_number, question_number, answer) VALUES ('$teamID', '$round', '$question_number', '$ans');";

      }

    if (mysqli_query($conn,$ans_query)){
    	echo $ans . "</td>";
    }
	// spew it back out into a table

$ans_query = "";

} else {
	// this code runs if the team secret is NOT matched. Allow retry.

$retry_field = str_replace("QQQ", $question_number, $field_template);
$retry_field = str_replace("ZZZ", $ans, $retry_field);
echo $retry_field;




}


$question_number++;
echo "</tr>";
}

?>

</table>

<?php if ($team_secret_pass == 0) {
// close off the form elements
	?>

	  <input type="hidden" id="roundnumber" name="round_number" value=<?php echo '"'.$round.'"'; ?>> 
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>


<?php
}

?>



</div>
</body>
</html>

