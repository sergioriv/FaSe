<?php include'config-lobby.php';

$id = $_POST['id'];
$rip = $_POST['rip'];

$con->query("UPDATE rips SET rip_nombre = '$rip' WHERE IDRips = '$id'");

header("location:rips");

?>