<?php
$home = "/";
$page = NULL;
if ($_SERVER['PHP_SELF'] == "/account/check-picks.php") {
    $page = "check-picks";
}
?>
<!doctype html>
<html class="no-js" lang=en>
<head>
<meta charset=UTF-8>
<meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
<title>Your Account | FantasyF1 | Noelinho.org</title>
<link rel="apple-touch-icon" href="/assets/img/icon.png">
<link rel="stylesheet" href="/assets/css/main.min.css" />
<meta name=description content="The (not quite) world-famous Noelinho.org FantasyF1 game">
</head>
<body>

<div class="contain-to-grid fixed">
	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name"></li>
			<li class="toggle-topbar menu-icon"><a href=""><span>Menu</span></a></li>
		</ul>
		<section class="top-bar-section">
			<ul class="left">
				<li><a href=<?php echo $home; ?>>Main site</a></li>
				<li><a href=<?php echo $home; ?>account/>Your profile</a></li>
				<li><a href=<?php echo $home; ?>account/picks.php>Make picks</a></li>
        <li><a href=<?php echo $home; ?>account/check-picks.php>Check picks</a></li>
        <li><a href=<?php echo $home; ?>account/feedback.php>Feedback</a></li>
				<li><a href=<?php echo $home; ?>account/logout.php>Log Out</a></li>
			</ul>
		</section>
	</nav>
</div>

<header id=top-header>
	<div class=row>
		<div class="small-12 columns">
            <div id="race-car"></div>
		</div>
	</div>
</header>
