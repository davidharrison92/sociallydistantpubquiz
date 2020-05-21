<?php include_once("messages.php");

if(!isset($show_video)){
    include_once("db/db_config.php");
    include_once("db/get_game_state.php");
}

function site_tabs($current_round, $quiz_complete){
    //BEFORE and AFTER a quiz, keep to one tab. During the quiz, secondary pages open in new tabs (_blank)
    if($quiz_complete == 1 OR $current_round == 0){
        return 'target="_self"';
    } else {
        return 'target="_blank"';
    }
}

?>

<div class="container">
     <div class="row">
        <div class="col-xs-12 col-md-5">
            <a href="index.php"><img src="thepanickedshopper.jpg" class="img-responsive img-circle" max alt="Responsive image" style="max-height: 200px; max-width: 200px;"></a>
            <h1>Socially Distant Pub Quiz</h1>
            <h4>Now with 100% less human contact</h4>
        </div>
        <div class="col-xs-12 col-md-6 pull-right">
            <?php if ($show_video == 1) {
                ?>
            <iframe width="100%" height="300" id="ytembed" class="miniplayer" <?php echo 'src="https://www.youtube.com/embed/'.$ytID .'?controls=0&autoplay=1" '; ?>frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php
            } // end show vid
            ?>
        </div>
    </div>
    <div class="row">
        <div class="navbar-default">
            <ul class="nav navbar-nav">
                <?php 
                    if ($current_round > 0){
                        ?>
                        <li><a href="index.php" <?php echo site_tabs($current_round, $quiz_complete); ?> ><strong>Answer Sheet</strong></a></li>
                    <?php 
                    } ?>
                <li><a href="https://sociallydistant.pub/store/" <?php echo site_tabs($current_round, $quiz_complete); ?>>
                    <strong>Raffle</strong>
                </a></li>
                <li><a href="about.php" <?php echo site_tabs($current_round, $quiz_complete); ?> >How To Play</a></li>
                <li><a href="your_answers.php" <?php echo site_tabs($current_round, $quiz_complete); ?> >Your Score</a></li>
                <li><a href="leaderboard.php"  <?php echo site_tabs($current_round, $quiz_complete); ?> >Full Leaderboard</a></li>
                <li><a href="https://twitter.com/davidharrison92" target="_blank">@Dave</a></li>
                <li><a href="https://twitter.com/ElectricBloo" target="_blank">@Alex</a></li>
                <li><a href="https://twitter.com/PubQuizStreams" target="_blank">@Quiz</a></li>

            </ul>
        </div>
    </div> <!-- end row -->
</div> <!-- end container -->
