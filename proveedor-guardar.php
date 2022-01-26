<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO proveedores SET pr_idClinica='$sessionClinica', pr_nombre='$nombre', pr_nit='$nit', pr_telefonoFijo='$telefono', pr_idCiudad='$ciudad', pr_direccion='$direccion', pr_correo='$correo', pr_estado='1', pr_fechaCreacion='$fechaHoy'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE proveedores SET pr_nombre='$nombre', pr_nit='$nit', pr_telefonoFijo='$telefono', pr_idCiudad='$ciudad', pr_direccion='$direccion', pr_correo='$correo' WHERE IDProveedor='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>