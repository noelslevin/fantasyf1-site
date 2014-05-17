<?php

$error = NULL;
$output = NULL;

function changepassword() {
	include('includes/changepassword.html');
}

function error() {
	echo "<p>It has not been possible to change your password. Please see the information below.</p>";
	global $error;
	echo $error;
}

if (isset($_SESSION['user_id'])) {
	$title = "Your FantasyF1 Profile";
	include('includes/header.php');
	include '../../private/connection.php';
	
	echo "<div class=row>\n";
	echo "<div class=\"small-12 columns\">\n";
	echo "<h1>User profile</h1>";
	
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
	
	include'terms.php';
	include 'picks/registration_details.php';
	echo $output;
	
	if (isset($_POST['submit'])) {
		$error = NULL;
		if (empty($_POST['oldpassword'])) {
		$error .= "<p>You did not enter your old password.</p>";
		}
		if (empty($_POST['newpassword1'])) {
			$error .= "<p>You did not enter a new password. Passwords are mandatory. Please <strong>do not</strong> reuse a password from another online service.</p>";
		}
		if (empty($_POST['newpassword2'])) {
			$error .= "<p>You did not re-enter your new password. This is required to verify your new password is correct.</p>";
		}
		if ($_POST['newpassword1'] != $_POST['newpassword2']) {
				$error .="<p>You entered two different passwords in the new password fields.</p>";
		}
		if ($error == NULL) {
			$password = password_hash($_POST['newpassword1'], PASSWORD_DEFAULT);
			$id = $_SESSION['user_id'];
			$sql = "SELECT password FROM fantasyusers WHERE id = :userid";
			$query = $dbh->prepare($sql);
			$query->execute(array(':userid' => $id));
			$row = $query->fetch(PDO::FETCH_ASSOC);
			if ($query->rowCount() == 1) {
				if (password_verify($_POST['oldpassword'], $row['password'])) {
					$sql = "UPDATE fantasyusers SET password=? WHERE id=?";
					$query = $dbh->prepare($sql);
					$query->execute(array($password,$id));
					if ($query->rowCount() == 1) {
						echo "<p>Your password has been updated.</p>";
					}
					else {
						$error .= "<p>An unexpected error occurred and your password was not updated. Please contact the system administrator.</p>";
						echo $error;
					}
				}
				else {
					$error .= "<p>The old password you entered does not match the password stored in the database.</p>";
					changepassword();
					error();
				}
			}
			else {
				$error .= "<p>An unexpected error occurred and your password was not updated. Please contact the system administrator.</p>";
				echo $error;
			}
		}
		else {
			changepassword();
			error();
		}
	echo "</div>\n</div>\n";
	include('includes/footer.php');
	}
	else {
		changepassword();
		echo "</div>\n</div>\n";
		include 'includes/footer.php';
	}
}
else {
	header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
	exit(); // Quit the script.
}

?>