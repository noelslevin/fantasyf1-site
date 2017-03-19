<?php

$sql = $dbh->prepare("SELECT status FROM fantasyusers WHERE fantasyusers.id = :fantasyuser");
$sql->execute(array(':fantasyuser' => $_SESSION['user_id']));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$totalrows = count($row);
if ($sql->rowCount() == 1) {
	$status = $row['status'];
	if ($status != 'A') {
		echo "<div class=panel>";
		echo "<p>You must agree to the site terms below to continue.</p>\n";
		echo "<ol>\n";
		echo "<li>By creating a FantasyF1 account, you are entitled to participate in the FantasyF1 competition. By participating in FantasyF1, you agree the site can store your email address, username and password.</li>\n";
		echo "<li>You agree the site owner may use your email address to contact you about FantasyF1, including (but not limited to) information about race results, picks deadlines and site developments. For this purpose, your email address may also be stored in Mailchimp.</li>\n";
		echo "<li>If you wish not to participate in future seasons, you will be given the opportunity to opt out and have your email address removed, but your public user data (e.g. race and season results) will still be available on the site.</li>\n";
		echo "<li>Whilst every effort is made to keep the site secure, including by using strongly encrypted connections, and passwords stored according to best practice principles, you acknowledge it is strongly recommended you use a password for your account which is different to those used on sensitive accounts, such as your personal email, banking, Tinder, etc.</li>\n";
		echo "<li>(Very important) You promise not to take this competition too seriously and let the site author win occasionally.</li>\n";
		echo "</ol>\n";
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=post>\n";
		echo "<input type=checkbox name=terms value=1 required/>I agree to the site terms.<br>\n";
		echo "<input type=submit name=agreetoterms id=agreetoterms value=\"Agree To Terms\" />\n";
		echo "</form>";
		echo "</div>";
	}
}
else {
	$error .= "<p>A serious error occurred. Something is broken.</p>";
}
?>