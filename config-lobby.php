<?php
session_start();
error_reporting(0);
//date_default_timezone_set('America/Bogota');

$dbhost = '34.136.39.29';
$database='fase_db';
$user='MantizTechnology';
$password='Mantiz$Tech-2020';
 
// Conectarse a la base de datos
if(empty($con)){
	$con = new mysqli($dbhost,$user,$password,$database);
}
// if ($con->connect_errno) { include'smtp.php';

// 	echo "<b>Error inesperado:</b> (" . $con->connect_errno . ")<br> Intentalo más tarde.";	

// 	$error_descripcion = 'Error inesperado en la plataforma ('. $con->connect_errno .') ' . $con->connect_error;

// 	//Set who the message is to be sent to
// 	$mail->AddBCC('fabiojara@gmail.com');
// 	$mail->AddBCC('fabiojara@live.com');
// 	$mail->AddBCC('sergioa.rivcif@gmail.com');
// 	$mail->AddBCC('sergioa_rivcif@hotmail.es');
// 	$mail->Subject = 'Error inesperado | FaSe';
// 	$mail->msgHTML(utf8_decode($error_descripcion));
// 	$mail->send();
// }

$hoyDia = date("d");
$hoyMes = date("m");
$hoyAno = date("Y");
$hoyHora = date("H");
$hoyMin = date("i");

$fechaHoy = $hoyAno.'/'.$hoyMes.'/'.$hoyDia.' '.$hoyHora.':'.$hoyMin;
$fechaHoySinEsp = $hoyAno.$hoyMes.$hoyDia;

//$ruta = 'https://mantiztechnology.com/demo/FaSe/';
$ruta = __DIR__;
?>