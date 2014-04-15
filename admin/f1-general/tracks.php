<?php

echo "<h2>Create Tracks</h2>";

$message = NULL;
if (isset($_POST['submittracks'])) {
	$trackname = $_POST['trackname'];
	$query = "SELECT * FROM tracks WHERE track_name = '$trackname'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) == 0) {
		// Track not already there, can enter
		$query = "INSERT INTO tracks (track_name) VALUES ('$trackname')";
		$result = mysql_query($query);
		if ($result) {
			$message .= "<p>Record entered successfully.</p>";
			}
		else {
			// Record not entered.
			$message .= "<p>Error: Record not entered. ".mysql_error()."</p>";
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