<?php

if (strpos($filename,'/') !== false) {
	if (file_exists($filename)) {
		include '../private/connection.php';
		include $filename;
	}
else {
	header("HTTP/1.0 404 Not Found");
	include '404.php';
	}
}
else {
	echo "<h1>Statistics</h1>";
	echo "<p>There aren't any statistics available at the moment. Sorry.</p>";
}

/*

// SQL

// Most ff1 championship points
SELECT username, SUM(points) as points
FROM view_ff1individualstandingsbyyear
GROUP BY username
ORDER BY points DESC
LIMIT 5



// Most ff1 overall points 
SELECT username, SUM(total) as total
FROM view_ff1individualstandingsbyyear
GROUP BY username
ORDER BY points DESC
LIMIT 5



// Most F1 points since 2011 
SELECT CONCAT(forename, ' ', surname) AS Driver, SUM(race_points) AS Points FROM `view_ff1allraceresults`
GROUP BY driver
ORDER BY points DESC
LIMIT 5



// Most-picked drivers 
SELECT CONCAT(forename, ' ', surname) as driver, COUNT(CONCAT(forename, ' ', surname)) as picks FROM view_ff1allfantasypicks
GROUP BY driver
ORDER BY picks DESC
*/ ?>