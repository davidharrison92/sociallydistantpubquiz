<?php 

include ("db/db_config.php");

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
  <div class="col-xs-12 col-md-8"><h1>Socially Distant Pub Quiz</h1>
<h3><em>Now with 100% less human contact</em></h3></div>

<div class="col-xs-12 col-md-4 pull-right"><a href="about.html" target="_blank">Help / Privacy / About</a></div>


</div> <!-- / header row -->
<hr>
<h4>Register a new team!</h4>
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
      <input type="text" class="form-control" id="team_name" name="team_name" placeholder="The General's Knowledge">
    </div>
  </div>
  <div class="form-group">
    <label for="names" class="col-sm-2 control-label">Team Members</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="member1" name="member1" placeholder="Team Member #1">
      <input type="text" class="form-control" id="member2" name="member2" placeholder="Team Member #2">
      <input type="text" class="form-control" id="member3" name="member3" placeholder="Team Member #3">
      <input type="text" class="form-control" id="member4" name="member4" placeholder="Team Member #4">
    </div>
  </div>

  <div class="form-group">
    <label for="team_secret" class="col-sm-2 control-label">Team Secret</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="team_secret" id="team_secret" placeholder="I have the high ground">
          <span id="helpBlock" class="help-block">This is a secret that you'll need to submit with your answers for each round. Nice try fraudsters.</span>
          
    </div>
  </div>



  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign up!</button>
    </div>
  </div>
</form>



</div> <!-- /container -->
</body>
</html>

