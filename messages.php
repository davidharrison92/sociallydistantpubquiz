
<?php 
if(!isset($_SESSION)){
	session_start();	
}

if (isset($_SESSION["messages"])){
	foreach($_SESSION["messages"] as $key => $value){
	    foreach ($value as $message){
	    ?>
	        <div class="alert alert-<?php echo $key; ?>" role="alert">
	            <p><?php echo $message; ?></p>
	        </div>
	    <?php
	    }
	}
}
unset($_SESSION["messages"]);
?>