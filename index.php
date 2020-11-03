<?php include ("db/db_config.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("db/get_game_state.php");

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
<!DOCTYPE html>
<html>
    <head>
       <?php include("htmlheader.php"); ?>
       <title>Socially Distant Pub Quiz</title>
    </head>
    
    <body>
        <div class="container">
            <div class="row">
                <?php include("header.php");?>

            </div> <!-- / header row -->
            <hr>
<!-- 
            <div class="alert alert-nhs">
                <p class="lead">Friday's raffle is raising money for <strong>NHS Charities Together</strong> and <strong>Mind</strong>.</p> 
                <p><a class="btn btn-nhs" href="https://sociallydistant.pub/store/" role="button">Raffle tickets</a><span class="small">    <a class="link-nhs" href="charity.html" target="_blank">More info</a></span></p>
            </div> -->
            <?php
            if (($current_round > 0) and ($quiz_complete == 0)) {
            ?>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <?php
                            include("answersheet.php");
                        ?>
                    </div>
                </div>
                <hr>
            <?php 
            } // suppress answer sheet before game starts

            if ($allow_signup == 1 and $current_round > 0) { 
            ?>
            <div class="row">
                <div class="alert alert-info" role="alert">
                    <a href="newteam.php" class="alert-link"><strong>Be quick!</strong> We're about to start the quiz. Click here quick and enter your team.</a> 
                </div>
                <?php
            }

            if ($current_round == 0) {
                ?>
                <!-- <div class="panel panel-primary">
                    <div class="panel-heading">Beat the Quizmaster!</div>
                    <div class="panel-body">
                        <p>This Friday, it's your chance to ask the questions. Dave and Alex are going to compete against each other. There'll be a punishment for the loser.</p>
                        <p>Send us your questions, don't tell anyone (even your teammates) the answers, though!</p>
                        <p><a class="btn btn-primary" href="https://forms.gle/fZwg8yNDL786iP3W9" role="button">Send in your questions!</a></p>

                    
                    </div>
                </div> -->

                <div class="jumbotron">
                <h1>Lockdown, you say?</h1>
                    <p class="lead">You know what that means...</p>
                    <p>Pub Quiz Streams will return on Friday 6<sup>th</sup> of November at 8:00 GMT</p>
                    <p><a class="btn btn-success btn-lg" href="newteam.php" role="button">Register your team!</a>
                </div>
                <?php
            }
               
            if ($allow_signup == 1) {
                ?>
                <div class="row">
                    <p class="lead"><strong><?php echo count($teams_list); ?> teams</strong> registered already! </p>
                    <div class="col-md-4">
                        <ul>
                            <?php
                            $teamprinter = 0;
                            foreach($teams_list as $team){
                                $teamprinter = $teamprinter + 1;
                                if (count($teams_list) > 14 and $teamprinter % ceil((count($teams_list)+1)/3) == 0 and $teamprinter > 0){
                                    // every 10th row, start a new column
                                    echo '</div><div class="col-md-4">';
                                }
                                echo "<li>".$team["team_name"]."</li>";
                            }
                                ?>
                          <li><a href="newteam.php">Click here to become team #<?php echo count($teams_list)+1; ?>...</a> </li>
                        </ul>
                    </div>
                </div> 
                <a href="index.php">Refresh</a>
                </div> <!-- end row for game start -->
            <?php 
            }

            if ($quiz_complete == 1) {
             ?>
            <div class="col-xs-12 col-md-12">
                <h4>Leaderboard</h4>
    
                    <?php include("db/build_leaderboard.php"); ?>
                <?php 
                } // suppress leaderboard when not in play
                ?>
            </div>
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
            </div>
        </div> <!-- /container -->
    </body>
</html>

<?php 
    mysqli_close($conn);
?>

