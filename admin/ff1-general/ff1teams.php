<?php

if (isset($_POST['createfantasyteam'])) {
	$teamname = $_POST['teamname'];
	if (!empty($teamname)) {
		$query = "INSERT INTO fantasyteams (fantasyteam_name) VALUES ('$teamname')";
		$result = mysql_query($query);
		if ($result) {
			echo "<p>Success. Team name added to the database.</p>";
		}
		else {
			echo "<p>Error. Team name not added to the database.</p>";
		}
	}
	else {
		echo "<p>Error. Team name field was left empty.</p>";
	}
}

?>

<h2>Create A New Fantasy Team</h2>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=ff1teams" method="post">

<label for="teamname">Team Name: </label><input type="text" name="teamname" id="teamname" value="" />

<input type="submit" name="createfantasyteam" value="Create Fantasy Team" />
</form>