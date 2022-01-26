<?php include'config-lobby.php';

$id = $_POST['id'];
$medicamento = $_POST['medicamento'];
$presentacion = $_POST['presentacion'];

$con->query("UPDATE vadecum SET vd_medicamento = '$medicamento',  vd_presentacion = '$presentacion' WHERE IDVadecum = '$id'");

header("location:vademecum");

?>