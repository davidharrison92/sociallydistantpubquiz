<?php 

	$name_query = "SELECT COUNT(*) as 'Count' from teams where team_id = '$teamID' and secret = '$teamsecret'";
	$check = $conn->query($name_query);
	$check = $check->fetch_assoc();
			

	if (($check["Count"] * 1) >= 1) {
		$team_secret_pass = 1;
		$_SESSION["teamID"] = $teamID;

		$activate_qry = "UPDATE teams set team_active = 1 where team_id = '".$teamID."';";
		mysqli_query($conn,$activate_qry);


	} else {
	//new (allowable) teamname
		$team_secret_pass = 0;
	}
