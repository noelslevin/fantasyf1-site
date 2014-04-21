<?php

$sql = $dbh->prepare("SELECT COUNT(*) AS 'Podiums' FROM `view_ff1results` WHERE fantasy_race_position < 4 AND username = :user");
$sql->execute(array(':user' => constant("USERPROFILE")));
$row = $sql->fetch(PDO::FETCH_ASSOC);
if ($sql->rowCount() == 1) {
    $podiums = $row['Podiums'];
    echo '<div class="center trophy">';
    echo "<h2>".$podiums."</h2>\n";
    echo "<p>Podiums</p>\n";
    echo "</div>\n";
}

?>