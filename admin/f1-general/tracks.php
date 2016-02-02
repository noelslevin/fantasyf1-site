<?php

echo "<h2>Create Tracks</h2>";

$message = NULL;
if (isset($_POST['submittracks'])) {
	$trackname = $_POST['trackname'];
  $sql = $dbh->prepare("SELECT * FROM tracks WHERE track_name = :trackname");
  $sql->execute(array(':trackname' => $trackname));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
	if ($sql->rowCount() == 0) {
		// Track not already there, can enter
    $sql = $dbh->prepare("INSERT INTO tracks (track_name) VALUES (:trackname)");
    $sql->execute(array(':trackname' => $trackname));
		if ($sql->rowCount() == 1) {
			$message .= "<p>Record entered successfully.</p>";
			}
		else {
			// Record not entered.
			$message .= "<p>Error: Record not entered.</p>";
			}
		}
	else {
		// GP already on database
		$message .="<p>Error: Track already on database – not entered.</p>";
		}
	}

echo $message;

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=tracks" method="post">

<label for="forename">Track Name: </label><input type="text" name="trackname" value="" /><br/>

<input type="submit" name="submittracks" value="Add Track" />
</form>