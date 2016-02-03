<h2>Race Entries</h2>
<p>This page is for adding drivers to races. Please note, the script currently assumes there should be 20 entrants per race.</p>

<?php

$year = date("Y");
$message = NULL;
$n = 0;

if (isset($_POST['race_entries'])) {
	$raceid = $_POST['race_id'];
	if (!empty($_POST['driver'])) {
		foreach ($_POST['driver'] as $driver) {
      $sql = $dbh->prepare("INSERT INTO raceentries (driverstoseasons_id, races_id) VALUES (:driver, :raceid)");
      $sql->execute(array(':driver' => $driver, ':raceid' => $raceid));
      $row = $sql->fetchAll(PDO::FETCH_OBJ);
			if ($sql->rowCount() == 1) {
			$n++;
				//$message .= "<p>Driver ID ".$driver." added to race ID ".$raceid." the database.</p>";
				// Driver added
			}
			else {
			// Driver not added
				$message .= "<p>Error. Entry not appended to the database.</p>";
			}
		}
		if ($n == 20) {
		// If 20 drivers have been added
		$sql = $dbh->prepare("UPDATE races SET status = 2 WHERE id = :raceid");
    $sql->execute(array(':raceid' => $raceid));
    $row = $sql->fetchAll(PDO::FETCH_OBJ);
			if ($sql->rowCount() == 1) {
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
$sql = $dbh->prepare("SELECT driverstoseasons.id, drivers.forename, drivers.surname, teams.team_name FROM drivers, teams, driverstoseasons, teamstoseasons WHERE driverstoseasons.drivers_id = drivers.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id AND teamstoseasons.teams_id = teams.id AND teamstoseasons.season = :year ORDER BY teams.team_name ASC, drivers.forename ASC, drivers.surname ASC");
$sql->execute(array(':year' => $year));
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	foreach ($row as $result) {
	echo "<input type=\"checkbox\" checked=\"checked\" name=\"driver[]\" value=\"".$result->id."\" />".$result->forename." ".$result->surname." (".$result->team_name.")<br/>\n";
	}
	echo "\n";
}
else {
	$message .= "<p>No drivers found.</p>";
}

// Select all Grands Prix
$sql = $dbh->prepare("SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '1' ORDER BY races.race_date ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name = \"race_id\">\n";
	foreach ($row as $result) {
	echo "<option value=\"".$result->id."\">".$result->grand_prix_name." (".$result->race_date.")</option>\n";
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
