<?php

$sql = $dbh->prepare("SELECT registered FROM fantasyusers WHERE fantasyusers.id = :fantasyuser");
$sql->execute(array(':fantasyuser' => $_SESSION['user_id']));
$row = $sql->fetch(PDO::FETCH_ASSOC);
$totalrows = count($row);
if ($sql->rowCount() == 1) {
	$agreement = $row['registered'];
	if ($agreement != 1) {
		echo "<div class=panel>";
		echo "<p>You have not agreed to the site terms. You must agree to the site terms to continue.</p>";
		echo "<p>The site terms are as follows:</p>\n";
		echo "<ol>\n";
		echo "<li>By creating a FantasyF1 account, you are entitled to participate in the FantasyF1 competition. In order to administer the competition, you agree to the storing of your email address, which is linked to your username.</li>\n";
		echo "<li>You agree the site owner may use your email address to contact you about the FantasyF1 competition, including (but not limited to) information about race results, picks deadlines and site developments. Your email address will not be passed on to any third party.</li>\n";
		echo "<li>If you wish not to participate in future seasons, you will be given the opportunity to opt out and have your email address removed, but your public user data (e.g. race and season results) will still be available on the site.</li>\n";
		echo "<li>Whilst every reasonable effort is made to make the site secure, it is always possible for malicious attacks to steal data. On this site, that is limited to your email address and password. Passwords on this site are salted and hashed using strong and widely used standards. In the event that an attacker stole user login details, it would take a determined person to reverse engineer the passwords as stored, but it would not be impossible. However, the connection between your browser and this site is not encrypted, and therefore it would be possible for someone to intercept your password in this way (especially if you are on a monitored network, e.g. in a workplace). You agree that, in light of this, you are responsible for the security of your account, and understand that it is advised <strong>in the strongest possible terms</strong> that you should not use a password on this site which you use for another online service.</li>\n";
		echo "<li>You promise not to take this competition too seriously and let the site author win occasionally. </li>\n";
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