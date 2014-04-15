<?php
$sql = $dbh->prepare("SELECT * FROM `view_ff1allfantasypicks` WHERE (status = '3' OR status = '4') AND fantasyusers_id = :userid");
$sql->execute(array(':userid' => $_SESSION['user_id']));
$row = $sql->fetchAll(PDO::FETCH_ASSOC);
if ($sql->rowCount() > 0) {
	$output .= "<h2>Current Picks</h2>\n";
	$output .= "<p>If you submit new picks, your current picks will be deleted.</p>";
	$output .= "<table class=standard>\n<thead><th>Driver</th><th>Team</th><th>Cost</th></thead>\n";
	$output .= "<tbody>\n";
	foreach ($row as $result) {
		$output .= "<tr><td>".$result['forename']." ".$result['surname']."</td><td>".$result['team_name']."</td><td>".$result['fantasy_value']."</td></tr>\n";
	}
	$output .= "</tbody>\n</table>\n";
}
else {
	$output .= "<p>You do not appear to have any current picks for this race.</p>";
}


?>