<?php

include '../../private/connection.php';

if (isset($_POST['raceid'])) {
	$output = NULL;
	$raceid = $_POST['raceid'];
	$sql = "SELECT username, teamname, fantasy_race_position, fantasy_race_points, fantasy_championship_points, grand_prix_name, track_name, race_date FROM view_ff1results WHERE races_id = :raceid ORDER BY fantasy_race_position ASC";
	$query = $dbh->prepare($sql);
	$query->execute(array(':raceid' => $_POST['raceid']));
	$row = $query->fetchAll(PDO::FETCH_ASSOC);
	if ($query->RowCount() > 0) {
		$top = TRUE;
		foreach ($row as $key=>$dataheader) {
			if ($top) {
				$racename = "<h1>".substr($dataheader['race_date'], 0, 4)." ".$dataheader['grand_prix_name']."</h1>\n";
				$resultspath = "../results/".substr($dataheader['race_date'], 0, 4)."/".$raceid."-";
				$output.= $racename;
				$output.= "<table>\n";
				$output.= "<thead>\n";
				$output.= "<tr><th>#</th><th>Username</th><th>Team</th><th>Score</th><th>Points</th></tr>\n";
				$output.= "</thead>\n";
				$output.= "<tbody>\n";
				$top = FALSE;
			}
		}
		foreach ($row as $data) {
			$output.= "<tr><td>".$data['fantasy_race_position']."</td><td>".$data['username']."</td><td>".$data['teamname']."</td><td>".$data['fantasy_race_points']."</td><td>".$data['fantasy_championship_points']."</td></tr>\n";
		}
		$output.= "</tbody>\n";
		$output.= "</table>\n";
		echo $output;
		$racename = $resultspath.substr(preg_replace("/-/", "/", str_replace(" ", "-", strtolower(trim(strip_tags($racename)))).".html", 1), 5);

		if (file_exists($racename)) {
			echo "<p>The results file already exists. Please manually overwrite if required.</p>";
		}
		else {
			// Create results file
			$fp = fopen($racename, "x") or die("can't open file");
			if (fwrite($fp, $output) !== FALSE) {
				echo "<p>File created</p>";
				fclose($fp);
			}
			else {
				echo "<p>File created, but no data written. Perhaps the file permissions are incorrect?</p>";
			}
		}

	}
	else {
		$error .= "<p>No data found.</p>";
		echo $error;
	}
}

else {

	$sql = "SELECT races_id, race_date, grand_prix_name FROM view_ff1allraces WHERE status = '6' ORDER BY race_date DESC";
	$query = $dbh->prepare($sql);
	$query->execute();
	$row = $query->fetchAll(PDO::FETCH_ASSOC);
	if ($query->RowCount() > 0) {
		echo "<form name=ff1results action=\"".$_SERVER['PHP_SELF']."\" method=post>\n";
		echo "<select name=raceid>\n";
		foreach ($row as $data) {
			echo "<option value=".$data['races_id'].">".substr($data['race_date'], 0, 4)." ".$data['grand_prix_name']."</option>\n";
		}
		echo "</select>\n";
		echo "<input type=submit name=submit value=\"Create results\">";
		echo "</form>";
	}
	else {
		echo "<p>No races found in the database.</p>";
	}
}

?>