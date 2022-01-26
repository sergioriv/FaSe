<?php require 'config-lobby.php';

$id = $_POST['id'];
//$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];

$con->query("UPDATE bancos SET bnc_nombre='$nombre' WHERE IDBanco = '$id'");

header("location:borrar.php");

?>