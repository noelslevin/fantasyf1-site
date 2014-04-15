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
	
	$query = "SELECT * FROM races WHERE race_date = '$gpdate'";
	$result = mysql_query ($query);
	// If record does not already exist
	if (mysql_num_rows($result) == 0) {
		$query = "INSERT INTO races (trackstograndsprix_id, race_date, picksdeadline) VALUES ('$raceid', '$gpdate', '$deadline')";
		$result = mysql_query ($query);
		if (mysql_affected_rows() > 0) {
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
$query = "SELECT trackstograndsprix.id, tracks.track_name, grandsprix.grand_prix_name FROM trackstograndsprix, tracks, grandsprix WHERE trackstograndsprix.tracks_id = tracks.id AND trackstograndsprix.grandsprix_id = grandsprix.id ORDER BY grand_prix_name, track_name ASC";
$result = mysql_query ($query);
// If data is found
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"race_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$track = $row['track_name'];
		$gp = $row['grand_prix_name'];
		echo "<option value=\"".$id."\">".$gp." @ ".$track."</option>\n";
		
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