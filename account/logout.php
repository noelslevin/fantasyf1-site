<?php 

session_start();
session_destroy();

?>

<script>
alert("You are now logged out.");
window.location="../";
</script>
