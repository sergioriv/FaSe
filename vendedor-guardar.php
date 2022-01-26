<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO vendedores SET vn_idClinica='$sessionClinica', vn_nombre='$nombre', vn_telefono='$telefono', vn_estado='1', vn_fechaCreacion='$fechaHoy'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE vendedores SET vn_nombre='$nombre', vn_telefono='$telefono' WHERE IDVendedor='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>