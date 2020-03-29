<?php 

include ("db/db_config.php");
session_start();

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

if (array_key_exists("teamID", $_SESSION)){
	$teamExists = FALSE;
	foreach($teams_list as $team){
		if ($_SESSION['teamID'] == $team["team_id"]){

			$_SESSION['teamName'] = $team["team_name"];
			$teamExists = TRUE;
		}
	}
	if (!$teamExists){
		unset($_SESSION['teamID']);
		unset($_SESSION['teamName']);
	}
}

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

 <script
			  src="https://code.jquery.com/jquery-3.4.1.min.js"
			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			  crossorigin="anonymous"></script>

<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
<link rel="manifest" href="favicon/site.webmanifest">

    <title>Socially Distant Pub Quiz</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="vidcontrols.css" >


</head>
<body>
<div class="container">


<div class="row">

  <div class="col-xs-12 col-md-5">
<img src="thepanickedshopper.jpg" class="img-responsive img-circle" max alt="Responsive image" style="max-height: 200px; max-width: 200px;">

<h1>Socially Distant Pub Quiz</h1>

<h4>Now with 100% less human contact</h4>
<span class="pull-left"><a href="about.html" target="_blank">How to Play</a></span>
<span class="pull-right">
	Tweet us: <a href="https://twitter.com/davidharrison92" target="_blank">@Dave</a>, <a href="https://twitter.com/ElectricBloo" target="_blank">@Lighty</a>, <a href="https://twitter.com/PubQuizStreams" target="_blank">@Quiz</a>    
</span>
<br/>
<br/>
<?php if (array_key_exists("teamID", $_SESSION)){ ?>
	<p>Your team is <?php echo $_SESSION["teamName"]?></p>
	<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/release_session.php' ?>">Not you?</a>
<?php } ?>

</div>

<div id="vidcontainer" class="col-xs-12 col-md-5 pull-right">
<?php if ($show_video == 1) {
	?>
<iframe id="ytembed" class="miniplayer" <?php echo 'src="https://www.youtube-nocookie.com/embed/'.$ytID .'?controls=0&autoplay=1" '; ?>frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
  <p><a class="btn btn-primary btn-lg" href="newteam.php" role="button">Register your team!</a></p>
</div>
<?php

 }

 if ($allow_signup == 1) {
?>
<div class="row">
<p class="lead">Teams registered so far: </p>
<div class="col-md-4">
<?php
$teamprinter = 0;
 foreach($teams_list as $team){
	$teamprinter = $teamprinter + 1;
	if (count($teams_list) > 14 and $teamprinter % 15 == 0 and $teamprinter > 0){
		// every 10th row, start a new column
		echo '</div><div class="col-md-4">';
	}
	echo "<li>".$team["team_name"]."</li>";
}
	?>
</ul>
</div>
</div> 
<a href="index.php">Refresh</a>
</div> <!-- end row for game start -->


<?php 
}

if ($current_round > 0) {
	?>

<div class="row">

	<button type="button" class="btn btn-default" id="bigplayer">Video Only</button>
	<button type="button" class="btn btn-default hidden" id="showanswers">Show Answer Sheet</button>

</div>

<div id="answersheet" class="col-xs-12 col-md-9">

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

<script>

	$('#bigplayer').click(function(){
console.log('bigplayer clicked');
	 	$('#answersheet').toggleClass('hidden visible');
	 	$('#bigplayer').toggleClass('hidden visible');
		$('#vidcontainer').removeClass('col-md-5 pull-right').addClass('col-md-12');
		$('#showanswers').toggleClass('hidden visible');
		$('#ytembed').removeClass('miniplayer').addClass('fullplayer');
});
	$('#showanswers').click(function(){
console.log('showanswers clicked');
	 	$('#answersheet').toggleClass('visible hidden');
	 	$('#bigplayer').toggleClass('visible hidden');
		$('#vidcontainer').removeClass('col-md-12').addClass('col-md-5 pull-right');
		$('#showanswers').toggleClass('visible hidden');
		$('#ytembed').removeClass('fullplayer').addClass('miniplayer');
});

</script>



</div> <!-- /container -->
</body>
</html>

<?php 
	mysqli_close($conn);
?>

