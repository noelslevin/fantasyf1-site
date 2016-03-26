<h2>Fantasy Results</h2>
<p>Used for processing fantasy picks and points.</p>

<?php

// Runs if we want to work out user points for a race - runs after Fantasy Results
if (isset($_POST['fantasy_race_points'])) {
	$raceid = $_POST['race_id'];
	$sql = $dbh->prepare("SELECT fantasyraceentries.id, fantasyraceentries.fantasy_race_position, fantasyusers.username, fantasyteams.fantasyteam_name, fantasyraceentries.fantasy_race_points FROM fantasyusers, fantasyraceentries, fantasyteams, fantasyuserstoseasons, fantasyteamstoseasons WHERE fantasyraceentries.fantasyuserstoseasons_id = fantasyuserstoseasons.id AND fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id AND fantasyuserstoseasons.fantasyusers_id = fantasyusers.id AND fantasyteamstoseasons.fantasyteams_id = fantasyteams.id AND fantasyraceentries.races_id = :raceid ORDER BY fantasyraceentries.fantasy_race_points DESC, fantasyraceentries.picksbinary DESC, fantasyraceentries.timepicked ASC");
  $sql->execute(array(':raceid' => $raceid));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
  if ($sql->rowCount() > 0) {
		$pos = 0;
		foreach ($row as $result) {
			$pos++;
			switch ($pos) {
				case 1: $points = 25;
				break;
				case 2: $points = 18;
				break;
				case 3: $points = 15;
				break;
				case 4: $points = 12;
				break;
				case 5: $points = 10;
				break;
				case 6: $points = 8;
				break;
				case 7: $points = 6;
				break;
				case 8: $points = 4;
				break;
				case 9: $points = 2;
				break;
				case 10: $points = 1;
				break;
				default: $points = 0;
			}
			$sql = $dbh->prepare("UPDATE fantasyraceentries SET fantasy_race_position = ?, fantasy_championship_points = ? WHERE id = ?");
      $sql->execute(array($pos, $points, $result->id));
      if ($sql->rowCount() != 1) {
        echo "<p>Error: result not updated.</p>";
      }
      $points = NULL;
		}
		echo "<p>Points processed - check table to confirm values are correct.</p>";
		$sql = $dbh->prepare("UPDATE races SET status = '6' WHERE id = :raceid");
    $sql->execute(array(':raceid' => $raceid));
    if ($sql->rowCount() != 1) {
      echo "<p>Error. No race id updated.</p>";
    }
	}
	else {
		echo "<p>Error. No data found.</p>";
	}
}

// If we want a results table.
else if (isset($_POST['results_table'])) {
// Query for producing a results table
$raceid = $_POST['race_id'];
$sql = $dbh->prepare("SELECT fantasyusers.username, fantasyteamstoseasons.teamname, fantasyraceentries.fantasy_race_points, fantasyraceentries.fantasy_championship_points
FROM fantasyusers, fantasyraceentries, fantasyteams, fantasyuserstoseasons, fantasyteamstoseasons WHERE fantasyraceentries.fantasyuserstoseasons_id = fantasyuserstoseasons.id AND fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id AND fantasyuserstoseasons.fantasyusers_id = fantasyusers.id AND fantasyteamstoseasons.fantasyteams_id = fantasyteams.id AND fantasyraceentries.races_id = :raceid ORDER BY fantasyraceentries.fantasy_race_points DESC, fantasyraceentries.picksbinary DESC, fantasyraceentries.timepicked ASC");
$sql->execute(array(':raceid' => $raceid));
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	$num = 0;
	echo "<table><tr><td>Position</td><td>Name</td><td>Team</td><td>Race Points</td><td>FF1 Points</td></tr>\n";
	foreach ($row as $result) {
		$num++;
		echo "<tr><td>".$num."</td><td>".$result->username."</td><td>".$result->teamname."</td><td>".$result->fantasy_race_points."</td><td>".$result->fantasy_championship_points."</td></tr>\n";
	}
	echo "</table>\n\n";
}
else {
	echo "<p>Error: Results not found.</p>";
}

}

// If Fantasy Results are to be processed.

else if (isset($_POST['fantasy_results'])) {
	$raceid = $_POST['race_id'];

// This query needs to pull data from every table - make sure the joins are properly coded!

// Need to select results, then select each user, and rund each user against results, creating 24-number code for each user.

$loops = 0; // Number of times loop has completed successfully
$usernum = 0; // Number of users, and therefore loops expected.
$nopicks = 0;

// Get race results
$sql = $dbh->prepare("SELECT drivers.forename, drivers.surname, teamstoseasons.teamname, raceentries.id AS raceentryid, raceentries.race_position, raceentries.race_points FROM drivers, teams, teamstoseasons, driverstoseasons, raceentries, races WHERE teamstoseasons.teams_id = teams.id
AND driverstoseasons.drivers_id = drivers.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id AND raceentries.driverstoseasons_id = driverstoseasons.id AND raceentries.races_id = races.id AND races.id = :raceid ORDER BY raceentries.race_position ASC");
$sql->execute(array(':raceid' => $raceid));
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	foreach ($row as $result) {
		$raceentryid[] = $result->raceentryid; // This is the race entry id of the driver, is available to the rest of the page.
	}
}
else {
	echo "<p>No race results found.</p>";
}

$year = date("Y");
// Need to query all fantasy users now
$sql = $dbh->prepare("SELECT fantasyuserstoseasons.id, fantasyusers.username FROM fantasyusers, fantasyuserstoseasons, fantasyteamstoseasons WHERE fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id AND fantasyuserstoseasons.fantasyusers_id = fantasyusers.id AND fantasyteamstoseasons.season = :year ORDER BY fantasyusers.id ASC");
$sql->execute(array(':year' => $year));
$row = $sql->fetchAll(PDO::FETCH_OBJ);

if ($sql->rowCount() > 0) {
	foreach ($row as $result) {
		$usernum++;
		$userid = $result->id;
		$username = $result->username;
		
		// For each fantasy user, query picks from the current race, which will be pulled through from a form
		$sql = $dbh->prepare("SELECT raceentries.id AS raceentryid, raceentries.race_points, fantasypicks.timepicked FROM drivers, teams, teamstoseasons, driverstoseasons, races, raceentries, fantasypicks, fantasyuserstoseasons, fantasyusers WHERE teamstoseasons.teams_id = teams.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id AND driverstoseasons.drivers_id = drivers.id AND raceentries.driverstoseasons_id = driverstoseasons.id AND raceentries.races_id = races.id AND fantasypicks.fantasyuserstoseasons_id = fantasyuserstoseasons.id AND fantasyuserstoseasons.fantasyusers_id = fantasyusers.id AND fantasypicks.raceentries_id = raceentries.id AND fantasypicks.races_id = races.id AND races.id = :raceid AND fantasyuserstoseasons.id = :userid");
    $sql->execute(array(':raceid' => $raceid, ':userid' => $userid));
    $row = $sql->fetchALL(PDO::FETCH_OBJ);
		if ($sql->rowCount() > 0) {
			// Run for anything to be run once per user
			$points = 0;
			//echo "<p>";
			//echo $username."<br/>";
			foreach ($row as $result) {
				// Run for anything that needs to be run once per pick
				$timepicked[] = $result->timepicked;
				$picksid[] = $result->raceentryid; // This is the race entry id picked, but to avoid conflict with the previous array, will call it picksid as it's the raceentryid that is picked by the fantasy user
				$points = $points + $result->race_points;
			}
				
				// Compare the two arrays, starting with the first array as that needs to order sequentially.
				$y = NULL; // 24-number array value
				foreach ($raceentryid as $finishingposition) { // For each race position (and associated raceentryid)
					$x = 0; // Checks for match
						foreach ($picksid as $pick) {
							if ($x == 0) { // If no match found so far
								if ($pick == $finishingposition) {
									$x = 1;
									//echo "<p>".$pick." / ".$finishingposition." (".$x.")";
								}
								else {
									//echo "<p>".$pick." / ".$finishingposition." (".$x.")";
								}
							}
						}
					$y .= $x;
				}
			// Back to once per user data
			//echo $y." (".strlen($y).")</p>";
			$sql = $dbh->prepare("INSERT INTO fantasyraceentries (fantasyuserstoseasons_id, races_id, picksbinary, timepicked, fantasy_race_points) VALUES ('$userid', '$raceid', '$y', '$timepicked[0]', '$points')");
      $sql->execute();
      if ($sql->rowCount() == 1) {
				$loops++;
			}
			else {
				echo "<p>Error. User #".$userid." not entered in the database.</p>";
			}
			//echo "<p>".$insertquery."</p>";
			unset($picksid);
			unset($timepicked);
		}
		else {
			// No picks found for this user.
			$nopicks++;
		}
	}
}
else {
	echo "<p>Error: No fantasy users found.</p>";
}

if ($loops == $usernum) {
	echo "<p>".$loops." users found and looped successfully.</p>";
}
else {
	echo "<p>".$usernum." users found, but only ".$loops." users looped successfully. ".$nopicks." users appear not to have picked for the selected race.</p>";
}

$sql = $dbh->prepare("UPDATE races SET status = '5' WHERE races.id = '$raceid'");
$sql->execute();
if ($sql->rowCount() == 1) {
	echo "<p>Race marked as complete.</p>";
}
else {
	echo "<p>Race not marked complete.</p>";
}

}

// Select all non-complete Grands Prix
echo "<h3>Calculate Fantasy Results</h3>";
$sql = $dbh->prepare("SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '4' ORDER BY races.race_date ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<form action=\"".$_SERVER['PHP_SELF']."?page=ff1results\" method=\"post\">\n";
	echo "<select name = \"race_id\">\n";
	foreach ($row as $result) {
	$raceid = $result->id;
	$grandprix = $result->grand_prix_name;
	$track = $result->track_name;
	$date = $result->race_date;
	echo "<option value=\"".$raceid."\">".$grandprix." (".$date.")</option>\n";
	}
	echo "</select>\n\n";
	echo "<input type=\"submit\" value=\"Process Fantasy Results\" name=\"fantasy_results\" />\n\n";
	echo "</form>\n\n";
}
else {
	echo "<p>No Grands Prix found.</p>";
}



// Query for producing a table for points
echo "<h3>Calculate Fantasy Points For A Grand Prix</h3>";
$sql = $dbh->prepare("SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '5' ORDER BY races.race_date DESC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<form action=\"".$_SERVER['PHP_SELF']."?page=ff1results\" method=\"post\">\n";
	echo "<select name = \"race_id\">\n";
	foreach ($row as $result) {
	$raceid = $result->id;
	$grandprix = $result->grand_prix_name;
	$track = $result->track_name;
	$date = $result->race_date;
	echo "<option value=\"".$raceid."\">".$grandprix." (".$date.")</option>\n";
	}
	echo "</select>\n\n";
	echo "<input type=\"submit\" value=\"Calculate Fantasy Race Points\" name=\"fantasy_race_points\" />\n\n";
	echo "</form>\n\n";
}
else {
	echo "<p>No completed Grands Prix found.</p>";
}



// Query for producing a results table
echo "<h3>View Fantasy Results Tables</h3>";
$sql = $dbh->prepare("SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '6' ORDER BY races.race_date DESC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<form action=\"".$_SERVER['PHP_SELF']."?page=ff1results\" method=\"post\">\n";
	echo "<select name = \"race_id\">\n";
	foreach ($row as $result) {
	$raceid = $result->id;
	$grandprix = $result->grand_prix_name;
	$track = $result->track_name;
	$date = $result->race_date;
	echo "<option value=\"".$raceid."\">".$grandprix." (".$date.")</option>\n";
	}
	echo "</select>\n\n";
	echo "<input type=\"submit\" value=\"Display Fantasy Results Table\" name=\"results_table\" />\n\n";
	echo "</form>\n\n";
}
else {
	echo "<p>No completed Grands Prix found.</p>";
}
?>