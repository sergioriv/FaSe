<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO convenios SET cnv_idClinica='$sessionClinica', cnv_idUsuario='$sessionIDUsuario', cnv_nombre='$nombre', cnv_descuento='$valor', cnv_estado='1', cnv_fechaCreacion='$fechaHoy'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE convenios SET cnv_nombre='$nombre', cnv_descuento='$valor' WHERE IDConvenio='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>