<?php

if (!defined('IN_PAGE')) {
    header("HTTP/1.1 404 Not Found");
	include '../404.php';
	die();
}

else {
	echo "<div class=row>\n";
	echo "<div class=\"small-12 columns\">\n";
	echo "<p>You are now logged in.</p>";
	echo "</div>\n</div>\n";
}

?>