<?php 

include ("db/db_config.php");

// GET CURRENT ROUND

$current_round = 0;

$round_qry = "SELECT roundnumber from current_round";
$round_res = mysqli_query($conn, $round_qry);

$round_res = mysqli_fetch_row($round_res);
$current_round = $round_res[0];





?>

<html>
<head>
    <title>Socially Distant Pub Quiz</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">
<form class="form-horizontal">


<div class="row">
  <div class="col-xs-12 col-md-8"><h1>Socially Distant Pub Quiz</h1>
<h3><em>Now with 100% less human contact</em></h3></div>



</div> <!-- / header row -->

<?php if ($current_round == 0) { ?>

<div class="row">
<div class="alert alert-info" role="alert">
  <a href="#" class="alert-link">The quiz hasn't started yet, click here to register a new team!</a>
</div>


<p class="lead">Teams registered so far: </p>
<ul>
	<li>test 1</li>
	<li>test 2</li>
	<li>test tickles</li>
</ul>

<a href="index.php">Refresh</a>


<?php } //end if round 0 ?>




</form> <!-- this whole thing was a form?!  -->
</div> <!-- /container -->
</body>
</html>

