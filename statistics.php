<?php

include '../private/connection.php';

if (strpos($filename,'/') !== false) {
	if (file_exists($filename)) {
		include $filename;
	}
else {
	header("HTTP/1.1 404 Not Found");
	include '404.php';
	}
}
else {
	echo "<h1>Statistics</h1>";
  echo "<p>On this page are some fun statistics, which are automatically updated at the end of every race. They will be improved and added to as time allows.</p>";
}

$i = 0;
$fileoutput = NULL;
$record = NULL;
$recordvalue = NULL;
$records = (array("Most total points", "Largest total score", "Highest race score", "Lowest race score", "Most wins", "Most podiums", "Most race scores", "Most scoreless races", "Most race entries", "Highest average race score", "Lowest average race score"));
$statement[0] = "SELECT username as Username, SUM(points) as Points FROM view_ff1individualstandingsbyyear
GROUP BY username ORDER BY Points DESC LIMIT 1";
$statement[1] = "SELECT username Username, SUM(total) Total FROM view_ff1individualstandingsbyyear GROUP BY username ORDER BY Total DESC LIMIT 1";
$statement[2] = 'SELECT username, CONCAT(fantasy_race_points, " (", YEAR(race_date), " ", grand_prix_name, ")") AS result FROM view_ff1results ORDER BY fantasy_race_points DESC LIMIT 1';
$statement[3] = 'SELECT username, CONCAT(fantasy_race_points, " (", YEAR(race_date), " ", grand_prix_name, ")") AS result FROM view_ff1results ORDER BY fantasy_race_points ASC LIMIT 1';
$statement[4] = "SELECT username, COUNT(*) AS Wins FROM view_ff1results WHERE fantasy_race_position = 1 GROUP BY username ORDER BY Wins DESC LIMIT 1";
$statement[5] = "SELECT username, COUNT(*) AS Podiums FROM view_ff1results WHERE fantasy_race_position < 4 GROUP BY username ORDER BY Podiums DESC LIMIT 1";
$statement[6] = "SELECT username, COUNT(*) AS Scores FROM view_ff1results WHERE fantasy_championship_points > 0 GROUP BY username ORDER BY Scores DESC LIMIT 1";
$statement[7] = "SELECT username, COUNT(*) AS Scores FROM view_ff1results WHERE fantasy_championship_points = 0 GROUP BY username ORDER BY Scores DESC LIMIT 1";
$statement[8] = "SELECT username, COUNT(*) AS Entries FROM view_ff1results GROUP BY username
ORDER BY Entries DESC LIMIT 1";
$statement[9] = 'SELECT CONCAT(YEAR(race_date), " ", grand_prix_name) AS race, AVG(fantasy_race_points) AS Average FROM view_ff1results GROUP BY races_id ORDER BY Average DESC LIMIT 1';
$statement[10] = 'SELECT CONCAT(YEAR(race_date), " ", grand_prix_name) AS race, AVG(fantasy_race_points) AS Average FROM view_ff1results GROUP BY races_id ORDER BY Average ASC LIMIT 1';

for ($i = 0; $i < count($statement); $i++) {
	$sql = $dbh->prepare($statement[$i]);
	$sql->execute();
	$row = $sql->fetch(PDO::FETCH_NUM);
	if ($sql->rowCount() > 0) {
		$fileoutput .= "<tr><td>".$records[$i]."</td><td>".$row[0]."</td><td>".$row[1]."</td></tr>\n";
	}
}

?>

<table>
	<thead>
		<tr><th>Record</th><th>Held By</th><th>Value</th></tr>
	</thead>
	<tbody>
	<?php echo $fileoutput; ?>
	</tbody>
</table>