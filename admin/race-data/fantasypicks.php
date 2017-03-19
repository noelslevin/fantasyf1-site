<?php

$sql = $dbh->prepare("SELECT id AS user_id, username, 
(SELECT SUM(fantasy_value) FROM `view_ff1allfantasypicks` WHERE status = 3 AND fantasyusers_id = user_id) AS spent,
CASE WHEN (SELECT COUNT(*) FROM `view_ff1allfantasypicks` WHERE fantasyusers_id = user_id AND (status = '3' OR status = '4')) > 0 THEN 'Picked' ELSE CONCAT('<a href=mailto:',(SELECT email_address FROM fantasyusers WHERE fantasyusers.id = user_id),'>Email reminder</a>') END AS picks
FROM fantasyusers WHERE status = 'A' ORDER BY picks ASC, spent DESC, username ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_ASSOC);
if ($sql->rowCount() > 0) {
	echo "<h2>Picks for current race</h2>\n";
	echo "<table class=standard>\n<thead><th>User</th><th>Spent</th><th>Status</th></thead>\n";
	echo "<tbody>\n";
	foreach ($row as $result) {
		echo "<tr><td>".$result['username']."</td><td>".$result['spent']."</td><td>".$result['picks']."</td></tr>\n";
	}
	echo "</tbody>\n</table>\n";
}

else {
	echo "<p>No results found. Is there an active race?</p>\n";
}

?>