<?php

echo "<h2>Tracks To Grands Prix</h2>";

// Set output message to NULL
$message = NULL;

// This runs if any data has been submitted
if (isset($_POST['trackstograndsprix'])) {
	$trackid = $_POST['track_id'];
	$grandprixid = $_POST['grandsprix_id'];
	$query = "SELECT * FROM trackstograndsprix WHERE tracks_id = '$trackid' AND grandsprix_id = '$grandprixid'";
	$result = mysql_query ($query);
	// If record does not already exist
	if (mysql_num_rows($result) == 0) {
		$query = "INSERT INTO trackstograndsprix (tracks_id, grandsprix_id) VALUES ('$trackid', '$grandprixid')";
		$result = mysql_query ($query);
		if (mysql_affected_rows() > 0) {
			$message .= "<p>The record was successfully added into the database.</p>";
		}
		else {
			$message .= "<p>Error: the record was not entered into the database.</p>";
		}
	}
	else {
		$message .= "<p>Error: This track/grand prix association already exists.</p>";
		}
}

echo "<form action =\"".$_SERVER['PHP_SELF']."?page=trackstograndsprix\" method=\"post\">\n\n";

// Select all tracks from the database
$query = "SELECT * FROM tracks ORDER by track_name ASC";
$result = mysql_query ($query);
// If tracks are found
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"track_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['ID'];
		$trackname = $row['track_name'];
		echo "<option value=\"".$id."\">".$trackname."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No tracks found.</p>\n";
}
// Select all grands prix from the database
$query = "SELECT * FROM grandsprix ORDER BY grand_prix_name ASC";
$result = mysql_query ($query);
// If Grands Prix are found
if (mysql_num_rows ($result) > 0) {
	echo "<select name = \"grandsprix_id\">\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['ID'];
		$name = $row['grand_prix_name'];
		echo "<option value=\"".$id."\">".$name."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No Grands Prix found.</p>\n";
}

echo "<input type=\"submit\" value=\"Create Association\" name=\"trackstograndsprix\" />\n\n";
echo "</form>\n\n";

echo $message;

?>