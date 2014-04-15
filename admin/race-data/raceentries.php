<h2>Race Entries</h2>
<p>This page is for adding drivers to races. Please note, the script currently assumes there should be 22 entrants per race.</p>

<?php

$year = date("Y");
$message = NULL;
$n = 0;

if (isset($_POST['race_entries'])) {
	$raceid = $_POST['race_id'];
	if (!empty($_POST['driver'])) {
		foreach ($_POST['driver'] as $driver) {
			$query = "INSERT INTO raceentries (driverstoseasons_id, races_id) VALUES ('$driver', '$raceid')";
			$result = mysql_query ($query);
			$affected = mysql_affected_rows();
			if ($affected == 1) {
			$n++;
				//$message .= "<p>Driver ID ".$driver." added to race ID ".$raceid." the database.</p>";
				// Driver added
			}
			else {
			// Driver not added
				$message .= "<p>Error. Entry not appended to the database.</p>";
			}
		}
		if ($n == 22) {
		// If 22 drivers have been added
		$query = "UPDATE races SET status = 2 WHERE id = '$raceid'";
			$result = mysql_query($query);
			$affected = mysql_affected_rows();
			if ($affected == 1) {
			// One race updated and marked as complete.
				echo "<p>Drivers entered and race status updated.</p>";
			}
			else {
			// No race marked complete, or more than 1
			echo "<p>Error. Number of races updated: ".$affected."</p>";
			}
		}
		else {
		// Number of drivers added is not 22
			echo "<p>Error. Number of drivers added: ".$n."</p>";
		}
	}
	else {
	// No drivers selected
		$message .= "<p>Error: No drivers selected.</p>";
	}
}

echo "<form action=\"".$_SERVER['PHP_SELF']."?page=raceentries\" method=\"post\">\n\n";

// Select all drivers
$query = "SELECT driverstoseasons.id, drivers.forename, drivers.surname, teams.team_name FROM drivers, teams, driverstoseasons, teamstoseasons WHERE driverstoseasons.drivers_id = drivers.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id AND teamstoseasons.teams_id = teams.id AND teamstoseasons.season = '$year' ORDER BY teams.team_name ASC, drivers.forename ASC, drivers.surname ASC";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	$driverid = $row['id'];
	$forename = $row['forename'];
	$surname = $row['surname'];
	$team = $row['team_name'];
	echo "<input type=\"checkbox\" checked=\"checked\" name=\"driver[]\" value=\"".$driverid."\" />".$forename." ".$surname." (".$team.")<br/>\n";
	}
	echo "\n";
}
else {
	$message .= "<p>No drivers found.</p>";
}

// Select all Grands Prix
$query = "SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '1' ORDER BY races.race_date ASC";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"race_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	$raceid = $row['id'];
	$grandprix = $row['grand_prix_name'];
	$track = $row['track_name'];
	$date = $row['race_date'];
	echo "<option value=\"".$raceid."\">".$grandprix." (".$date.")</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No Grands Prix to enter drivers to.</p>";
}

echo "<input type=\"submit\" value=\"Add Race Entries\" name=\"race_entries\" />\n\n";
echo "</form>\n\n";

echo $message;

?>
