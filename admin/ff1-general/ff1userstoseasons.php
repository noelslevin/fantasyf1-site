<?php

$message = NULL;

if(isset($_POST['fantasyuserstoseasons'])) {
	$userid = $_POST['fantasy_user_id'];
	$teamid = $_POST['fantasyteam_id'];
	$query = "INSERT INTO fantasyuserstoseasons (fantasyusers_id, fantasyteamstoseasons_id) VALUES ('$userid', '$teamid')";
	$result = mysql_query($query);
	if ($result) {
		$message .= "<p>The record was successfully added into the database.</p>";
	}
	else {
		$message .= "<p>Error: the record was not entered into the database.</p>";
	}

}

echo "<h2>Fantasy Users To Seasons</h2>\n";

echo "<form action=".$_SERVER['PHP_SELF']."?page=ff1userstoseasons method=post>\n\n";

// Select all fantasy users not registered but not linked to a team in the current year
$query = "SELECT fantasyusers.id AS fantasyusers_id, fantasyusers.username FROM fantasyusers
WHERE registered = 1 AND username NOT IN (SELECT username FROM `fantasyusers` INNER JOIN fantasyteamstoseasons INNER JOIN fantasyuserstoseasons ON fantasyuserstoseasons.fantasyusers_id = fantasyusers.id AND fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id WHERE fantasyteamstoseasons.season = YEAR(CURDATE())) ORDER BY username ASC";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	echo "<select name=fantasy_user_id>\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['fantasyusers_id'];
		$username = $row['username'];
		echo "<option value=".$id.">".$username."</option>\n";
	}
	echo "</select>\n\n";
}
else {
	$message .= "<p>No fantasy users found.</p>\n";
}
// Select all fantasy teams from the database – only displays teams from the current year
$query = "SELECT fantasyteamstoseasons.id AS teamstoseasons_id, fantasyteamstoseasons.teamname, (SELECT COUNT(*) FROM fantasyuserstoseasons WHERE fantasyteamstoseasons_id = teamstoseasons_id) AS count, fantasyteamstoseasons.season FROM fantasyteamstoseasons WHERE season = YEAR(CURDATE()) ORDER BY teamname ASC";
$result = mysql_query ($query);
if (mysql_num_rows ($result) > 0) {
	echo "<select name=fantasyteam_id>\n";
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['teamstoseasons_id'];
		$name = $row['teamname'];
		$season = $row['season'];
		$count = $row['count'];
		// Only show team if there is a place available
		if ($count < 2) {
			echo "<option value=".$id.">".$name." (".$season.")</option>\n";
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