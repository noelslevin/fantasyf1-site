<?php

echo "<h2>Create Races</h2>";

// Set output message to NULL
$message = NULL;

// This runs if any data has been submitted
if (isset($_POST['createrace'])) {
	$raceid = $_POST['race_id'];
	$gpdate = $_POST['gpdate']; // Race date
	date_default_timezone_set('GMT'); // Ensure EPOCH time is working from GMT
	$deadline = strtotime($gpdate); // EPOCH time of race date
	$deadline = $deadline - 172800; // Subtract two days for Friday date (picks deadline)
	
  $sql = $dbh->prepare("SELECT * FROM races WHERE race_date = :gpdate");
  $sql->execute(array(':gpdate' => $gpdate));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);
	// If record does not already exist
	if ($sql->rowCount() == 0) {
    $sql = $dbh->prepare("INSERT INTO races (trackstograndsprix_id, race_date, picksdeadline) VALUES (:raceid, :gpdate, :deadline)");
    $sql->execute(array(':raceid' => $raceid, ':gpdate' => $gpdate, ':deadline' => $deadline));
		if ($sql->rowCount() > 0) {
			$message .= "<p>The record was successfully added into the database.</p>";
		}
		else {
			$message .= "<p>Error: the record was not entered into the database.</p>";
		}
	}
	else {
		$message .= "<p>Error: A race already exists on this day.</p>";
		}
}

echo "<form action =\"".$_SERVER['PHP_SELF']."?page=races\" method=\"post\">\n\n";

// Select all race/track combinations from the database
$sql = $dbh->prepare("SELECT trackstograndsprix.id, tracks.track_name, grandsprix.grand_prix_name FROM trackstograndsprix JOIN tracks ON trackstograndsprix.tracks_id = tracks.id JOIN grandsprix ON grandsprix.ID = trackstograndsprix.grandsprix_id WHERE	trackstograndsprix.status = 'A' ORDER BY grand_prix_name, track_name ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
// If data is found
if ($sql->rowCount() > 0) {
	echo "<select name = \"race_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->id."\">".$result->grand_prix_name." @ ".$result->track_name."</option>\n";
		
	}
	echo "</select>\n\n";
	echo "<input type=\"date\" value=\"\" name=\"gpdate\" />\n\n";
}
else {
	$message .= "<p>No race/track combinations found.</p>\n";
}

echo "<input type=\"submit\" value=\"Create Association\" name=\"createrace\" />\n\n";
echo "</form>\n\n";

echo $message;

?>