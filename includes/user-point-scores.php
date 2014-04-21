<?php

$sql = $dbh->prepare("SELECT COUNT(*) AS 'Scores' FROM `view_ff1results` WHERE fantasy_race_position < 11 AND username = :user");
$sql->execute(array(':user' => constant("USERPROFILE")));
$row = $sql->fetch(PDO::FETCH_ASSOC);
if ($sql->rowCount() == 1) {
    $scores = $row['Scores'];
    echo '<div class="center trophy">';
    echo "<h2>".$scores."</h2>\n";
    echo "<p>Scores</p>\n";
    echo "</div>\n";
}

?>