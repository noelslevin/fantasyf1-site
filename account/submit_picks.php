<!-- Need to get fantasyuserstoseasonsid from $_SESSION variable -->
<?php

$error = NULL;
$currentyear = date("Y"); //THIS MUST BE USED ONCE TESTING IS COMPLETE
//$currentyear = 2013;
$sql = $dbh->prepare("SELECT fantasyuserstoseasons.id, fantasyusers.username, fantasyteamstoseasons.season, fantasyteamstoseasons.teamname
FROM fantasyusers
INNER JOIN fantasyteams
INNER JOIN fantasyteamstoseasons ON fantasyteamstoseasons.fantasyteams_id = fantasyteams.id 
INNER JOIN fantasyuserstoseasons ON fantasyuserstoseasons.fantasyteamstoseasons_id = fantasyteamstoseasons.id AND fantasyuserstoseasons.fantasyusers_id = fantasyusers.id 
WHERE season = :season
AND fantasyusers.id = :fantasyuser");
$sql->execute(array(':season' => $currentyear, ':fantasyuser' => $_SESSION['user_id']));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$totalrows = count($row);
if ($sql->rowCount() == 1) {
	echo "<p>You are registered for this season's FantasyF1 Championship</p>";
	$fantasyuserstoseasonsid = $row['id'];
}
else {
	echo "<p>It looks like you're not registered for this season's FantasyF1 competition.</p>";
}

$message = NULL;
if (isset($_POST['submitpicks'])) {
	// Retrieve the raceid
	$raceid = $_POST['raceid'];
	// Check if picks are still open
	$sql = $dbh->prepare("SELECT picksdeadline FROM races WHERE id = :raceid");
	$sql->execute(array(':raceid' => $raceid));
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	$totalrows = count($row);
	if ($sql->rowCount() == 1) {
		$deadline = $row['picksdeadline'];
		$now = time();
		if ($deadline < $now) { // MUST MAKE SURE THIS IS THE CORRECT WAY ROUND ( "<" ) AFTER TESTING!
				echo "<p>Sorry, you submitted your picks too late. The deadline has now passed. Any previous picks have been left in place.</p>";
		}
		else {
			if (!empty($_POST['driverpicks'])) {
				$driverpicks = $_POST['driverpicks'];
				$N = count($driverpicks);
				
				// Enter new picks
				echo "<p>Drivers selected: ".$N."</p>";
				$numrows = 0;
				for($i=0; $i < $N; $i++) {
					// Enter pick and return status of entry
					$sql = $dbh->prepare("INSERT INTO fantasypicks (fantasyuserstoseasons_id, raceentries_id, races_id, timepicked) VALUES (:user, :driver, :race, :time)");
					$sql->execute(array(':user' => $fantasyuserstoseasonsid, ':driver' => $driverpicks[$i], ':race' => $raceid, ':time' => $now));
					$row = $sql->rowCount();
					if ($row == 1) {
						$numrows++;
					}
				}
				if ($numrows == $i && $numrows == $N) {
					echo "<p>".$N." picks were entered in the database.</p";
					$sql = $dbh->prepare("DELETE FROM fantasypicks WHERE fantasyuserstoseasons_id = :user AND races_id = :race AND timepicked != :time");
					$sql->execute(array(':user'=>$fantasyuserstoseasonsid, ':race'=>$raceid, ':time'=>$now));
					$row = $sql->rowCount();
					if ($row > 0) {
						echo "<p>".$row." previous picks deleted.</p>";
					}
					else {
						echo "<p>No previous picks deleted.</p>";
					}
				}
				else {
					echo "<p>There was an error submitting your picks. Please report.</p>";
				}
			}
			else {
				echo "<p>It looks like you didn't select any picks. Please pick your drivers from the form below and re-submit.</p>";
			}
		}
	}
	else {
		echo "<p>Error: no race found. This is a fundamental error. Please report.</p>";
	}
}

/*

Flow chart:

If picks {
	Check deadline
	If deadline hasn't passed {
		Check and submit new picks
		Delete old picks
	}
}

Show possible picks

*/

?>