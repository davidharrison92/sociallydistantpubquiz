<?php
include ("db/db_config.php");
$started = session_start();

if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
} 

$username = mysqli_real_escape_string($conn, $_POST["username"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);

// if (password_verify($_POST['password'], $password)) {
if ($username == "george" && $password == "test"){
    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['name'] = $_POST['username'];
    $_SESSION['id'] = $id;
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
    exit();
} else {
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/login.php');
    exit();
} 
?>
