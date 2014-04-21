<?php

$sql = $dbh->prepare("SELECT COUNT(*) AS 'Wins' FROM `view_ff1results` WHERE fantasy_race_position = 1 AND username = :user");
$sql->execute(array(':user' => constant("USERPROFILE")));
$row = $sql->fetch(PDO::FETCH_ASSOC);
if ($sql->rowCount() == 1) {
    $wins = $row['Wins'];
    echo '<div class="center trophy">';
    echo "<h2>".$wins."</h2>\n";
    echo "<p>Wins</p>\n";
    echo "</div>\n";
}

?>