<?php
echo "<h2>Fantasy Teams To Seasons</h2>\n\n";
if(isset($_POST['fantasyteamstoseasons'])) {
	$teamid = $_POST['fantasyteam_id'];
	$teamname = $_POST['fantasy_team_name'];
	$year = $_POST['year'];
	$query = "INSERT INTO fantasyteamstoseasons (fantasyteams_id, teamname, season) VALUES ('$teamid', '$teamname', '$year')";
	$result = mysql_query($query);
	if ($result) {
		$output .= "<p>The record was successfully added into the database.</p>";
	}
	else {
		$error .= "<p>Error: the record was not entered into the database.</p>";
	}
}
	

echo "<form action =\"".$_SERVER['PHP_SELF']."?page=ff1teamstoseasons\" method=\"post\">\n\n";

// Select all fantasy teams from the database
//$query = "SELECT fantasyteams.id, fantasyteams.fantasyteam_name FROM fantasyteams ORDER BY fantasyteams.fantasyteam_name ASC";
$query = "SELECT fantasyteams.id, fantasyteams.fantasyteam_name FROM fantasyteams WHERE id NOT IN (SELECT fantasyteams.id AS fantasyteams_id FROM fantasyteams INNER JOIN fantasyteamstoseasons ON fantasyteamstoseasons.fantasyteams_id = fantasyteams.id WHERE fantasyteamstoseasons.season = YEAR(CURDATE())) ORDER BY fantasyteams.fantasyteam_name ASC";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"fantasyteam_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$name = $row['fantasyteam_name'];
		echo "<option value=\"".$id."\">".$name."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$error .= "<p>No fantasy teams found.</p>\n";
}

if ($error == NULL) {
	echo $output;
}
else {
	echo $error;
}

?>

<label for="year">Year: </label><input type="text" name="year" id="year" value="<?php echo date("Y"); ?>" />
<label for="fantasy_team_name">Team Name: </label><input type="text" name="fantasy_team_name" id="fantasy_team_name" value="" />

<?php

echo "<input type=\"submit\" value=\"Create Association\" name=\"fantasyteamstoseasons\" />\n\n";
echo "</form>\n\n";

?>