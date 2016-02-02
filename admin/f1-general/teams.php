<?php

echo "<h2>Create Teams</h2>";

$message = NULL;
if (isset($_POST['submitteams'])) {
	$teamname = $_POST['team'];
  $sql = $dbh->prepare("SELECT * FROM teams WHERE team_name = :teamname");
  $sql->execute(array(':teamname' => $teamname));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
	if ($sql->rowCount() == 0) {
		// Team not already there, can enter
    $sql = $dbh->prepare("INSERT INTO teams (team_name) VALUES (:teamname)");
    $sql->execute(array(':teamname' => $teamname));
    $row = $sql->fetchAll(PDO::FETCH_OBJ);
		if ($sql->rowCount() == 1) {
			$message .= "<p>Record entered successfully.</p>";
			}
		else {
			// Record not entered.
			$message .= "<p>Error: Record not entered.</p>";
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