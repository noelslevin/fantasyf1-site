<?php
$home = "/";
if(isset($_GET['page'])) { $page = $_GET['page']; } else { $page= NULL; }
?>
<!doctype html>
<html class="no-js" lang=en>
  <head>
    <meta charset=utf-8>
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>FantasyF1 | Welcome</title>
     <link rel="apple-touch-icon" href="/assets/img/icon.png">
    <link rel="stylesheet" href="/assets/css/main.min.css" />
  </head>
  <body>
	<div class=contain-to-grid>
		<nav class=top-bar data-topbar>
			<ul class=title-area>
				<li class=name></li>
				<li class="toggle-topbar menu-icon"><a href="">Menu</a></li>
			</ul>
			<section class=top-bar-section>
				<ul class=left>
					<li><a href=<?php echo $home; ?>>Home</a></li>
					<li><a href=<?php echo $home; ?>about/>About</a></li>
					<li><a href=<?php echo $home; ?>rules/>Rules</a></li>
					<li><a href=<?php echo $home; ?>profiles/>Profiles</a></li>
					<li><a href=<?php echo $home; ?>results/>Results</a></li>
					<li><a href=<?php echo $home; ?>standings/>Standings</a></li>
					<li><a href=<?php echo $home; ?>statistics/>Statistics</a></li>
					<li><a href=<?php echo $home; ?>account/>Account</a></li>
				</ul>
			</section>
		</nav>
	</div>
	
	<header id=top-header>
		<div class=row>
			<div class="small-12 columns" id=race-car></div>
		</div>
	</header>
	
	<div class="row">
		<?php if ($page) {
			if (substr($page, -1) == "/") {
				$filename = substr($page, 0, -1); // Removes trailing slash if it's there
			}
			else {
				$filename = $page; // Nothing to remove
			}
			$root = explode("/", $filename); // Splits into array
			$filename = $filename.'.php'; // Adds file extension for loading file
			$rootfile = $root[0].'.php'; // Returns the part of the string before a trailing slash, adds file extension
			echo '<div class="small-12 large-7 columns" id=mainwidth>';
			if (file_exists($rootfile)) {
                if ($rootfile == '404.php') {
                  header("HTTP/1.1 404 Not Found");
                }
				include $rootfile;
				}
			else {
				header("HTTP/1.1 404 Not Found");
				include '404.php';
				}
				echo '</div>';
				echo '<div class="small-12 large-5 columns">';
				include 'sidebar.php';
				echo '</div>';
				}
			else {
				include 'homepage.php';
			}
		?>
	</div>

	<div id=footer>
		<div class=row>
			<div class="small-12 columns">
				<footer>
				<p>© Noel Slevin, 2011 – <?php echo date("Y"); ?>. All rights reserved.</p>
				</footer>
			</div>
		</div>
	</div>
    
	<script src="/assets/js/main.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
