<?php

include("db/db_config.php");

// clean the results


if ( empty($_POST) ) {

  $error = true;
  $error_reason[] = "Please enter the details on the form below";

  echo "nothing set";
} else {

// CLEAN IT, BUILD AN ARRAY

	$answers["a1"] = mysqli_real_escape_string($conn,$_POST["ans1"]);
	$answers["a2"] = mysqli_real_escape_string($conn,$_POST["ans2"]);
	$answers["a3"] = mysqli_real_escape_string($conn,$_POST["ans3"]);
	$answers["a4"] = mysqli_real_escape_string($conn,$_POST["ans4"]);
	$answers["a5"] = mysqli_real_escape_string($conn,$_POST["ans5"]);
	$answers["a6"] = mysqli_real_escape_string($conn,$_POST["ans6"]);
	$answers["a7"] = mysqli_real_escape_string($conn,$_POST["ans7"]);
	$answers["a8"] = mysqli_real_escape_string($conn,$_POST["ans8"]);
	$answers["a9"] = mysqli_real_escape_string($conn,$_POST["ans9"]);
	$answers["a10"] = mysqli_real_escape_string($conn,$_POST["ans10"]);


	$answers["teamID"] = mysqli_real_escape_string($conn, $_POST["teamID"]);
	$answers["teamsecret"] = mysqli_real_escape_string($conn, $_POST["secret"]);


	} // end of if POST empty.


var_dump($answers);



// loop through the array, and pass them to the database.


