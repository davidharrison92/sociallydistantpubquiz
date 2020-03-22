<?php

include("db/db_config.php");

// clean the results


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

var_dump($answers);

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

<p class="lead"> Thanks for submitting your answers...</p>


<table class="table table-striped">

  	<tr><td><strong>#</strong></td><td><strong>Header</strong></td></tr>

<?php 

$question_number = 1;
foreach ($answers as $ans){

echo "<td>". $question_number."</td> <td>";
	// Does it exist?

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

$question_number++;
echo "</tr>";
}

?>

</table>



</div>
</body>
</html>

