<?php
$home = "/";
?>
<!doctype html>
<html class="no-js" lang=en>
<head>
<meta charset=UTF-8>
<meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
<title>Your Account | FantasyF1 | Noelinho.org</title>
<link rel="stylesheet" href="/assets/foundation.min.css" />
<link rel="stylesheet" href="/assets/normalize.css" />
<link rel="stylesheet" href="/assets/style.css" />
<script src="/assets/modernizr.js"></script>
<meta name=description content="The (not quite) world-famous Noelinho.org FantasyF1 game">
</head>
<body>

<div class=contain-to-grid fixed>
	<nav class=top-bar data-topbar>
		<ul class=title-area>
			<li class=name></li>
			<li class="toggle-topbar menu-icon"><a href="">Menu</a></li>
		</ul>
		<section class=top-bar-section>
			<ul class=left>
				<li><a href=<?php echo $home; ?>>Main site</a></li>
				<li><a href=<?php echo $home; ?>account/>Your profile</a></li>
				<li><a href=<?php echo $home; ?>account/picks.php>Picks</a></li>
				<li><a href=<?php echo $home; ?>account/logout.php>Log Out</a></li>
			</ul>
		</section>
	</nav>
</div>

<header id=top-header>
	<div class=row>
		<div class="small-12 columns">
			<img class=race-car src=<?php echo $home; ?>assets/race-car.svg height=140 alt="Race Car" />
		</div>
	</div>
</header>