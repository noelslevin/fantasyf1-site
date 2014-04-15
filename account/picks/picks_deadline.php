<?php

$now = time();
$canpick = NULL;
$raceid = $_POST['raceid'];
$sql = $dbh->prepare("SELECT picksdeadline FROM races WHERE status = '3'");
$sql->execute(array(':raceid' => $raceid));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$totalrows = count($row);
if ($sql->rowCount() == 1) {
	$deadline = $row['picksdeadline'];
	if ($deadline > $now) { // MUST MAKE SURE THIS IS THE CORRECT WAY ROUND ( ">" ) AFTER TESTING!
		$canpick = 1;
	}
	else {
		$canpick = 0;
		$error .="<p>Sorry, you submitted your picks too late. The deadline has now passed. Any previous picks have been left in place.</p>";
	}
}
else {
	$error .="<p>No matching race could be found. You may have missed the picks deadline. If you're not sure, please report, and quote \"".$now."\".</p>";
}

?>