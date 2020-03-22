<?php 

include ("db/db_config.php");

var_dump($_POST);

$error = false;

if ( empty($_POST) ) {

  $error = true;
  $error_reason[] = "Please enter the details on the form below";

  echo "nothing set";

} else {
  //POST is set.

  //clean_values
    $team_name = mysqli_real_escape_string($conn,$_POST["team_name"]);
    $tm_1 = mysqli_real_escape_string($conn,$_POST["member1"]);
    $tm_2 = mysqli_real_escape_string($conn,$_POST["member2"]);
    $tm_3 = mysqli_real_escape_string($conn,$_POST["member3"]);
    $tm_4 = mysqli_real_escape_string($conn,$_POST["member4"]);

    $team_secret = mysqli_real_escape_string($conn,$_POST["team_secret"]);

// VALIDATE TEAM NAME

       if (strlen($team_name) > 0) {
           //CHECK THE TEAM FOR VALIDITY
           $cheesequery = "SELECT COUNT(*) as 'Count' from teams where team_name = '$team_name'";
            $getcheese = $conn->query($cheesequery);
           $cheeseresult = $getcheese->fetch_assoc();
                    
           
           if (($team_result["Count"] * 1) >= 1) {
              $error = 1;
              $error_reason[] = "Teamname already used";
              $valid_teamname = 0;

           } else {
              //new (allowable) teamname
              $valid_teamname = 1;
           }
            
       } else {
        //team name not given.
          $error = 1;
          $error_reason[] = "Please provide a team name";
       } // end check team


// VALIDATE THAT THERE'S A SECRET

      if (strlen($team_secret) < 1) {
        $error = 1;
        $error_reason[] = "Please provide a team secret";
        $valid_teamsecret = 0;
      } else {
        $valid_teamsecret = 1;
      }


} // end of submitted $_POST.



?>

<html>
<head>
    <title>Socially Distant Pub Quiz</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<div class="container">


<div class="row">
  <div class="col-xs-12 col-md-8"><h1>Socially Distant Pub Quiz</h1>
<h3><em>Now with 100% less human contact</em></h3></div>



</div> <!-- / header row -->




</div> <!-- /container -->
</body>
</html>

