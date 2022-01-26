<?php include'config.php';
$id = $_POST['id'];

$consentimientoList = $con->query("SELECT mct_contenido FROM mis_concentimientos WHERE IDMiConcentimiento = '$id' ")->fetch_assoc();

echo $consentimientoList['mct_contenido'];
?>