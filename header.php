<?php include("messages.php");

if(!isset($show_video)){
    include("db/db_config.php");
    includ("db/get_game_state.php");
}

?>

<div class="container">
    <div class="col-xs-12 col-md-5">
        <a href="index.php"><img src="thepanickedshopper.jpg" class="img-responsive img-circle" max alt="Responsive image" style="max-height: 200px; max-width: 200px;"></a>
        <h1>Socially Distant Pub Quiz</h1>
        <h4>Now with 100% less human contact</h4>
    </div>
    <div class="col-xs-12 col-md-5 pull-right">
        <?php if ($show_video == 1) {
            ?>
        <iframe id="ytembed" class="miniplayer" <?php echo 'src="https://www.youtube-nocookie.com/embed/'.$ytID .'?controls=0&autoplay=1" '; ?>frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <?php
        } // end show vid
        ?>
    </div>
    <div class="row">
        <nav class="navbar navbar-default">
            <ul class="nav navbar-nav">
                <li><a href="about.html" target="_blank">How To Play</a></li>
                <li><a href="your_answers.php" target="_blank">Your Score</a></li>
                <li><a href="leaderboard.php" target="_blank">Full Leaderboard</a></li>
            </ul>
        </nav>
    </div> <!-- end row -->
</div> <!-- end container -->