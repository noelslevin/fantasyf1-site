<script src="/assets/chart.min.js"></script>
<script>
	var canvaswidth = (document.getElementById("mainwidth").offsetWidth) - 40;
	if (canvaswidth > 600) {
		canvaswidth = 600;
	}
	var canvasheight = canvaswidth * 0.75;
</script>

<h1>Charts</h1>
<p>Test page for FantasyF1 charts. You did well to find this. We'll keep it as our little secret.</p>
<?php

$num = NULL;
$drivers = array();
$picks = array();

$sql = $dbh->prepare("SELECT CONCAT(forename, ' ', surname) as Driver, surname, COUNT(*) AS `picks` FROM `view_ff1allfantasypicks` WHERE status > '3' GROUP BY Driver ORDER BY `picks` DESC LIMIT 5");
$sql->execute();
$row = $sql->fetchAll(PDO::FETCH_ASSOC);
if ($sql->rowCount() > 0) {
	$num = 0;
	echo "<p>Data.</p>"; ?>
	<!-- echo "<canvas id=canvas height=<script>document.write(canvasheight);</script> width=400></canvas>";-->
	<script>
	document.write('<canvas id=canvas height='+canvasheight+' width='+canvaswidth+'></canvas>');
	</script>
	<?php
	foreach ($row as $result) {
		$num++;
		$drivers[$num] = $result['surname'];
		$picks[$num] = $result['picks'];
	}
}
else {
	echo "<p>Error: no data.</p>";
}
?>

<script>
	var barChartData = {
		<?php echo 'labels: ["'.$drivers[1].'","'.$drivers[2].'","'.$drivers[3].'","'.$drivers[4].'","'.$drivers[5].'"],'; ?>
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				<?php echo "data: [".$picks[1].",".$picks[2].",".$picks[3].",".$picks[4].",".$picks[5]."]"; ?>
			}
		],
	}
	var barChartOptions = {
		scaleOverride: true,
		scaleSteps: 10,
		scaleStepWidth: <?php echo ceil($picks[1]/10); ?>,
		scaleStartValue: 0
	}
	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData,barChartOptions);
</script>