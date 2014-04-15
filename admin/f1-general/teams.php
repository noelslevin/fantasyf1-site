<?php

echo "<h2>Create Teams</h2>";

$message = NULL;
if (isset($_POST['submitteams'])) {
	$teamname = $_POST['team'];
	$query = "SELECT * FROM teams WHERE team_name = '$teamname'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) == 0) {
		// Team not already there, can enter
		$query = "INSERT INTO teams (team_name) VALUES ('$teamname')";
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
		// Team already on database
		$message .="<p>Error: Team already on database – not entered.</p>";
		}
	}

echo $message;

?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=teams" method="post">

<label for="forename">Team Name: </label><input type="text" name="team" value="" /><br/>

<input type="submit" name="submitteams" value="Add Team" />
</form>