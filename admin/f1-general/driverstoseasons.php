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
    $sql = $dbh->prepare("SELECT * FROM driverstoseasons WHERE drivers_id = :driverid AND teamstoseasons_id = :teamid");
    $sql->execute(array(':driverid' => $driverid, ':teamid' => $teamid));
    $row = $sql->fetchAll(PDO::FETCH_OBJ);
    if ($sql->rowCount() == 0) {
	// If record does not already exist
		$sql = $dbh->prepare("INSERT INTO driverstoseasons (drivers_id, teamstoseasons_id) VALUES (:driverid, :teamid)");
    $dbh->execute(array(':driverid' => $driverid, ':teamid' => $teamid));
		if ($sql->rowCount() > 0) {
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

// Select all active drivers from the database
$sql = $dbh->prepare("SELECT drivers.id, drivers.forename, drivers.surname FROM drivers WHERE status = 'A' ORDER BY drivers.forename, drivers.surname");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name = \"driver_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->id."\">".$result->forename." ".$result->surname."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$output .= "<p>No drivers found.</p>\n";
}
// Select all teams from the database
$sql = $dbh->prepare("SELECT teamstoseasons.id, teamstoseasons.teamname, teamstoseasons.season FROM teamstoseasons WHERE teamstoseasons.season = ':date' ORDER BY teamstoseasons.teamname");
$sql->execute(array(':date', $date));
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name = \"team_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->id."\">".$result->teamname." (".$result->season.")</option>\n";
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