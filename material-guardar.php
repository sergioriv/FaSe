<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO materiales SET mt_idUsuario='$sessionUsuario', mt_idSucursal='$sucursal', mt_codigo='$codigo', mt_nombre='$nombre', mt_marca='$marca', mt_presentacion='$presentacion', mt_riesgo='$riesgo', mt_temperatura='$temperatura', mt_vidaUtil='$vidautil', mt_vencimiento='$vencimiento', mt_estado='1', mt_fechaCreacion='$fechaHoy'");

	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE materiales SET mt_idSucursal='$sucursal', mt_codigo='$codigo', mt_nombre='$nombre', mt_marca='$marca', mt_presentacion='$presentacion', mt_riesgo='$riesgo', mt_temperatura='$temperatura', mt_vidaUtil='$vidautil' WHERE IDMaterial='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>