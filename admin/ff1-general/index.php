<?php

include '../../../private/connection.php';
$page = NULL;
if (isset($_GET['page'])) {
	$page = $_GET['page'].".php";
	if (file_exists($page)) {
		include $page;
		echo "<a href=/admin/>Back to main admin page</a>";
	}
}

?>