<?php include("messages.php");?>

<div class="col-xs-12 col-md-5">
    <a href="index.php"><img src="thepanickedshopper.jpg" class="img-responsive img-circle" max alt="Responsive image" style="max-height: 200px; max-width: 200px;"></a>
    <h1>Socially Distant Pub Quiz</h1>
    <h4>Now with 100% less human contact</h4>
    <span class="pull-left"><a href="about.html" target="_blank">How to Play</a></span>
    <span class="pull-right">
        Tweet us: <a href="https://twitter.com/davidharrison92" target="_blank">@Dave</a>, <a href="https://twitter.com/ElectricBloo" target="_blank">@Lighty</a>, <a href="https://twitter.com/PubQuizStreams" target="_blank">@Quiz</a>    
    </span>
</div>
<div class="col-xs-12 col-md-5 pull-right">
    <?php if ($show_video == 1) {
        ?>
    <iframe id="ytembed" class="miniplayer" <?php echo 'src="https://www.youtube-nocookie.com/embed/'.$ytID .'?controls=0&autoplay=1" '; ?>frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <?php
    } // end show vid
    ?>
</div>