 <?php
 
$output = NULL;
$error = NULL;
$forename = NULL;
$surname = NULL;
$output .= "<h2>Create Drivers</h2>";
if (isset($_POST['submitdrivers'])) {
	if (!empty($_POST['forename'])) { $forename = $_POST['forename']; }
	if (!empty($_POST['surname'])) { $surname = $_POST['surname']; }
	if ($forename && $surname) {
    // Check to make sure the driver doesn't already exist
    $sql = $dbh->prepare("SELECT * FROM drivers WHERE forename = :forename AND surname = :surname");
    $sql->execute(array(':forename' => $forename, ':surname' => $surname));
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);
		if ($sql->rowCount() == 0) {
      // No driver found
			$sql = $dbh->prepare("INSERT INTO drivers (forename, surname) VALUES (:forename, :surname)");
      $sql->execute(array(':forename' => $forename, ':surname' => $surname));
			if ($sql->rowCount() == 1) {
				$output .= "<p>Record entered successfully.</p>";
				}
			else {
				// Record not entered.
				$output .= "<p>Error: Record not entered.</p>";
				}
			}
		else {
			// Driver already on database
			$output .="<p>Error: Driver already on database. Record cannot be added.</p>";
			}
		}
		else {
			$error .="<p>Driver details were not entered properly.</p>";
		}
	}
	
if ($error == NULL) {
	echo $output;
}
else {
	echo $error;
}

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=drivers" method="post">

<label for="forename">Forename: </label><input type="text" name="forename" value="<?php if(isset($_POST['forename'])) { echo $_POST['forename']; } ?>" /><br/>
<label for="surname">Surname: </label><input type="text" name="surname" value="<?php if(isset($_POST['surname'])) { echo $_POST['surname']; } ?>" /><br/>

<input type="submit" name="submitdrivers" value="Add Driver" />
</form>