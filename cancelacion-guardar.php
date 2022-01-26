<?php include'config-lobby.php';

$citaID = $_POST['citaID'];

$query = $con->query("UPDATE citas SET ct_estado = '2' WHERE IDCita = '$citaID'");

if($query){
	echo "La Cita a sido cancelada!";

	//ENVIAR CORREO DE CANCELACION A LA SUCURSAL
	//ENVIAR CORREO DE CANCELACION AL PACIENTE
} else {
	echo "Error, Inténtelo nuevamente.<br>Si el error persiste, Inténtalo más tarde.";
}
?>