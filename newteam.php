<?php 

include ("db/db_config.php");



$round_qry = "SELECT show_video, allow_signup, youtubeID from current_round";
$round_res = mysqli_query($conn, $round_qry);

$round_res = mysqli_fetch_row($round_res);
$show_video = $round_res[0];
$allow_signup = $round_res[1];
$ytID = $round_res[2];

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
<a href="index.php"><img src="thepanickedshopper.jpg" class="img-responsive img-circle" max alt="Responsive image" style="max-height: 250px; max-width: 250px;"></a>

  	<h1>Socially Distant Pub Quiz</h1>
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

<h4>Register a new team!</h4>

<?php if($allow_signup == 0) { 
    //signup forbidden
  ?>

<div class="alert alert-danger"><p class="lead">Too slow!</p>
  <p>We're already underway, and aren't accepting any new entries into the quiz at this time. We hope we see you for the next one!</p>
  <a class="btn btn-warning btn-sm" href="index.php" role="button">Back to main page</a>


<?php 
} else {
  //signups allowed
?>

<p>Fill in your details below:</p>
<ul>
  <li>Teams can have up to 4 members</li>
  <li>It's best if you nominate a captain to fill in the answer sheets</li>
</ul>
<hr>


<form class="form-horizontal" method="post" action="./submit_team.php">
  <div class="form-group">
    <label for="team_name" class="col-sm-2 control-label">Team Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="team_name" name="team_name" placeholder="The General's Knowledge" required="required">
    </div>
  </div>
  <div class="form-group">
    <label for="email"class="col-sm-2 control-label">Email address</label>
        <div class="col-sm-10">
      <input type="email" class="form-control" id="email" name="team_email" placeholder="Email" required="required">
          <p class="help-block">We'll let you know before the quiz starts. We will not spam you or share this. <a href="about.html" target="_blank">More info</a></p>
          <input type="checkbox" value="1" name="livestream"><strong> I'm happy for you to include me in the live video</strong> (you'll need to provide a <em>GMail</em> address)

      </div>
  </div>
  <div class="form-group">
    <label for="names" class="col-sm-2 control-label">Team Members</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="member1" name="member1" placeholder="Team Member #1" required="required">
      <input type="text" class="form-control" id="member2" name="member2" placeholder="Team Member #2">
      <input type="text" class="form-control" id="member3" name="member3" placeholder="Team Member #3">
      <input type="text" class="form-control" id="member4" name="member4" placeholder="Team Member #4">
    </div>
  </div>

  <div class="form-group">
    <label for="team_secret" class="col-sm-2 control-label">Team Secret</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="team_secret" id="team_secret" placeholder="I have the high ground" required="required">
          <span id="helpBlock" class="help-block">This is a secret that you'll need to submit with your answers for each round. Nice try fraudsters.</span>
          
    </div>
  </div>



  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign up!</button>
    </div>
  </div>
</form>

<?php }
?>




</div> <!-- /container -->
</body>
</html>

