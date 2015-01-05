<?php

$profiles = NULL;

$sql = "SELECT username FROM profile_stats";
$query = $dbh->prepare($sql);
$query->execute();
if ($query->rowCount() > 0) {
  $result = $query->fetchAll(PDO::FETCH_OBJ);
  foreach ($result as $row) {
    $update = "UPDATE profile_stats SET races = (SELECT COUNT(*) FROM `view_ff1results` WHERE username = '$row->username'), wins = (SELECT COUNT(fantasy_race_position) FROM `view_ff1results` WHERE fantasy_race_position = 1 AND username = '$row->username'), points = (SELECT SUM(Points) FROM `view_ff1individualstandingsbyyear` WHERE username = '$row->username') WHERE username = '$row->username'";
    $updatequery = $dbh->prepare($update);
    $updatequery->execute();
  }
}
else {
  echo "<p>No data found.</p>";
}

?>