<?php 

if (strpos($filename,'/') !== false) {
	if (file_exists($filename)) {
		include '../private/connection.php';
		include $filename;
	}
else {
	header("HTTP/1.1 404 Not Found");
	include '404.php';
	}
}

else {
	echo "<h1>Standings</h1>"; ?>
	<p>You want to know who's winning? Of course you do, everyone does. Well, you're in the right place.</p>
	<ul class=profiles>
	<?php $contents = scandir($page,1);
	foreach($contents as $key => $value) {
		if ($value != "index.php" && $value !="." && $value !="..") {
			$value = substr($value, 0, -4);
			echo "<li class=profile-link><a href=".$home.$page.$value."/>".$value."</a></li>\n";
		}
	}
	echo "</ul>\n";
}

?>