<?php

echo "<h2>Create Grands Prix</h2>";

$message = NULL;
if (isset($_POST['submitgrandsprix'])) {
	$gpname = $_POST['gpname'];
	$query = "SELECT * FROM grandsprix WHERE grand_prix_name = '$gpname'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) == 0) {
		// GP not already there, can enter
		$query = "INSERT INTO grandsprix (grand_prix_name) VALUES ('$gpname')";
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
		$message .="<p>Error: GP already on database – not entered.</p>";
		}
	}

echo $message;

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=grandsprix" method="post">

<label for="gpname">Grand Prix</label><input type="text" name="gpname" value="" /><br/>

<input type="submit" name="submitgrandsprix" value="Add Grand Prix" />
</form>