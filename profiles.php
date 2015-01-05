<?php 

include '../private/connection.php';

if (strpos($filename,'/') !== false) {
	define("USERPROFILE", $root[1]);
	if (ctype_alnum(constant("USERPROFILE"))) { // Checks to see if username string is alphanumeric
		echo "<h1>".ucfirst(constant("USERPROFILE"))."</h1>"; ?>

            <div class="small-12 medium-4 column trophies">
                <div><object class=trophy type="image/svg+xml" data="/assets/svg/trophies/trophy-gold.svg"></object></div>
                <?php include 'includes/user-wins.php'; ?>
            </div>
            <div class="small-12 medium-4 column trophies">
                <div><object class=trophy type="image/svg+xml" data="/assets/svg/trophies/trophy-silver.svg"></object></div>
                <?php include 'includes/user-podiums.php'; ?>
            </div>
            <div class="small-12 medium-4 column trophies">
                <div><object class=trophy type="image/svg+xml" data="/assets/svg/trophies/trophy-bronze.svg"></object></div>
                <?php include 'includes/user-point-scores.php'; ?>
            </div>
        
        <?php
		// Season records
		echo "<h2>Results by season</h2>";
		echo "<table>\n";
		echo "<thead><tr><th>Season</th><th>Team</th><th>Score</th><th>Points</th></tr></thead>\n";
		$num = 0;
		$sql="SELECT season, team, points, total FROM view_ff1individualstandingsbyyear WHERE username = '".constant("USERPROFILE")."' ORDER BY season DESC";
		foreach ($dbh->query($sql) as $row) {
			$num++;
			$season = $row['season'];
			$team = $row['team'];
			$score = $row['points'];
			$points = $row['total'];
			echo "<tr><td>".$season."</td><td>".$team."</td><td>".$score."</td><td>".$points."</td></tr>\n";
		}
		echo "</table>\n\n";


		// Recent results
		echo "<h2>Recent Results</h2>";
		echo "<table>\n";
		echo "<thead><tr><th>Race</th><th>Position</th><th>Points</th></tr></thead>\n";
		$sql = "SELECT grand_prix_name, fantasy_race_position, fantasy_race_points FROM view_ff1results WHERE username = '".constant("USERPROFILE")."' ORDER BY id DESC LIMIT 5";
		foreach ($dbh->query($sql) as $row) {
			$race = $row['grand_prix_name'];
			$position = $row['fantasy_race_position'];
			$score = $row['fantasy_race_points'];
			echo "<tr><td>".$race."</td><td>".$position."</td><td>".$score."</td></tr>\n";
		}
		echo "</table>\n\n";

	}
	else {
		header("HTTP/1.1 404 Not Found");
		include '404.php';
	}
			
}

else {

	echo "<h1>Profiles</h1>"; ?>
	<p>Do you want to know a bit more about everyone who plays FantasyF1? You've come to the right place. Look at the profiles here to check out who's hot, who's not, and get a little insight into who their favourite drivers to pick are!</p>
	<div class="profiles">
	<?php 
    $sql = "SELECT * FROM profile_stats ORDER BY username ASC";
    $query = $dbh->prepare($sql);
    $query->execute();
    if ($query->rowCount() > 0) {
      $result = $query->fetchAll(PDO::FETCH_OBJ);
      foreach ($result as $row) {
        echo "<div class=homepage-panel><h3><a href=".$home.$page.$row->username."/>".$row->username."</h3></a><p>Races: ".$row->races."<br>Wins: ".$row->wins."<br>Points: ".$row->points."</p></div>\n";
      }
      
    }
	/*$contents = scandir($page);
	foreach($contents as $key => $value) {
		if ($value != "index.php" && $value !="." && $value !="..") {
			$value = substr($value, 0, -4);
			echo "<div class=homepage-panel><h3><a href=".$home.$page.$value."/>".$value."</h3></a><p>Races: <br>Wins: <br>Points: </p></div>\n";
		}
	}*/
	echo "</div>\n";
}

?>