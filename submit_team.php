<?php 

include ("db/db_config.php");

var_dump($_POST);

$error = false;

if ( empty($_POST) ) {

  $error = true;
  $error_reson[] = "Please enter the details on the form below";

  echo "nothing set";

} else {
  //POST is set.

  //clean_values
    $team_name = mysqli_real_escape_string($_POST["team_name"]);
    $tm_1 = mysqli_real_escape_string($_POST["member1"]);
    $tm_2 = mysqli_real_escape_string($_POST["member2"]);
    $tm_3 = mysqli_real_escape_string($_POST["member3"]);
    $tm_4 = mysqli_real_escape_string($_POST["member4"]);

    $team_secret = mysqli_real_escape_string($_POST["team_secret"]);

}



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



</div> <!-- / header row -->




</div> <!-- /container -->
</body>
</html>

