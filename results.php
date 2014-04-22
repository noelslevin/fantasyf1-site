<h1>Results</h1>

<?php

function listDirectory() {
	global $home;
	global $page;
	$output = NULL;
	$number = 0;
	$output .= "<ul class=profiles>\n";
	$contents = scandir($page,1);
	foreach($contents as $key => $value) {
		if ($value != "index.php" && $value !="." && $value !="..") {
			if (ctype_digit($value)) {
				$output .= "<li class=profile-link><a href=".$home.$page.$value."/>".$value."</a></li>\n";
				$number++;
			}
			else {
				$value = substr($value, 0, -5);
                $country = str_replace("-grand-prix","",$value);
                $pos = strpos($country, "-") + 1;
                $country = substr($country, $pos);
				$displayvalue = str_replace("-", " ", $value);
				$displayvalue = ucwords($displayvalue);
				$displayvalue = substr($displayvalue, 4);
				$output .= "<li class=\"profile-link ".$country."\"><a href=".$home.$page.$value."/>&nbsp;</a></li>\n";
				$number++;
			}
		}
	}
	$output .= "</ul>\n";
	if ($number > 0) {
		echo $output;
	}
	else {
		echo "<p>Error: no data found.</p>";
	}
}



if (preg_match("/results\/[0-9]{4}\/[a-z0-9-]/", $page)) {
	$page = substr($page, 0, -1).".html";
	if (file_exists($page)) {
		include $page;
	}
	else {
		header("HTTP/1.0 404 Not Found");
		include '404.php';
	}
	
}

else if (preg_match("/results\/[0-9]{4}\//", $page)) {
	if (is_dir($page)) {
		listDirectory();
	}
	else {
		header("HTTP/1.0 404 Not Found");
		include '404.php';
	}
}

else if ($page == "results/") {
	listDirectory();
}

else {
	header("HTTP/1.0 404 Not Found");
	include '404.php';
}

?>