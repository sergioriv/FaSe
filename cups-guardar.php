<?php include'config-lobby.php';

$id = $_POST['id'];
$cup = $_POST['cup'];

$con->query("UPDATE cups SET cup_nombre = '$cup' WHERE IDCups = '$id'");

header("location:cups.php");

?>