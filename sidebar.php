<?php include '../private/connection.php'; ?>
<h3>Formula One Standings</h3>
<dl class=tabs data-tab>
	<dd class="active"><a href="#panel2-1">Driver</a></dd>
	<dd><a href="#panel2-2">Team</a></dd>
</dl>
<div class="tabs-content">
	<div class="content active" id="panel2-1">
		<table>
			<thead>
				<tr><th>Driver</th><th>Team</th><th>Points</th></tr>
			</thead>
			<tbody>
				<?php
				$sql = $dbh->prepare("SELECT forename, surname, teamname, SUM(race_points) AS points FROM `view_ff1allraceresults` WHERE Year = 2016 GROUP BY driverstoseasons_id ORDER BY points DESC LIMIT 5");
				$sql->execute();
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				$totalrows = count($row);
				if ($sql->rowCount() > 0) {
					foreach ($row as $result) {
						$driver = $result['forename']." ".$result['surname'];
						$team = $result['teamname'];
						$points = $result['points'];
						echo "<tr><td>".$driver."</td><td>".$team."</td><td>".$points."</td></tr>";
						}
					}
				?>
			</tbody>
		</table>
	</div>

	<div class="content" id="panel2-2">
		<table>
			<thead>
				<tr><th>Team</th><th>Engine</th><th>Points</th></tr>
			</thead>
			<tbody>
				<?php
				$sql = $dbh->prepare("SELECT teamname, engine, SUM(race_points) AS points FROM `view_ff1allraceresults` WHERE Year = 2016 GROUP BY teamstoseasons_id ORDER BY points DESC Limit 5");
				$sql->execute();
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				$totalrows = count($row);
				if ($sql->rowCount() > 0) {
					foreach ($row as $result) {
						$team = $result['teamname'];
						$engine = $result['engine'];
						$points = $result['points'];
						echo "<tr><td>".$team."</td><td>".$engine."</td><td>".$points."</td></tr>";
						}
					}
				?>
			</tbody>
		</table>
	</div>
</div>

<h3>FantasyF1 Standings</h3>
<dl class=tabs data-tab>
	<dd class="active"><a href="#panel2-3">Individual</a></dd>
	<dd><a href="#panel2-4">Team</a></dd>
</dl>
<div class="tabs-content">
	<div class="content active" id="panel2-3">
		<table>
			<thead>
				<tr><th>User</th><th>Team</th><th>Points</th></tr>
			</thead>
			<tbody>
				<?php
				$sql = $dbh->prepare("SELECT username, team, points FROM `view_ff1individualstandingsbyyear` WHERE season = 2016 ORDER BY points DESC LIMIT 5");
				$sql->execute();
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				$totalrows = count($row);
				if ($sql->rowCount() > 0) {
					foreach ($row as $result) {
						$user = $result['username'];
						$team = $result['team'];
						$points = $result['points'];
						echo "<tr><td>".$user."</td><td>".$team."</td><td>".$points."</td></tr>";
						}
					}
				?>
			</tbody>
		</table>
	</div>

	<div class="content" id="panel2-4">
		<table>
			<thead>
				<tr><th>Team</th><th>Points</th></tr>
			</thead>
			<tbody>
				<?php
				$sql = $dbh->prepare("SELECT team, points FROM `view_ff1teamstandingsbyyear` WHERE season = 2016 ORDER BY points DESC LIMIt 5");
				$sql->execute();
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				$totalrows = count($row);
				if ($sql->rowCount() > 0) {
					foreach ($row as $result) {
						$team = $result['team'];
						$points = $result['points'];
						echo "<tr><td>".$team."</td><td>".$points."</td></tr>";
						}
					}
				?>
			</tbody>
		</table>
	</div>
	
</div>
