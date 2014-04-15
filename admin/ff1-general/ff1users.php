<?php

if(isset($_POST['fantasyusersupdate'])) {
	$userid = $_POST['user_id'];
	$query = "UPDATE fantasyusers SET registered = 1 WHERE id = '$userid'";
	$result = mysql_query($query);
	if ($result) {
		$message .= "<p>The user was successfully updated.</p>";
	}
	else {
		$message .= "<p>Error: the user was not updated.</p>";
	}
}

echo "<h2>Re-Register Fantasy Users For Current Season</h2>";
echo "<form action =\"".$_SERVER['PHP_SELF']."?page=ff1users\" method=\"post\">\n\n";
$query = "SELECT fantasyusers.id, fantasyusers.username FROM fantasyusers WHERE registered = 0 ORDER BY username ASC";
$result = mysql_query($query);
$num = mysql_num_rows($result);
if ($num > 0) {
	echo "<select name = \"user_id\">\n";
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$name = $row['username'];
		echo "<option value=\"".$id."\">".$name."</option>\n";
	}
	echo "</select>\n\n";
}

else {
	echo "<p>No unregistered users found.<p>";
}
echo "<input type=\"submit\" value=\"Update User\" name=\"fantasyusersupdate\" />\n\n";
echo "</form>\n\n";

echo $message;

?>