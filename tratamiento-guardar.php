<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

$precio = str_replace(',', '', $precio);

if(!$_POST['id']){
	$query = $con->query("INSERT INTO tratamientos SET tr_idClinica='$sessionClinica', tr_idCups='$cups', tr_idFase='$fase', tr_nombre='$nombre', tr_precio='$precio', tr_estado='1', tr_fechaCreacion='$fechaHoy'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE tratamientos SET tr_idCups='$cups', tr_idFase='$fase', tr_nombre='$nombre', tr_precio='$precio' WHERE IDTratamiento='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>