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
	$query = "SELECT * FROM teamstoseasons WHERE teams_id = '$teamid' AND season = '$season'";
	$result = mysql_query ($query);
	// If record does not already exist
	if (mysql_num_rows($result) == 0) {
		$query = "INSERT INTO teamstoseasons (teams_id, season, teamname) VALUES ('$teamid', '$season', '$teamname')";
		$result = mysql_query ($query);
		if (mysql_affected_rows() > 0) {
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
$query = "SELECT teams.id, teams.team_name FROM teams ORDER BY teams.team_name";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"team_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$name = $row['team_name'];
		echo "<option value=\"".$id."\">".$name."</option>\n";
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

<?php

/*

SELECT drivers.forename, drivers.surname, teamstoseasons.teamname, teamstoseasons.teams_id 
FROM drivers, driverstoseasons, teams, teamstoseasons
WHERE driverstoseasons.drivers_id = drivers.id
AND driverstoseasons.teamstoseasons_id = teamstoseasons.id
AND teamstoseasons.teams_id = teams.id
AND teamstoseasons.season = 2011
ORDER BY teams.id ASC

*/

?>