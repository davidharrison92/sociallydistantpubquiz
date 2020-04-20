<?php
session_start();
include("db/db_config.php");
include("db/get_game_state.php");

//Build Mini League on form submit

    if (array_key_exists("addteam", $_POST) and array_key_exists("teamID",$_SESSION)){
        
        foreach($_POST["addteam"] as $entry) {
            $ins_array[] = "('".$_SESSION["teamID"]."' , '".$entry."')";
        }

       // var_dump($ins_array);
        $ins_qry = 'INSERT IGNORE mini_leagues(league_owner,league_member) VALUES' . implode(" , ", $ins_array) ;

        if(mysqli_query($conn, $ins_qry)){
            // inserted succesfully
        } else {
            // failed to insert - raise an error
        }

    }

// Check if there's a Mini League for this team ID

if (array_key_exists("teamID", $_SESSION)){
    $qry_check_ML = "SELECT COUNT(*) as 'Count' FROM mini_leagues where league_owner = '".$_SESSION["teamID"]."'; ";

    $check_ML = $conn->query($qry_check_ML);
    $check_ML = $check_ML->fetch_assoc();

    if ($check_ML["Count"] * 1  >= 1) {
        $mini_league_exists = true;
    } else {
        $mini_league_exists = false;
    }

} else {
    //no team logged in
    $mini_league_exists = false;
}

?>
<head>
    <title>Socially Distant Pub Quiz</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container">


<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Full Leaderboard</a></li>
    <li role="presentation"><a href="#mini" aria-controls="mini" role="tab" data-toggle="tab">My League</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">

        <h1>Main Leaderboard:</h1>
        <h4>Socially Distanced Pub Quiz - Overall</h4>

        <?php
        if ($current_round > 1){
         include("db/build_leaderboard.php"); 
        } else {
        ?>
        <div class="alert alert-warning"><p><strong>The results aren't in yet</strong></p>
            <p>The leaderboard will become available after the first round has been marked</p>
        </div>
        <?php
        }
        ?>
            
    </div>


    <div role="tabpanel" class="tab-pane" id="mini">prof</div>



        <h1>Mini Leaderboard:</h1>
        <h4>Compete against the teams that matter...</h4>

        <?php
        if ($current_round > 1){
         include("db/build_minileague.php"); 
        } else {
        ?>
        <div class="alert alert-warning"><p><strong>The results aren't in yet</strong></p>
            <p>The leaderboard will become available after the first round has been marked</p>
        </div>
        <?php
        }
        ?>



  </div>

</div>




    </div> <!-- end container -->
</body>

<?php 
	mysqli_close($conn);
?>

