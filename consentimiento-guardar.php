<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO mis_concentimientos SET mct_idClinica='$sessionClinica', mct_idUsuario='$sessionIDUsuario', mct_nombre='$nombre', mct_contenido='$contenido', mct_fechaCreacion='$fechaHoy'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE mis_concentimientos SET mct_nombre='$nombre', mct_contenido='$contenido' WHERE IDMiConcentimiento='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>