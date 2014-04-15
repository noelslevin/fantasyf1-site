 <?php
 
 if (isset($_POST['ff1picks'])) {
	$raceid = $_POST['raceid'];
	$option = "active_race";
	$sql = $dbh->prepare("UPDATE races SET status = 4 WHERE id =?");
	$sql->execute(array($raceid));
	
	if ($sql->rowCount() == 1) {
		echo "<p>Active race updated.</p>";
	}
	else {
		echo "<p>Active race not updated.</p>";
	}
 }
 
 else {
 
	 $sql = $dbh->prepare("SELECT races_id, race_date, grand_prix_name, status FROM view_ff1allraces WHERE status = 3 ORDER BY race_date ASC");
	 $sql->execute();
	 $row = $sql->fetchAll(PDO::FETCH_ASSOC);
	 if ($sql->rowCount() > 0) {
		echo "<form name=ff1picks action=\"".$_SERVER['PHP_SELF']."?page=racestatus\" method=post>\n";
		echo "<select name='raceid'>\n";
		echo "<option value=0>Unset</option>\n";
		foreach ($row as $key=>$dataheader) {
			echo "<option value=".$dataheader['races_id'].">".$dataheader['grand_prix_name']." (".$dataheader['race_date'].")</option>\n";
		}
		echo "</select>\n";
		echo "<input type=submit name=ff1picks value=\"Close picks\">\n";
		echo "</form>\n";
	 }
	 else {
		echo "<p>Error: no active races found.</p>";
	 }
 
 }
 
 ?>