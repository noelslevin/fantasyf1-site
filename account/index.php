<?php

session_start();
DEFINE('IN_PAGE', TRUE);
if(isset($_GET['page'])) { $page = $_GET['page']; } else { $page= NULL; }

if (isset($_SESSION['user_id'])) {
	include 'profile.php';
	
}
else {
	header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
	exit(); // Quit the script.
}

?>