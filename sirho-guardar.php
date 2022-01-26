<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

	$query = $con->query("INSERT INTO sirhoclinica SET shcl_idClinica='$sessionClinica', shcl_idSirho='$sirho', shcl_cantidad='$cantidad', shcl_estado='1', shcl_fechaCreacion='$fechaHoy'");

if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }


unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>