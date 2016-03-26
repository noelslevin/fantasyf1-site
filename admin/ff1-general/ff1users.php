<?php

$message = NULL;

if(isset($_POST['fantasyusersupdate'])) {
	$userid = $_POST['user_id'];
	$sql = $dbh->prepare("UPDATE fantasyusers SET status = 'A' WHERE id = :userid");
  $sql->execute(array(':userid' => $userid));
  $rows = $sql->rowCount();
	if ($rows == 1) {
		$message .= "<p>The user was successfully updated.</p>";
	}
	else {
		$message .= "<p>Error: $rows rows updated.</p>";
	}
}

echo "<h2>Re-Register Fantasy Users For Current Season</h2>";
echo "<form action =\"".$_SERVER['PHP_SELF']."?page=ff1users\" method=\"post\">\n\n";
$sql = $dbh->prepare("SELECT fantasyusers.id, fantasyusers.username FROM fantasyusers WHERE status != 'A' ORDER BY username ASC");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_OBJ);
if ($sql->rowCount() > 0) {
	echo "<select name = \"user_id\">\n";
	foreach ($row as $result) {
		echo "<option value=\"".$result->id."\">".$result->username."</option>\n";
	}
	echo "</select>\n\n";
}

else {
	echo "<p>No unregistered users found.<p>";
}
echo "<input type=\"submit\" value=\"Update User\" name=\"fantasyusersupdate\" />\n\n";
echo "</form>\n\n";

if (isset($message)) {
  echo $message;
}

?>