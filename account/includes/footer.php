<div id=footer>
	<div class=row>
		<div class="small-12 columns">
			<footer>
			<p>© Noel Slevin, 2011 – <?php echo date("Y"); ?>. All rights reserved.</p>
			</footer>
		</div>
	</div>
</div>
<script src="/assets/js/main.min.js"></script>
<?php
if ($page == "check-picks") {
        echo "<script src=\"/assets/js/jquery.dataTables.min.js\"></script>\n";
}
?>
<script>
  $(document).foundation();
</script>
<?php 
    if ($page == "check-picks") {
        echo "<script> $(document).ready(function() { table = $('#ff1picks').DataTable({ \"info\": false, \"pageLength\": 25, \"lengthChange\": false, pagingType: \"full\" }); } ); </script>\n";
    }
?>
</body>
</html>
