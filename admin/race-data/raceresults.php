<h2>Submit Results</h2>
<p>Use this page to submit race results.</p>

<?php

// Set output message to NULL
$message = NULL;

if (isset($_POST['submitresults'])) {
	$position = $_POST['position'];
	$points = $_POST['points'];
	$raceentryid = $_POST['raceentryid'];
	$race = $_POST['race'];
  $sql = $dbh->prepare("UPDATE raceentries SET race_position = :position, race_points = :points WHERE id = :raceentryid");
  $sql->execute(array(':position' => $position, ':points' => $points, ':raceentryid' => $raceentryid));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
	if ($sql->rowCount() > 0) {
		echo "<p>Updated.</p>";
		}
	else {
		echo "<p>Nothing updated.</p>";
		}
}

// This runs if any data has been submitted
if (isset($_POST['resultspage']) or (isset($race))) {
	if (isset($race)) {
		$raceid = $race;
	}
	else {
		$raceid = $_POST['race_id'];
	}
	
	// Select all drivers from the race_id specified
  $sql = $dbh->prepare("SELECT * FROM `view_ff1allraceresults` WHERE id = :raceid AND ISNULL(race_position) ORDER BY fantasy_value DESC");
  $sql->execute(array(':raceid' => $raceid));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
	if ($sql->rowCount() > 0) {
		echo "<form action =\"".$_SERVER['PHP_SELF']."?page=raceresults\" method=\"post\">\n\n";
		echo "<select name=\"raceentryid\">\n";
		foreach ($row as $result) {
			// How do I do this?
			echo "<option value=\"".$result->raceentries_id."\">".$result->forename." ".$result->surname."</option>\n";
		}
		echo "</select>";
		echo "Position: <input type=\"text\" name=\"position\" />\n\n";
		echo "Points: <input type=\"text\" name=\"points\" />\n\n";
		echo "<input type=hidden name=race value=".$raceid." />\n";
		echo "<input type=\"submit\" value=\"Submit Result\" name=\"submitresults\" />\n\n";
		echo "</form>\n\n";

	}
	else {
		$message .= "<p>Error: no driver entries found for the specified Grand Prix.</p>";
	}
}

echo "<form action =\"".$_SERVER['PHP_SELF']."?page=raceresults\" method=\"post\">\n\n";

// Select all Grands Prix
$sql = $dbh->prepare("SELECT races.id, grandsprix.grand_prix_name, tracks.track_name, races.race_date FROM grandsprix, races, tracks, trackstograndsprix WHERE races.trackstograndsprix_id = trackstograndsprix.id AND trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id AND races.status = '4' ORDER BY races.race_date DESC");
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

echo "<input type=\"submit\" value=\"View Results Page\" name=\"resultspage\" />\n\n";
echo "</form>\n\n";

echo $message;

?>
