<?php

echo "<h2>Tracks To Grands Prix</h2>";

// Set output message to NULL
$message = NULL;

// This runs if any data has been submitted
if (isset($_POST['trackstograndsprix'])) {
	$trackid = $_POST['track_id'];
	$grandprixid = $_POST['grandsprix_id'];
  $sql = $dbh->prepare("SELECT * FROM trackstograndsprix WHERE tracks_id = :trackid AND grandsprix_id = :grandprixid");
  $sql->execute(array(':trackid' => $trackid, ':grandprixid' => $grandprixid));
  $row = $sql->fetchAll(PDO::FETCH_OBJ);  
	// If record does not already exist
	if ($sql->rowCount() == 0) {
    $sql = $dbh->prepare("INSERT INTO trackstograndsprix (tracks_id, grandsprix_id) VALUES (:trackid, :grandprixid)");
    $sql->execute(array(':trackid' => $trackid, ':grandprixid' => $grandprixid));
    $row = $sql->fetchAll(PDO::FETCH_OBJ);
		if ($sql->rowCount() > 0) {
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
$sql = $dbh->prepare("SELECT * FROM tracks ORDER by track_name ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
// If tracks are found
if ($sql->rowCount() > 0) {
	echo "<select name = \"track_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->ID."\">".$result->track_name."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No tracks found.</p>\n";
}
// Select all grands prix from the database
$sql = $dbh->prepare("SELECT * FROM grandsprix ORDER BY grand_prix_name ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
// If Grands Prix are found
if ($sql->rowCount() > 0) {
	echo "<select name = \"grandsprix_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->ID."\">".$result->grand_prix_name."</option>\n";
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