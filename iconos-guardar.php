<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

	$query = $con->query("UPDATE clinicas SET cl_iconoNuevo='$Nuevo', cl_iconoEditar='$Editar', cl_iconoEliminar='$Eliminar' WHERE IDClinica='$sessionClinica'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>