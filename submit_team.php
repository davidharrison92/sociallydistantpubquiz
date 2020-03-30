<?php 

include ("db/db_config.php");
session_start();

$error = false;
$messages = array(
    "info" => array(),
    "danger" => array(),
    "success" => array()
);

if ( empty($_POST) ) {

  $error = true;
  $_SESSION["messages"]["danger"][] = "Please enter the details on the form below";

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


    if ($livestream == "1") {
        $livestream = 1;
    } else {
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
            $_SESSION["messages"]["danger"][] = "There's already a team called " .$team_name;
            $valid_teamname = 0;

        } else {
            //new (allowable) teamname
            $valid_teamname = 1;
        }
        
    } else {
    //team name not given.
        $error = 1;
        $_SESSION["messages"]["danger"][] = "You must choose a team name";
    } // end check team


    // VALIDATE Email

    if (strlen($team_email) < 5){
        $error = 1;
        $_SESSION["messages"]["danger"][] = "You must provide an email address";
    } else {
        $valid_email = 1;
    }
    // VALIDATE THAT THERE'S A SECRET

    if (strlen($team_secret) < 1) {
        $error = 1;
        $_SESSION["messages"]["danger"][] = "You must provide a team secret";
        $valid_teamsecret = 0;
    } else {
        $valid_teamsecret = 1;
    }

// Validate that at least one team member exists


    if (strlen($tm_1) < 1) {
        $error = 1;
        $_SESSION["messages"]["danger"][] = "You must provide at least one team member";
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
        $_SESSION["messages"]["danger"][] = "There was an issue saving team members. If this reoccurs, please <a href=\"mailto:david.harrison1992@gmail.com\">let me know</a>";      }
    }

    } else {
        // failed to insert
        $error = 1;
        $_SESSION["messages"]["danger"][] = "There was an issue saving your team. If this reoccurs, please <a href=\"mailto:david.harrison1992@gmail.com\">let me know</a>";
    }
    if ($error == 0){
        $_SESSION["messages"]["info"][] = "Your team has been entered into the next quiz. Good luck!";
    }
}
header("Location: newteam.php");
mysqli_close($conn);
exit();

