<h1>2015 FantasyF1 Standings</h1>
<h2>Drivers' Standings</h2>
<?php
$season = '2015';
echo "<table>\n";
echo "<thead><tr><th class=position>Position</th><th class=name>Name</th><th class=team>Team</th><th class=points>Points</th><th class=total>(Score)</th></tr></thead>\n<tbody>\n";
$num = 0;
$sql="SELECT * FROM view_ff1individualstandingsbyyear WHERE season = '$season' ORDER BY points DESC, total DESC, username ASC";
foreach ($dbh->query($sql) as $row) {
	$num++;
	$username = $row['username'];
	$total = $row['total'];
	$team = $row['team'];
	$points = $row['points'];
	echo "<tr><td class=position>".$num."</td><td class=name>".$username."</td><td class=team>".$team."</td><td class=points>".$points."</td><td class=total>".$total."</td></tr>\n";
}
echo "</tbody>\n</table>\n\n";
?>
<h2>Constructors' Standings</h2>

<?php
echo "<table>\n";
echo "<thead><tr><th>Position</th><th>Team</th><th>Points</th><th>(Score)</th></tr></thead>\n<tbody>\n\n";
$num = 0;
$sql="SELECT * FROM view_ff1teamstandingsbyyear WHERE season = '$season' ORDER BY points DESC, total DESC, team ASC";
foreach ($dbh->query($sql) as $row) {
	$num++;
	$team = $row['team'];
	$total = $row['total'];
	$points = $row['points'];
	echo "<tr><td>".$num."</td><td>".$team."</td><td>".$points."</td><td>".$total."</td></tr>\n";
}
echo "</tbody>\n</table>\n\n";
?>