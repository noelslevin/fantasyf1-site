<h2>Driver Values</h2>
<p>Use this page to assign values to drivers in the database</p>

<?php

// Set output message to NULL
$message = NULL;

// This runs if any data has been submitted
if (isset($_POST['drivervalues'])) {
	$raceid = $_POST['race_id'];

	// Select all drivers from the race_id specified
	$sql = $dbh->prepare("SELECT raceentries.id, raceentries.driverstoseasons_id, drivers.forename, drivers.surname, teams.team_name, races.id AS races_id, teamstoseasons.base_price, races.race_date FROM races, trackstograndsprix, tracks, grandsprix, drivers, teams, raceentries, driverstoseasons, teamstoseasons WHERE raceentries.races_id = races.id AND raceentries.driverstoseasons_id = driverstoseasons.id AND races.trackstograndsprix_id = trackstograndsprix.id AND driverstoseasons.drivers_id = drivers.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id AND teamstoseasons.teams_id = teams.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.id = :raceid ORDER BY teams.id ASC, drivers.id ASC");
  $sql->execute(array(':raceid' => $raceid));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
  if ($sql->rowCount() > 0) {
		echo "<table>\n";
		echo "<thead>\n";
		echo "<tr>\n";
		echo "<th>Name</th>";
		echo "<th>Team</th>";
		echo "<th>Race 1</th>";
		echo "<th>Race 2</th>";
		echo "<th>Race 3</th>";
		echo "<th>Race 4</th>";
		echo "<th>Race 5</th>";
		echo "<th>Total</th>\n";
		echo "<th>Value</th>\n";
		echo "<th>Updated?</th>\n";
		echo "</tr>\n";
		echo "</thead>\n";
		echo "<tbody>\n";
		foreach ($row as $result) {
			// This variable will be used for accessing the correct array positions
			$arraynum = 0;
			// Set up the arrays
			$driverid[] = $result->driverstoseasons_id;
			$forename[] = $result->forename;
			$surname[] = $result->surname;
			$teamname[] = $result->team_name;
			$baseprice[] = $result->base_price;
			$racedate[] = $result->race_date;
			$entryid[] = $result->id;
		}
		foreach ($driverid as $f1driverid) {
			// Set variable to 0 as it only needs to count to 5
			$x = 0;
			// Set driver's price to 0
			$drivervalue = 0;
			// Show driver details
			echo "<tr>";
			echo "<td>".$forename[$arraynum]." ".$surname[$arraynum]."</td>";
			echo "<td>".$teamname[$arraynum]."</td>";
			
			// Find recent results - must be in the current season, with the current drivers, at their current team, and no more than 5
			$sql = $dbh->prepare("SELECT raceentries.id AS raceentryid, raceentries.driverstoseasons_id, drivers.forename, drivers.surname, teams.team_name, races.id AS races_id, raceentries.race_points FROM races, trackstograndsprix, tracks, grandsprix, drivers, teams, raceentries, driverstoseasons, teamstoseasons WHERE raceentries.races_id = races.id AND raceentries.driverstoseasons_id = driverstoseasons.id AND races.trackstograndsprix_id = trackstograndsprix.id AND driverstoseasons.drivers_id = drivers.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id AND teamstoseasons.teams_id = teams.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND driverstoseasons.id = :driverid AND races.race_date < :racedate ORDER BY races.race_date DESC LIMIT 5");
      $sql->execute(array(':driverid' => $driverid[$arraynum], ':racedate' => $racedate[$arraynum]));
      $row = $sql->fetchAll(PDO::FETCH_OBJ);
      if ($sql->rowCount() > 0) {
				foreach ($row as $result) {
					// For each recent race found
					$racepoints = $result->race_points;
					$drivervalue = $drivervalue + $racepoints;
					echo "<td>".$racepoints."</td>";
					// Increment x
					$x++;
				}
			}
		// Check to see if we have found five, otherwise, fill with base values
		while ($x < 5) {
			$drivervalue = $drivervalue + $baseprice[$arraynum];
			echo "<td><em>".$baseprice[$arraynum]."<strong></em>";
			// Increment x
			$x++;
		}		
		echo "<td><strong>".$drivervalue."</strong></td>";
		$cost = $drivervalue / 5;
		if ($cost < 1) {
			$cost = 1;
		}
		echo "<td><strong><em>".$cost."</em></strong></td>";
		// Try to update the value in the database.
		$sql = $dbh->prepare("UPDATE raceentries SET fantasy_value = '$cost' WHERE id = '$entryid[$arraynum]'");
    $sql->execute();
		//echo "<p>".$updatequery."</p>";
		if ($sql->rowCount() > 0) {
			echo "<td>Yes</td>";
		}
		else {
			echo "<td>No</td>";
		}
		
		echo "</tr>";
		$arraynum++;
		}
		echo "</tbody>";
		echo "</table>";
	}
	else {
		$message .= "<p>Error: no driver entries found for the specified Grand Prix.</p>";
	}
	echo "</tbody>";
	echo "</table>";
	if ($arraynum > 0) {
		$sql = $dbh->prepare("UPDATE races SET status = '3' WHERE id = '$raceid'");
    $sql->execute();
	}
}


echo "<form action =\"".$_SERVER['PHP_SELF']."?page=drivervalues\" method=\"post\">\n\n";

// Select all Grands Prix
$sql = $dbh->prepare("SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '2' ORDER BY races.race_date DESC");
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
	$message .= "<p>No Grands Prix found.</p>";
}

echo "<input type=\"submit\" value=\"Calculate Values\" name=\"drivervalues\" />\n\n";
echo "</form>\n\n";



echo $message;

?>