<?php 

function FindIndex($round, $scoretype){
	//round 1-9
	//scoretype (Marked, Correct)
	return 'R'.$round.$scoretype;
}

function add_team($team_id){
	if (array_key_exists("teamID",$_SESSION)){
		return '<td><input type="checkbox" name="addteam[]" value="'.$team_id.'"></td>';
	}

}


function remove_team($team_id){
	if (array_key_exists("teamID",$_SESSION)){
		return '<td><input type="checkbox" name="deleteteam[]" value="'.$team_id.'"></td>';
	}

}


function iscurrent($teamID){

	$match = false;

	if (array_key_exists("teamID", $_SESSION)) {
		if ($teamID == $_SESSION["teamID"]){
			$match = true;
		}
	}

	return $match;
}



function reportlink($teamID){
	if(array_key_exists("teamID",$_SESSION)){
		
		if($teamID == $_SESSION["teamID"]){
			$url = "your_answers.php";
		} else {
			$url = "your_answers.php?teamID=".$teamID;
		}

		return '<span class="nowrap"><a href="'.$url.'"><span class="glyphicon glyphicon-list-alt"></span><span class="small"> Peek<span></a></span>';
	}
}