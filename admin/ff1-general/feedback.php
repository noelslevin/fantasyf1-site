<h2>User feedback</h2>

<style>
  table, th, td {
    border: 1px solid #000;
    border-collapse: collapse;
  }
  th, td {
    padding: 5px;
  }
</style>

<?php

$sql = "SELECT * FROM fantasyusers WHERE registered = '1'";
$query = $dbh->prepare($sql);
$query->execute();
if ($query->rowCount() > 0) {
  $result = $query->fetchAll(PDO::FETCH_OBJ);
  echo "<table>\n";
  echo "<thead><tr><th>Name</th><th>Bugs</th><th>Features</th><th>Preferences</th></tr></thead>\n";
  foreach ($result as $row) {
    echo "<tr><td>".$row->username."</td><td>".$row->bugs."</td><td>".$row->features."</td><td>".$row->preferences."</td></tr>\n";
  }
  echo "<tbody>\n";
  echo "</tbody></table>\n";
}
else {
  echo "<p>No data found.</p>";
}

?>