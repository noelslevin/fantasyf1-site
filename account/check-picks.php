<?php

session_start();
$error = NULL;
$output = NULL;

if (isset($_SESSION['user_id'])) {
	$title = "All FantasyF1 Picks";
	include('includes/header.php');
	include('../../private/connection.php'); ?>
	
	<div class=row>
	   <div class="small-12 columns">
	   <h1>Check FantasyF1 picks</h1>
	   <p>All picks made by all users for the current season are available to view here once picks have been closed for the each race. Please use the search box below to filter results. For example, to search Noelinho's picks for Bahrain, type in "Noelinho Bahrain".</p>
        <p>All columns are searchable and orderable.</p>
    <?php
    $message = NULL;
    $query = "SELECT username, forename, surname, team_name, grand_prix_name, race_points FROM `view_ff1allfantasypicks` WHERE status > 3 ORDER BY races_id DESC, fantasy_value DESC";
    $result = mysql_query($query);
    $num = mysql_num_rows($result);
        if ($num > 0) {
        echo "<table id=\"ff1picks\" class=\"display\">
        <thead>
        <tr>
        <th>Username</th>
        <th>Driver</th>
        <th>Team</th>
        <th>Grand Prix</th>
        <th>Points</th>
        </tr>
        </thead>
        <tbody>\n";
        while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
            $username = $row['username'];
            $driver = $row['forename']." ".$row['surname'];
            $team = $row['team_name'];
            $gp = $row['grand_prix_name'];
            $points = $row['race_points'];

            echo "<tr>
            <td>".$username."</td>
            <td>".$driver."</td>
            <td>".$team."</td>
            <td>".$gp."</td>
            <td>".$points."</td>
            </tr>
            ";
        }
        echo "</tbody>
        </table>\n";
    }
    else {
        $message .= "<p>Error: No data found. Looks like the deadline for picks for the first race hasn't passed yet.</p>";
    }
    
    echo $message;
    echo "</div>\n</div>\n";
	include('includes/footer.php');
}
else {
	header ("Location:  http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
	exit(); // Quit the script.
}

?>