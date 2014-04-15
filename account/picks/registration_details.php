<?php

$registered = NULL;
$currentyear = date("Y");
$sql = $dbh->prepare("SELECT fantasyusers.username, fantasyteamstoseasons.teamname FROM fantasyusers INNER JOIN fantasyteams INNER JOIN fantasyteamstoseasons ON fantasyteamstoseasons.fantasyteams_id = fantasyteams.id INNER JOIN fantasyuserstoseasons ON fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id AND fantasyuserstoseasons.fantasyusers_id = fantasyusers.id 
WHERE season = :season AND fantasyusers.id = :fantasyuser");
$sql->execute(array(':season' => $currentyear, ':fantasyuser' => $_SESSION['user_id']));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$totalrows = count($row);
if ($sql->rowCount() == 1) {
	$registered = 1;
	$output .= "<p>You are registered for this season's FantasyF1 Championship.</p>";
	$output .= "<table class=standard>\n<thead><th>Username</th><th>Team</th></thead>\n";
	$output .= "<tbody>\n";
	$output .= "<td>".$row['username']."</td><td>".$row['teamname']."</td>";
	$output .= "</tbody>\n</table>\n";
}
else {
	$registered = 0;
	$error .= "<p>It looks like you're not registered for this season's FantasyF1 competition. This is probably because you've not been assigned a team yet.</p>";
}

?>