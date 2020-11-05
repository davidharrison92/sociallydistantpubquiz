<?php 


function get_extension($file) {
	$tmp = explode('.', $file);
	$extension = end($tmp);
	return $extension;
}

function pictureround($imagesrc){
	$allow_types = array('PNG', 'JPG', 'JPEG');



	if (in_array(strtoupper(get_extension($imagesrc)), $allow_types))	{
		$outputstring = '<img src="picture_round/' . $imagesrc . '" alt="Picture Question" class="img-thumbnail img-responsive">';
		return $outputstring;
	} else {
		return 'Error - supplied image is not an allowable file format';
	}

}	



function pictureicon($imagesrc){
	$allow_types = array('PNG', 'JPG', 'JPEG');



	if (in_array(strtoupper(get_extension($imagesrc)), $allow_types))	{
		$outputstring = '<img src="picture_round/' . $imagesrc . '" alt="Picture Question" class="img-circle" height="50px" width="50px">';
		return $outputstring;
	} else {
		return 'Error - supplied image is not an allowable file format';
	}

}	

function musicplayer($audiosource, $allow_downloads){
	$playerstring = '<audio controls preload="none" style=" width:300px;">' . 
				 '<source src="picture_round/' . $audiosource . '" type="audio/mpeg">' . 
				 'Your Browser does not support this audio file. Sorry about that.' . 
				 '</audio>';
	
	if ($allow_downloads == TRUE) {
	$playerstring .=  '<br>';
	$playerstring .= '<a download href="picture_round/' .$audiosource. '"><span class="glyphicon glyphicon-download"></span> Download.</a>';
}
				

	return $playerstring;




}