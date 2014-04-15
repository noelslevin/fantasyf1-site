<?php

$sql = $dbh->prepare("DELETE FROM fantasypicks WHERE fantasyuserstoseasons_id = :fantasyuser AND races_id = :race AND timepicked != :picktime");
$sql->execute(array(':fantasyuser' => $_SESSION['user_id'], ':race' => $raceid, ':picktime' => $deadline));
$totalrows = count($row);
if ($sql->rowCount() > 0) {
	$output .="<p>".$totalrows." previous picks were found and deleted for this race.</p>";
}
else {
	$output .="<p>No previous picks were found for this race.</p>";
}

?>