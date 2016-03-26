<?php

session_start();
$error = NULL;
$output = NULL;
$N = NULL;

if (isset($_SESSION['user_id'])) {
	$title = "Your FantasyF1 Picks";
	include('includes/header.php');
	include('../../private/connection.php');
	
	echo "<div class=row>\n";
	echo "<div class=\"small-12 columns\">\n";
	echo "<h1>Make your picks</h1>";
	
	if (isset($_POST['agreetoterms'])) {
		$terms = 0;
		$terms = $_POST['terms'];
		if ($terms == 1) {
			$sql = $dbh->prepare("UPDATE fantasyusers SET registered = 1 WHERE id = :userid");
			$sql->execute(array(':userid' => $_SESSION['user_id']));
			if ($sql->rowCount() == 1) {
				echo "<p>You have agreed to the site terms.</p>";
			}
			else {
				$error .= "<p>An unexpected error occurred and your password was not updated. Please contact the system administrator.</p>";
				echo $error;
			}
		}	
	}
	
	include 'terms.php';
	include ('picks/registration_details.php');
	
	if ($registered == 1) {
		if (isset($_POST['submitpicks'])) {
			include ('picks/picks_deadline.php');
			if ($canpick == 1) {
				include ('submit_picks.php');
				if ($N > 0) {
					include ('picks/remove_old_picks.php');
				}
			}
		}
		
		include ('picks/current_picks.php');
		include ('picks/get_picks.php');
	}
	
	if ($error == NULL) {
		echo $output;
	}
	else {
		echo $error;
	}
	echo "</div>\n</div>\n";
	include('includes/footer.php');
}
else {
	header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
	exit(); // Quit the script.
}

?>