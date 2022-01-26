<?php include'config-lobby.php';


$compleanosHoy = new DateTime($fechaHoy);

$edadSql = $con->query("SELECT * FROM pacientes");
while($edadRow = $edadSql->fetch_assoc()){
	$cumpleanos = new DateTime($edadRow['pc_fechaNacimiento']);
	$diffCumpleanos = $compleanosHoy->diff($cumpleanos);
	$edad = $diffCumpleanos->y;
	$con->query("UPDATE pacientes SET pc_edad='$edad' WHERE IDPaciente = '$edadRow[IDPaciente]'");
}
?>