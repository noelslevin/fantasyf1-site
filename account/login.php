<?php

include '../../private/connection.php';
include 'includes/header.php';

echo "<div class=row>\n";
echo "<div class=\"small-12 columns\">\n";

if (isset($_POST['submit'])) {
	$email = $_POST['email'];	
	// Select user from database
	$sql = $dbh->prepare("SELECT username, password, id FROM fantasyusers WHERE email_address = :email");
	$sql->execute(array(':email' => $email));
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	// If one record is found
	if ($sql->rowCount() == 1) {
		//Verify submitted password against stored hash
		if (password_verify($_POST['password'], $row['password'])) {
			// Has successfully logged in - set session and redirect
			session_start();
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
			header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/");
			exit();
		}
		else {
			echo "<p>Sorry, the details you entered do not match those on the database.</p>";
		}
	}
	else {
		echo "<p>Sorry, the user details you entered could not be found.</p>";
	}
}
	
else {

?>

<h1>Log In</h1>

<form name=loginform action="<?php echo $_SERVER['PHP_SELF'];?>" method=post>
  <input type=email name=email placeholder="Email address" autofocus>
  <input type=password name=password placeholder=Password>
  <input type=submit name=submit value="Log In">
</form>

<p><a href="password_reset.php">I need to reset my password.</a></p>
<p><a href="register.php">I want to register.</a></p>

<?php 

}

echo "</div>\n</div>\n";
include 'includes/footer.php';

?>