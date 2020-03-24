<?php 

include ("db/db_config.php");

// GET CURRENT ROUND

$current_round = 0;

$round_qry = "SELECT roundnumber, round_label from current_round";
$round_res = mysqli_query($conn, $round_qry);

$round_res = mysqli_fetch_row($round_res);
$current_round = $round_res[0];
$round_name = $round_res[1];

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

  	<h1>Socially Distant Pub Quiz</h1>
<h4>Now with 100% less human contact</h4>
<a href="about.html" target="_blank">Help / Privacy / About</a>

</div>

<div class="col-xs-12 col-md-5 pull-right">

<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/wsHIzzmJEkY?controls=0&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


</div>


</div> <!-- / header row -->
<hr>
<?php if ($current_round < 2) { ?>

<div class="row">
<div class="alert alert-info" role="alert">
  <a href="newteam.php" class="alert-link">The quiz hasn't started yet, click here to register a new team!</a>
</div>


<p class="lead">Teams registered so far: </p>
<?php foreach($teams_list as $team){
	echo "<li>".$team["team_name"]."</li>";
}
	?>
</ul>

<a href="index.php">Refresh</a>
</div> <!-- end row for game start -->
<?php } ?>


<?php if ($current_round > 0) {
	?>
<div class="col-xs-12 col-md-9">
	<?php
		include("answersheet.php");
	?>
</div>

<?php 
} // suppress answer sheet before game starts


 if ($current_round > 1) {
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

