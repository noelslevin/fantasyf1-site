<?php

$active = 1;

function regform() {
	include('includes/register.html');
}

function error() {
	echo "<p><strong>There are errors with the data you submitted. Please see the information below.</strong></p>";
	global $error;
	echo $error;
}

include '../../private/connection.php';
include 'includes/header.php';

echo "<div class=row>\n";
echo "<div class=\"small-12 columns\">\n";

if (isset($_POST['submit'])) {
	$error = NULL;
	$email = strtolower($_POST['email']);
	$username= strtolower($_POST['username']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error .= "<p>The email address you submitted is not a valid email address.</p>";
	}
	if (!ctype_alnum($username)) {
		$error .= "<p>The username you entered you submitted is not valid. Only lowercase letters and numbers are allowed. Any uppercase letters will automatically be converted to lowercase, but use of spaces and punctuation will result in an error.</p>";
	}
	if (empty($_POST['password'])) {
		$error .= "<p>You did not enter a password. Passwords are mandatory. Please <strong>do not</strong> reuse a password from another online service.</p>";
	}
	if ($error == NULL) {
		$sql = $dbh->prepare("SELECT username, email_address FROM fantasyusers WHERE username = :username OR email_address = :email");
		$sql->execute(array(':username' => $username, ':email' => $email));
		$row = $sql->fetchAll(PDO::FETCH_ASSOC);
		if ($sql->rowCount() > 0) {
			foreach ($row as $return) {
				if ($return['username'] == $username) { $username_test = 1; }
				if ($return['email_address'] == $email) { $email_test = 1; }
			}
		if ($username_test == 1) { $error .= "<p>Sorry, the username you entered to register is already taken.</p>"; }
		if ($email_test == 1) { $error .= "<p>Sorry, the email address you entered is already registered against an account. Have you tried to log in?</p>"; }
		regform();
		error();
		}
		else {
			// Email address and username both available. Add to database.
			$sql = "INSERT INTO fantasyusers (username, password, email_address) VALUES (:username, :password, :email)";
			$query = $dbh->prepare($sql);
			$query->execute(array(':username'=>$username,':password'=>$password,':email'=>$email));
			if ($query->rowCount() == 1) {
				echo "<p>You have been successfully registered. Please log in to continue. Once logged in, you will need to agree to the (brief) site terms.</p>";
			}
			else {
				$error .= "<p>An unexpected error occurred. Please contact the system administrator.</p>";
				echo $error;
			}
		}
	
	}
	else {
		regform(); // Need to add $_POST options to the register.html form for registration errors
		error();
	}
}
	
else {
	if ($active == 1) {
		regform();
	}
	else {
	echo "<h1>Registration</h1>\n";
		echo "<p>Sorry, registration is currently closed. Once current users have had a chance to register for this season, it will open.</p>";
	}
}

echo "</div>\n</div>\n";

include 'includes/footer.php';

?>