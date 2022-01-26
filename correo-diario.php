<?php include'config-lobby.php'; include'smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$sucursalesSQL = $con->query("SELECT * FROM sucursales WHERE sc_estado=1 AND sc_enviarCorreo=1 AND sc_correo!=''");
while($sucursales = $sucursalesSQL->fetch_assoc()){
	
	$destino = $sucursales['sc_correo'];
	if (filter_var($destino, FILTER_VALIDATE_EMAIL)) {

		$clinica = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$sucursales[sc_idClinica]'")->fetch_assoc();

		if($clinica['cl_logo']!=""){ $titulo = "<img style='max-width: 150px; max-height: 60px;' src='$ruta$clinica[cl_logo]'>"; }
		else { $titulo = strtoupper($clinica['cl_nombre']); }

	

$html="
			<style type='text/css'>
				span.im {
					color: black;
				}
			</style>
			<table border='0' style='border-collapse: collapse; width: 100%; font-family: Helvetica, Arial, sans-serif; color: black; font-size: 15px;'>
			  <tr style='height: 100px; border-bottom: 1px solid ".$colorPrincipal.";'>
				<td colspan='4' style='padding: 10px; font-size: 20px; text-align: center;'>".$titulo."</td>
			  </tr>
			  <tr>
				<th colspan='4'>Sucursal <b>".strtoupper($sucursales['sc_nombre'])."</b></th>
			  </tr>
			  <tr>
				<th colspan='4'>Sus Citas para hoy son:</th>
			  </tr>
			  <tr>
			  	<th>Hora de Citas</th>
			  	<th>Paciente</th>
			  	<th>Doctor</th>
			  	<th>Tratamiento</th>
			  </tr>";
			  $sc_citasSQL = $con->query("SELECT * FROM citas, doctores, tratamientos, pacientes WHERE citas.ct_idSucursal='$sucursales[IDSucursal]' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_estado<2 AND citas.ct_fechaInicio='$fechaHoySinEsp' ORDER BY citas.ct_fechaOrden ASC");
			  while($sc_citas = $sc_citasSQL->fetch_assoc()){
$html.="	  <tr>
			  	<td>". $sc_citas['ct_horaCita']." (".$sc_citas['ct_duracion']." min)</td>
			  	<td>". $sc_citas['pc_nombres']."</td>
			  	<td>". $sc_citas['dc_nombres']."</td>
			  	<td>". $sc_citas['tr_nombre']."</td>
			  </tr>";
			  }
$html.="	  <tr style='height: 50px; border-top: 1px solid ".$colorPrincipal.";'>
				<td colspan='4' style='padding: 10px; font-size: 12px;'>powered by <a href='https://mantiztechnology.com/'>MantizTechnology</a></td>
			  </tr>
			</table>
			";

		$mail->AddAddress($destino);
		$mail->Subject = utf8_decode('Citas '.$hoyDia.'-'.$hoyMes.'-'.$hoyAno);
		$mail->msgHTML(utf8_decode($html));

		$mail->send();


	}
} ?>