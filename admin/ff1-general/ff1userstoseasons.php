<?php

$message = NULL;

if(isset($_POST['fantasyuserstoseasons'])) {
	$userid = $_POST['fantasy_user_id'];
	$teamid = $_POST['fantasyteam_id'];
	$sql = $dbh->prepare("INSERT INTO fantasyuserstoseasons (fantasyusers_id, fantasyteamstoseasons_id) VALUES (:userid, :teamid)");
  $sql->execute(array(':userid' => $userid, ':teamid' => $teamid));
  if ($sql->rowCount() == 1) {
		$message .= "<p>The record was successfully added into the database.</p>";
	}
	else {
		$message .= "<p>Error: the record was not entered into the database.</p>";
	}

}

echo "<h2>Fantasy Users To Seasons</h2>\n";

echo "<form action=".$_SERVER['PHP_SELF']."?page=ff1userstoseasons method=post>\n\n";

// Select all active fantasy users not not linked to a team in the current year
$sql = $dbh->prepare("SELECT fantasyusers.id AS fantasyusers_id, fantasyusers.username FROM fantasyusers
WHERE status = 'A' AND username NOT IN (SELECT username FROM `fantasyusers` INNER JOIN fantasyteamstoseasons INNER JOIN fantasyuserstoseasons ON fantasyuserstoseasons.fantasyusers_id = fantasyusers.id AND fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id WHERE fantasyteamstoseasons.season = YEAR(CURDATE())) ORDER BY username ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name=fantasy_user_id>\n";
	foreach ($row as $result) {
		echo "<option value=".$result->fantasyusers_id.">".$result->username."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No fantasy users found.</p>\n";
}
// Select all fantasy teams from the database – only displays teams from the current year
$sql = $dbh->prepare("SELECT fantasyteamstoseasons.id AS teamstoseasons_id, fantasyteamstoseasons.teamname, (SELECT COUNT(*) FROM fantasyuserstoseasons WHERE fantasyteamstoseasons_id = teamstoseasons_id) AS count, fantasyteamstoseasons.season FROM fantasyteamstoseasons WHERE season = YEAR(CURDATE()) ORDER BY teamname ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name=fantasyteam_id>\n";
	foreach ($row as $result) {
		// Only show team if there is a place available
		if ($result->count < 2) {
			echo "<option value=".$result->teamstoseasons_id.">".$result->teamname." (".$result->season.")</option>\n";
		}
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No fantasy teams found.</p>\n";
}

echo "<input type=submit value=\"Create Association\" name=fantasyuserstoseasons />\n\n";
echo "</form>\n\n";

echo $message;

?>