<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO tiposidentificacion SET ti_idClinica='$sessionClinica', ti_nombre='$nombre', ti_estado='1', ti_fechaCreacion='$fechaHoy'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE tiposidentificacion SET ti_nombre='$nombre' WHERE IDTipoIdentificacion='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>