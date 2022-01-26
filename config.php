<?php
session_start();

if(!$_SESSION['concultoriosUsuario']){
	?>
		<script type="text/javascript">setTimeout("location.href = 'cerrar-sesion'");</script>
	<?php
} else { 
	$_SESSION['concultoriosClinica'] = $_SESSION['concultoriosClinica'];
	$_SESSION['concultoriosUsuario'] = $_SESSION['concultoriosUsuario'];
	$_SESSION['concultoriosIDUsuario'] = $_SESSION['concultoriosIDUsuario'];
	$_SESSION['concultoriosRol'] = $_SESSION['concultoriosRol'];	 
}
if(!$_SERVER['HTTP_REFERER']){
	?>
		<script type="text/javascript">setTimeout("location.href = 'cerrar-sesion'");</script>
	<?php
}

error_reporting(0);
date_default_timezone_set('America/Bogota');

$dbhost = '34.136.39.29';
$database='fase_db';
$user='MantizTechnology';
$password='Mantiz$Tech-2020';
 
// Conectarse a la base de datos
if(empty($con)){
	$con = new mysqli($dbhost,$user,$password,$database);
}
if ($con->connect_errno) { include'smtp.php';

	echo "<b>Error inesperado:</b> (" . $con->connect_errno . ")<br> Intentalo más tarde.";	

	$error_descripcion = 'Error inesperado en la plataforma ('. $con->connect_errno .') ' . $con->connect_error;

	//Set who the message is to be sent to
	$mail->AddBCC('fabiojara@gmail.com');
	$mail->AddBCC('fabiojara@live.com');
	$mail->AddBCC('sergioa.rivcif@gmail.com');
	$mail->AddBCC('sergioa_rivcif@hotmail.es');
	$mail->Subject = 'Error inesperado | FaSe';
	$mail->msgHTML(utf8_decode($error_descripcion));
	$mail->send();
}

$_SESSION['concultoriosAntes'] = $_SERVER['HTTP_REFERER'];
$_SESSION['concultoriosDosAntes'] = $_SESSION['concultoriosAntes'];

$hoyDia = date("d");
$hoyMes = date("m");
$hoyAno = date("Y");
$hoyHora = date("H");
$hoyMin = date("i");

$fechaEvolucionCita = $hoyAno.$hoyMes.$hoyDia.$hoyHora.$hoyMin;
$fechaHoy = $hoyAno.'/'.$hoyMes.'/'.$hoyDia.' '.$hoyHora.':'.$hoyMin;
$fechaHoySinEsp = $hoyAno.$hoyMes.$hoyDia;
$fechaMesInicio = $hoyAno.$hoyMes.'01';

$ruta = 'https://softwaredental.online/demo/';

// CLINICAS
$sessionClinica = $_SESSION['concultoriosClinica'];
$sessionUsuario = $_SESSION['concultoriosUsuario'];
$sessionIDUsuario = $_SESSION['concultoriosIDUsuario'];
$sessionRol = $_SESSION['concultoriosRol'];

$clinicaSql = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$sessionClinica'");
$clinicaRow = $clinicaSql->fetch_assoc();

	$colorPrincipal = 'rgb(0,160,227)';
	$colorSecundario = 'rgb(176,203,31)';

	$iconoNuevo = '<i class="fa fa-plus" aria-hidden="true"></i>';
	$iconoEditar = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>';
	$iconoEliminar = '<i class="fa fa-trash-o" aria-hidden="true"></i>';
	$iconW = '<i class="fa fa-exclamation-triangle iconW"></i>';

	$numeroResultados = 10;
?>