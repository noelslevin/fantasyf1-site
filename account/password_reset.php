<?php

$error = NULL;

function resetform() {
	include('includes/passwordreset.html');
}

function error() {
	echo "<p><strong>An error occurred. Please see the information below.</strong></p>";
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
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error .= "<p>The email address you submitted is not a valid email address.</p>";
	}
	if ($error == NULL) {
		$sql = $dbh->prepare("SELECT username, email_address FROM fantasyusers WHERE email_address = :email");
		$sql->execute(array(':email' => $email));
		$row = $sql->fetchAll(PDO::FETCH_ASSOC);
		if ($sql->rowCount() == 1) {
			// Email password reset details.
			// Need to create one-time reset link
			$code = bin2hex(openssl_random_pseudo_bytes(64));
			$sql = $dbh->prepare("UPDATE fantasyusers SET resetcode = :code WHERE email_address = :email");
			$sql->execute(array(':code'=>$code, ':email'=>$email));
			if ($sql->rowCount() == 1) {
				require '../includes/class.phpmailer.php';
				require '../includes/class.smtp.php';
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				$mail->isHTML(true);

				$mail->Host = $ff1mailhost;
				$mail->Username = $ff1mailusername;
				$mail->Password = $ff1mailpassword;
				$mail->setFrom($ff1fromaddress, $ff1fromname);
				
				$mail->addAddress($email);
				$mail->Subject = 'FantasyF1 Password Reset';
				$mail->Body = "A password reset was requested for this account. You can <a href=http://fantasyf1.slevin.org.uk/account/password_reset.php?email=".$email."&code=".$code.">reset your password on the FantasyF1 website</a>. This link will only work once; please change your password when you log in.";
				
				if (!$mail->send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					echo "<h1>Password Reset</h1>";
					echo "<p>A password reset link has been emailed to ".$email.". Please check your spam folder as the email may be marked as spam.</p>";
				}
			}
			else {
				$error .="<p>There was a problem resetting your password.</p>";
			}
			
		}
		else {
			$error .= "<p>The email address you entered does not appear to be registered.</p>";
		}
	}
}

else if (isset($_POST['submitnewpassword'])) {
	$email = $_POST['email'];
	if (empty($_POST['password1'])) {
		$error .= "<p>You did not enter a new password. Passwords are mandatory. Please <strong>do not</strong> reuse a password from another online service.</p>";
	}
	if (empty($_POST['password2'])) {
		$error .= "<p>You did not re-enter your new password. This is required to verify your new password is correct.</p>";
	}
	if ($_POST['password1'] != $_POST['password2']) {
			$error .="<p>You entered two different passwords in the new password fields.</p>";
	}
	if ($error == NULL) {
		$password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
		$sql = $dbh->prepare("UPDATE fantasyusers SET password=? WHERE email_address=?");
		$sql->execute(array($password, $email));
		if ($sql->rowCount() == 1) {
			echo "<p>Your password has been updated. You will need to log in with your new password to continue.</p>";
		}
		else {
			$error .= "<p>Sorry, your password wasn't updated.</p>";
		}
	}
}

else if (isset($_GET['email'])) {
	if (isset($_GET['code'])) {
		$email = $_GET['email'];
		$code = $_GET['code'];
		$sql = $dbh->prepare("UPDATE fantasyusers SET resetcode = \"\" WHERE email_address = :email AND resetcode = :code");
		$sql->execute(array(':email' => $email, ':code' => $code));
		if ($sql->rowCount() == 1) {
			echo "<p>Please enter a new password now. Your password reset code cannot be used again.</p>";
			include 'includes/newpassword.html';
		}
		else {
			$error .= "<p>Error: Password reset code invalid.</p>";
		}
	}
}

else {
	resetform();
}

if ($error != NULL) {
	error();
}

echo "</div>\n</div>\n";
include 'includes/footer.php';

?>