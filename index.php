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

                <div class="row">

                <div id="carousel-example-generic" class="carousel slide col-md-6" data-ride="carousel">
                <style>
                .item img {
                    width:100%
                    }

                </style>

                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="4"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="5"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="6"></li>

                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img src="img/homepage/yt_shavedheads.png" class="img-responsive" alt="Two blokes doing nothing to improve their already disappointing looks">
                        </div>
                        <div class="item">
                            <img src="img/homepage/yt_inplay.png" class="img-responsive" alt="Two blokes doing nothing to improve their already disappointing looks">
                        </div>
                        <div class="item">
                            <img src="img/homepage/ss_answers.jpeg" alt="...">
                        </div>
                     
                        <div class="item">
                            <img src="img/homepage/albumart.png" class="img-responsive" alt="Two blokes doing nothing to improve their already disappointing looks">
                        </div>
                        <div class="item">
                            <img src="img/homepage/nsfw.jpeg" class="img-responsive" alt="Two blokes doing nothing to improve their already disappointing looks">
                        </div>
                        <div class="item">
                            <img src="img/homepage/leaderboard.jpeg" class="img-responsive" alt="Two blokes doing nothing to improve their already disappointing looks">
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    </div>








                    <div class="col-md-6">
                            <h3>We're not just a YouTube livestream - we're an actual pub quiz.</h3>
                            <p class="lead">We mark your answers, so you might actually win this one.</p>
                            <p>We're as authentic to the real pub quiz experience as possible<sup>&dagger;</sup><p>
                            <ul>
                                <li><strong>An answer sheet for every team</strong> on your computer! No passing out dodgy pens</li>
                                <li>Two genuine fat, balding <strong>live quiz masters</strong> read through the questions, and provide <strong>tragic banter</strong> on the night.</li>
                                <li>We'll <strong>mark every answer</strong>. And if you're wrong, you can <a href="https://twitter.com/JoshwaM/status/1268996439843184641">moan</a><a href="https://www.twitter.com/pubquizstreams"> at us on Twitter</a></li>
                                <li>We run <a href="https://sociallydistant.pub/store/">an optional raffle</a> to fund the website costs, but otherwise <strong>it's completely free!</strong></li>
                                <li>You can take on the world, or compete against friends in <strong>mini-leagues</strong>. We'll even let you peek at their answers</li>
                            </ul>

                            <p><a href="newteam.php">Register your team now</a>, set up your own Zoom call, and join us right here on Friday Nights for The Best Virtual Pub Quiz in the UK!</p>

                            <p class="small"><sup>&dagger;</sup>Beer and snacks are not provided. You're resposible for your own inebriation.</p>
                    </div>
                </div> <!--  end row -->
                <hr>
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
                                if(team["team_active"] == 1){
                                        $teamprinter = $teamprinter + 1;
                                        if (count($teams_list) > 14 and $teamprinter % ceil((count($teams_list)+1)/3) == 0 and $teamprinter > 0){
                                            // every 10th row, start a new column
                                            echo '</div><div class="col-md-4">';
                                        }
                                        echo "<li>".$team["team_name"]."</li>";
                                    }
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

