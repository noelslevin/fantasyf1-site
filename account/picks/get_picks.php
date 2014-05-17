<?php

$sql = $dbh->prepare("SELECT raceentries.id AS raceentryid, drivers.forename, drivers.surname, teams.team_name, raceentries.fantasy_value, races.id AS races_id, races.picksdeadline FROM drivers
INNER JOIN teams
INNER JOIN teamstoseasons ON teamstoseasons.teams_id = teams.id 
INNER JOIN driverstoseasons ON driverstoseasons.drivers_id = drivers.id AND driverstoseasons.teamstoseasons_id = teamstoseasons.id
INNER JOIN tracks
INNER JOIN grandsprix
INNER JOIN trackstograndsprix ON trackstograndsprix.grandsprix_id = grandsprix.id AND trackstograndsprix.tracks_id = tracks.id 
INNER JOIN races ON races.trackstograndsprix_id = trackstograndsprix.id 
INNER JOIN raceentries ON  raceentries.races_id = races.id AND raceentries.driverstoseasons_id = driverstoseasons.id
AND races.status = '3'
ORDER BY teams.id, drivers.id ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_ASSOC);
$totalrows = count($row);
//echo "There are ".$totalrows." rows.\n";
if ($sql->rowCount() > 0) {
	$output .= "<form action=\"".$_SERVER['PHP_SELF']."\" method=post>\n";
	$output .= "<div class=\"picks\">";
	$number = 0;
	foreach ($row as $result) {
		$number++;
		$driver = $result['forename']." ".$result['surname'];
		$team = $result['team_name'];
		$cost = $result['fantasy_value'];
		$raceentryid = $result['raceentryid'];
		$raceid = $result['races_id'];
		$deadline = $result['picksdeadline'];
		$output .= "<input type=checkbox name=driverpicks[] id=pick".$number." onclick=UpdateCost() value=".$raceentryid." />".$driver.", ".$team." (".$cost.")\n<br>\n";
		$output .= "<input type=hidden id=driverpick".$number." value=".$cost.">\n\n";
	}
	$output .= "</div>";
	$output .= "<input type=text readonly=readonly id=totalcost value=\"0.0\">\n";
	$output .= "<input type=hidden name=raceid id=raceid value=".$raceid.">\n";
	$output .= "<input type=submit name=submitpicks id=submitpicks value=\"Submit Picks\" />\n";
	$output .= "</form>";
	$output .= "<p>Submitting picks will remove any previous picks for this race from the database.</p>";
	$output .= "<script src=picks/drivercost.js></script>";
}

else {
	$output .= "<p>Sorry, picks are currently closed.</p>";
}

?>
