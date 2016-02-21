<?php

$sql = $dbh->prepare("SELECT registered FROM fantasyusers WHERE fantasyusers.id = :fantasyuser");
$sql->execute(array(':fantasyuser' => $_SESSION['user_id']));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$totalrows = count($row);
if ($sql->rowCount() == 1) {
	$status = $row['status'];
	if ($status != 'A') {
		echo "<div class=panel>";
		echo "<p>You must agree to the site terms below to continue.</p>\n";
		echo "<ol>\n";
		echo "<li>By creating a FantasyF1 account, you are entitled to participate in the FantasyF1 competition. In order to administer the competition, you agree to the storing of your email address, which is linked to your username.</li>\n";
		echo "<li>You agree the site owner may use your email address to contact you about the FantasyF1 competition, including (but not limited to) information about race results, picks deadlines and site developments. Your email address will not be passed on to any third party.</li>\n";
		echo "<li>If you wish not to participate in future seasons, you will be given the opportunity to opt out and have your email address removed, but your public user data (e.g. race and season results) will still be available on the site.</li>\n";
		echo "<li>Whilst every reasonable effort is made to make the site secure, it is always possible for malicious attacks to steal data. On this site, that is limited to your username, email address and password. Passwords on this site are salted and hashed using current and widely used standards. Your connection to this site is also encrypted. Therefore, your details are as safe as they can reasonably be. However, it is still strongly advised that you use a unique password for this site. In agreeing to these terms, you acknowledge this is solely your responsibility, and if you login details are compromised and used on other sites, the site owner is not responsible.</li>\n";
		echo "<li>(Really important) You promise not to take this competition too seriously and let the site author win occasionally. </li>\n";
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