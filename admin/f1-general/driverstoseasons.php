<?php

$output = NULL;
$error = NULL;
$driverid = NULL;
$teamid = NULL;
$date = date('Y');

$output .="<h2>Drivers To Seasons</h2>";
$output.="<p>Use this to assign drivers to teams in the database</p>";

// This runs if any data has been submitted
if (isset($_POST['driverstoseasons'])) {
	if (!empty($_POST['driver_id'])) { $driverid = $_POST['driver_id']; }
	if(!empty($_POST['team_id'])) { $teamid = $_POST['team_id']; }
	if ($driverid && $teamid) {
	$query = "SELECT * FROM driverstoseasons WHERE drivers_id = '$driverid' AND teamstoseasons_id = '$teamid'";
	$result = mysql_query ($query);
	// If record does not already exist
	if (mysql_num_rows($result) == 0) {
		$query = "INSERT INTO driverstoseasons (drivers_id, teamstoseasons_id) VALUES ('$driverid', '$teamid')";
		$result = mysql_query ($query);
		if (mysql_affected_rows() > 0) {
			$output .= "<p>The record was successfully added into the database.</p>";
		}
		else {
			$error .= "<p>Error: the record was not entered into the database.</p>";
		}
	}
	else {
		$error .= "<p>Error: This driver/team association already exists.</p>";
		}
	}
}

if ($error == NULL) {
	echo $output;
}
else {
	echo $error;
}

echo "<form action =\"".$_SERVER['PHP_SELF']."?page=driverstoseasons\" method=\"post\">\n\n";

// Select all drivers from the database
$query = "SELECT drivers.id, drivers.forename, drivers.surname FROM drivers ORDER BY drivers.forename, drivers.surname";
$result = mysql_query ($query);

if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"driver_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$forename = $row['forename'];
		$surname = $row['surname'];
		echo "<option value=\"".$id."\">".$forename." ".$surname."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$output .= "<p>No drivers found.</p>\n";
}
// Select all teams from the database
$query = "SELECT teamstoseasons.id, teamstoseasons.teamname, teamstoseasons.season FROM teamstoseasons WHERE teamstoseasons.season = '$date' ORDER BY teamstoseasons.teamname";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"team_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$name = $row['teamname'];
		$season = $row['season'];
		echo "<option value=\"".$id."\">".$name." (".$season.")</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$output .= "<p>No teams found.</p>\n";
}

echo "<input type=\"submit\" value=\"Create Association\" name=\"driverstoseasons\" />\n\n";
echo "</form>\n\n";

echo $output;

?>