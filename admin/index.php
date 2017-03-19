<style>
a {
	display: block;
	margin-bottom: 5px;
	font-size: 20px;
}

.block {
	display: table-cell;
	width: 20%;
}

</style>

<h1>FantasyF1 Admin</h1>

<div class=block>
<h2>F1 General</h2>
<a href=f1-general/index.php?page=drivers>Drivers</a>
<a href=f1-general/index.php?page=teams>Teams</a>
<a href=f1-general/index.php?page=teamstoseasons>Teams to Seasons</a>
<a href=f1-general/index.php?page=driverstoseasons>Drivers to Seasons</a>
<a href=f1-general/index.php?page=grandsprix>Grands Prix</a>
<a href=f1-general/index.php?page=tracks>Tracks</a>
<a href=f1-general/index.php?page=trackstograndsprix>Tracks to Grands Prix</a>
<a href=f1-general/index.php?page=races>Races</a>
</div>

<div class=block>
<h2>FF1 General</h2>
<a href=ff1-general/index.php?page=ff1users>FF1 Users</a>
<a href=ff1-general/index.php?page=ff1teams>FF1 Teams</a>
<a href=ff1-general/index.php?page=ff1teamstoseasons>FF1 Teams to Seasons</a>
<a href=ff1-general/index.php?page=ff1userstoseasons>FF1 Users to Seasons</a>
<a href=ff1-general/index.php?page=feedback>User feedback</a>
</div>

<div class=block>
<h2>Race Data</h2>
<a href=race-data/index.php?page=racestatus>Race Status</a>
<a href=race-data/index.php?page=raceentries>Race Entries</a>
<a href=race-data/index.php?page=drivervalues>Driver Values</a>
<a href=race-data/index.php?page=fantasypicks>Fantasy Picks</a>
<a href=race-data/index.php?page=raceresults>Race Results</a>
<a href=race-data/index.php?page=ff1results>FF1 Results</a>
<a href=ff1-race-results.php>FF1 Results Tables</a>
</div>

<?php include '../../private/connection.php'; ?>
<?php //echo constant("BASE_PATH"); ?>