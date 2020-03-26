<?php 

include ("db/db_config.php");

// GET CURRENT ROUND


$round_qry = "SELECT roundnumber, round_label, show_video, allow_signup, youtubeID from current_round";
$round_res = mysqli_query($conn, $round_qry);

$round_res = mysqli_fetch_row($round_res);
$current_round = $round_res[0];
$round_name = $round_res[1];
$show_video = $round_res[2];
$allow_signup = $round_res[3];
$ytID = $round_res[4];

include("db/get_teams.php");


?>

<html>
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-161589071-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-161589071-1');
</script>



    <title>Socially Distant Pub Quiz</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">


<div class="row">

  <div class="col-xs-12 col-md-7">
<img src="thepanickedshopper.jpg" class="img-responsive img-circle" max alt="Responsive image" style="max-height: 250px; max-width: 250px;">

  	<h1>Staging - Socially Distant Pub Quiz</h1>
<h4>Now with 100% less human contact</h4>
<a href="about.html" target="_blank">Help / Privacy / About</a>

</div>

<div class="col-xs-12 col-md-5 pull-right">
<?php if ($show_video == 1) {
	?>
<iframe width="560" height="315" <?php echo 'src="https://www.youtube-nocookie.com/embed/'.$ytID .'?controls=0&autoplay=1" '; ?>frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<?php
} // end show vid
?>

</div>


</div> <!-- / header row -->
<hr>
<?php if ($allow_signup == 1 and $current_round > 0) { 
?>
<div class="row">
<div class="alert alert-info" role="alert">
  <a href="newteam.php" class="alert-link"><strong>Be quick!</strong> We're about to start the quiz. Click here quick and enter your team.</a>
</div>

<?php
}

if ($current_round == 0 and $allow_signup == 1) {
?>
<div class="jumbotron">
  <h1>Coming very soon!</h1>
  <p>Mark your diaries, the next quiz will be on Friday at 8PM (UK Time). In the meantime...</p>
  <p><a class="btn btn-primary btn-lg" href="#" role="button">Register your team!</a></p>
</div>
<?php

 } 
?>

<p class="lead">Teams registered so far: </p>
<?php foreach($teams_list as $team){
	echo "<li>".$team["team_name"]."</li>";
}
	?>
</ul>

<a href="index.php">Refresh</a>
</div> <!-- end row for game start -->


<?php if ($current_round > 0) {
	?>
<div class="col-xs-12 col-md-9">
	<?php
		include("answersheet.php");
	?>
</div>

<?php 
} // suppress answer sheet before game starts


 if ($current_round > 	1) {
	?>
<div class="col-xs-12 col-md-3">
	<h4>Leaderboard</h4>
	<table class="table table-condensed table-hover">
	<tr>
		<td><strong>Team</strong></td>
		<td><strong>Score</strong></td>
	</tr>
	<?php include("db/build_leaderboard.php"); ?>
</table>
<?php 
} // suppress leaderboard when not in play
?>


</div> <!-- /container -->
</body>
</html>

<?php 
	mysqli_close($conn);
?>

