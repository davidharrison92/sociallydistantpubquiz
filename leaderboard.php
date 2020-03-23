<?php
include("db/db_config.php");
?>
<head>
    <title>Socially Distant Pub Quiz</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">

<h1>Leaderboard:</h1>
<h4>Socially Distanced Pub Quiz</h4>


<table class="table table-condensed table-hover">
<tr>
	<td><strong>Team Name</strong></td>
	<td><strong>Score</strong></td>
<?php include("db/build_leaderboard.php"); ?>

</table>


</div> <!-- end container -->
</body>
</html>