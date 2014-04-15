<!-- Get registration details -->
<!-- Show current picks -->
<!-- Get picks -->
<!-- Process picks -->

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
AND races.status = 2
ORDER BY teams.id, drivers.id ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_ASSOC);
$totalrows = count($row);
echo "There are ".$totalrows." rows.\n";
if ($sql->rowCount() > 0) {
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=post>\n";
	$number = 0;
	foreach ($row as $result) {
		$number++;
		$driver = $result['forename']." ".$result['surname'];
		$team = $result['team_name'];
		$cost = $result['fantasy_value'];
		$raceentryid = $result['raceentryid'];
		$raceid = $result['races_id'];
		$deadline = $result['picksdeadline'];
		echo "<input type=checkbox name=driverpicks[] id=pick".$number." onclick=UpdateCost() value=".$raceentryid." />".$driver.", ".$team." (".$cost.")\n<br>\n";
		echo "<input type=hidden id=driverpick".$number." value=".$cost.">\n\n";
	}
	echo "<input type=text readonly=readonly id=totalcost value=\"0.0\">\n";
	echo "<input type=hidden name=raceid id=raceid value=".$raceid.">\n";
	echo "<input type=submit name=submitpicks id=submitpicks value=\"Submit Picks\" />\n";
	echo "</form>";
	echo "<p>Submitting picks will remove any previous picks for this race from the database.</p>";
}

else {
	echo "It looks like there's nothing to pick at the moment.";
}

?>

<script>
// The counter for i must agree with the number of race entries
function UpdateCost() {
  var submitObj = document.getElementById('submitpicks');
  var sum = 0;
  var gn, elem;
  for (i=1; i<23; i++) {
    gn = 'pick'+i;
    elem = document.getElementById(gn);
    hn = 'driverpick'+i;
    elem2 = document.getElementById(hn);
    if (elem.checked == true) { sum += Number(elem2.value); }
  }
  document.getElementById('totalcost').value = sum.toFixed(1);
  if (sum < 45.01) {
  submitObj.disabled = false;
  }
  else {
  submitObj.disabled = true;
  }
}
</script>
