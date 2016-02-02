<h2>Teams To Seasons</h2>
<p>Use this to assign drivers to teams in the database</p>

<?php

// Set output message to NULL
$message = NULL;

// This runs if any data has been submitted
if (isset($_POST['teamstoseasons'])) {
	$teamid = $_POST['team_id'];
	$season = $_POST['season'];
	$teamname = $_POST['team_name'];
  $sql = $dbh->prepare("SELECT * FROM teamstoseasons WHERE teams_id = :teamid AND season = :season");
  $sql->execute(array(':teamid' => $teamid, ':season' => $season));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
	// If record does not already exist
	if ($sql->rowCount() == 0) {
    $sql = $dbh->prepare("INSERT INTO teamstoseasons (teams_id, season, teamname) VALUES (:teamid, :season, :teamname)");
    $sql->execute(array(':teamid' => $teamid, ':season' => $season, ':teamname' => $teamname));
		if ($sql->rowCount() == 1) {
			$message .= "<p>The record was successfully added into the database.</p>";
		}
		else {
			$message .= "<p>Error: the record was not entered into the database.</p>";
		}
	}
	else {
		$message .= "<p>Error: This team/season association already exists.</p>";
		}
}

echo "<form action =\"".$_SERVER['PHP_SELF']."?page=teamstoseasons\" method=\"post\">\n\n";

// Select all teams from the database
$sql = $dbh->prepare("SELECT teams.id, teams.team_name FROM teams WHERE status = 'A' ORDER BY teams.team_name");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name = \"team_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->id."\">".$result->team_name."</option>\n";
	}
	echo "</select><br/>\n\n";
}
else {
	$message .= "<p>No teams found.</p>\n";
}
echo "<select name=\"season\">\n";
$year = date('Y');
while ($year >= 1950) {
	echo "<option value\"".$year."\">".$year."</option>\n";
	$year--;
}
	echo "</select><br/>";
echo "<label for=\"team_name\">Team Name: </label><input type=\"text\" name=\"team_name\" value=\"\" /><br/>\n";
echo "<input type=\"submit\" value=\"Create Association\" name=\"teamstoseasons\" />\n\n";
echo "</form>\n\n";

echo $message;

?>