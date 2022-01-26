<?php include'config.php'; 

$tarea = $_POST['tarea'];
$dateNuevo = date('Y/m/d');

$query = $con->query("UPDATE tareas SET tar_estado = 1, tar_completada = '$dateNuevo' WHERE IDTarea = '$tarea'");

?>