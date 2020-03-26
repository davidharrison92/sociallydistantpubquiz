<?php 

include ("db/db_config.php");

//var_dump($_POST);

$error = false;
$error_reason = array();


if ( empty($_POST) ) {

  $error = true;
  $error_reason[] = "Please enter the details on the form below";

 // echo "nothing set";

} else {
  //POST is set.

  //clean_values
    $team_name = mysqli_real_escape_string($conn,$_POST["team_name"]);
    $tm_1 = mysqli_real_escape_string($conn,$_POST["member1"]);

    $tm_2 = mysqli_real_escape_string($conn,$_POST["member2"]);
    
    $tm_3 = mysqli_real_escape_string($conn,$_POST["member3"]);
    
    $tm_4 = mysqli_real_escape_string($conn,$_POST["member4"]);

    $team_secret = mysqli_real_escape_string($conn,$_POST["team_secret"]);

    $team_email = mysqli_real_escape_string($conn,$_POST["team_email"]);

    if (isset($_POST["livestream"])) {
     $livestream = mysqli_real_escape_string($conn,$_POST["livestream"]);
    } else {
      $livestream = 0;
    }


    if ($livestream !== 1) {
      $livestream = 0;
    }

// VALIDATE TEAM NAME

       if (strlen($team_name) > 0) {
           //CHECK THE TEAM FOR VALIDITY
           $name_query = "SELECT COUNT(*) as 'Count' from teams where team_name = '$team_name'";
            $getname = $conn->query($name_query);
           $team_result = $getname->fetch_assoc();
                    
           
           if (($team_result["Count"] * 1) >= 1) {
              $error = 1;
              $error_reason[] = "There's already a team called " .$team_name;
              $valid_teamname = 0;

           } else {
              //new (allowable) teamname
              $valid_teamname = 1;
           }
            
       } else {
        //team name not given.
          $error = 1;
          $error_reason[] = "You must choose a team name";
       } // end check team


// VALIDATE Email

       if (strlen($team_email) < 5){
        $error = 1;
        $error_reason[] = "You must provide an email address";
       } else {
        $valid_email = 1;
       }

// VALIDATE THAT THERE'S A SECRET

      if (strlen($team_secret) < 1) {
        $error = 1;
        $error_reason[] = "You must provide a team secret";
        $valid_teamsecret = 0;
      } else {
        $valid_teamsecret = 1;
      }

// Validate that at least one team member exists


      if (strlen($tm_1) < 1) {
        $error = 1;
        $error_reason[] = "You must provide at least one team member";
        $valid_teamcaptain = 0;
      } else {
        $valid_teamcaptain = 1;
      }



} // end of submitted $_POST.


if ($error == 0){
  // Save the team

  //generate an ID
  $team_ID = uniqid();

 // echo $team_ID;

  $initial_insert = "INSERT INTO teams (team_name, team_id, secret, person1, team_email, willing_livestream_participant) "; 
  $initial_insert = $initial_insert . "VALUES ('$team_name', '$team_ID', '$team_secret', '$tm_1', '$team_email', $livestream);" ;


  if (mysqli_query($conn,$initial_insert)) {
    // successful first insert.

    if(strlen($tm_2) >0 ){
      $additional_members_query = "UPDATE teams set person2 = '$tm_2' ";

      if (strlen($tm_3) > 0) {
        $additional_members_query = $additional_members_query . " , person3 = '$tm_3' ";
        print '3';


        if (strlen($tm_4 )> 0) {
          $additional_members_query = $additional_members_query. " , person4 = '$tm_4' ";
        }

      }
        

    $additional_members_query = $additional_members_query. " where team_id = '$team_ID';";




    //echo $additional_members_query;


      // here's where we run it

      if (mysqli_query($conn,$additional_members_query)){
        $error= 0;
      } else {
    $error = 1;
    $error_reason[] = "There was an issue saving team members. If this reoccurs, please <a href=\"mailto:david.harrison1992@gmail.com\">let me know</a>";      }
    }

  } else {
    // failed to insert
    $error = 1;
    $error_reason[] = "There was an issue saving your team. If this reoccurs, please <a href=\"mailto:david.harrison1992@gmail.com\">let me know</a>";
  }
}



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

<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/wsHIzzmJEkY?controls=0&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


</div>


</div> <!-- / header row -->
<?php if ($error == 0) {
  ?>

<div class="row">

<div class="alert alert-success">
  <p class="lead"><strong>Thanks, team <?php echo $team_name; ?></strong></p>
  <p>Your team has been entered into the next quiz.</p>
  <p>Good luck!</p>
  <p><a class="btn btn-success btn-lg" href="index.php" role="button">Main Page</a></p>
</div>

</div>



<?php
} // end of error = 0 if.

if ($error == 1){
?>


<div class="alert alert-danger">
  <p class="lead">Oh no!</p>
  <p>Looks like something went wrong: </p>
  <ul>
    <?php foreach($error_reason as $reason){
      echo '<li>' . $reason . '</li>' ;
    } ?>
  </ul>
</div>


<hr>

<h5>Try again...</h5>


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




<?php
} // end of error state.

?>
<div class="row">


</div>

</div> <!-- /container -->
</body>
</html>

