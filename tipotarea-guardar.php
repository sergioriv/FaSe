<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];

if(!$_POST['id']){
	$query = $con->query("INSERT INTO tipotarea SET tpt_idClinica='$sessionClinica', tpt_nombre='$nombre'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE tipotarea SET tpt_nombre='$nombre' WHERE IDTipoTarea='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}

header("Location:$_SESSION[concultoriosAntes]");
?>