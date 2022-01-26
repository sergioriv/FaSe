<?php include'config-lobby.php'; include'generarCodigo.php'; include'smtp.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultorioRegistro'][$clave] = $valor;
}

extract($_SESSION['consultorioRegistro']);

$codigoVerificacion = generarCodigo(6);


$registro = $con->query("INSERT INTO clinicas SET cl_nombre='$nombre', cl_nit='$nit', cl_correo='$correo', cl_colorPrimario='#00a0e3', cl_colorSecundario='#b0cb1f', cl_iconoNuevo='8', cl_iconoEditar='10', cl_iconoEliminar='17', cl_estado='1', cl_fechaCreacion='$fechaHoy'");
$registroID = $con->insert_id;
	

	$password_hash = password_hash($confirma, PASSWORD_DEFAULT);
	$con->query("INSERT INTO usuarios SET us_idClinica='$registroID', us_idRol='1', us_id='$registroID', us_nombre='$nombre', us_correo='$correo', us_password='$password_hash', us_codVerificacion='$codigoVerificacion', us_estado='1', us_fechaCreacion='$fechaHoy'");
	$_SESSION['consultorioRegistroID'] = $con->insert_id;

$correoLogin = $correo;
$passwordLogin = $confirma;

unset($_SESSION['consultorioRegistro']);

$_SESSION['consultoriosRegistroCorreo'] = $correoLogin;

if($registro){
	
//	$colorPrincipal = '#00a0e3';
	$html = "
		<style type='text/css'>
			span.im {
				color: black;
			}
		</style>
		<table border='0' style='border-collapse: collapse; width: 100%; font-family: Helvetica, Arial, sans-serif; color: black; font-size: 18px;'>
		  <tr style='height: 50px; border-bottom: 1px solid ".$colorPrincipal.";'>
			<td style='padding: 10px; font-size: 24px;'>Confirmación Registro</td>
		  </tr>
		  <tr>
			<td style='padding: 10px; text-align: center;'>
			<p>Código de verificación: <b>".$codigoVerificacion."</b></p>
			<p style='font-size: 13px;'><i>Nota: Este mensaje ha sido generado automaticamente. Por favor no lo responda.</i></p>
			</td>
		  </tr>
		  <tr style='height: 50px; border-top: 1px solid ".$colorPrincipal.";'>
			<td style='padding: 10px; font-size: 14px;'>powered by <a href='https://mantiztechnology.com/'>MantizTechnology</a></td>
		  </tr>
		</table>
		";
			
			$mail->AddAddress($correo);
			$mail->Subject = utf8_decode('Confirmación Registro | FaSe');
			$mail->msgHTML(utf8_decode($html));

		if($mail->send()){ $_SESSION['consultoriosLobbyMsj']=2; } else { $_SESSION['consultoriosLobbyMsj']=1; }

?>
	<script type="text/javascript">
		$('#formLobby').load('registro-codigo.php');
	</script>
<?php } else { ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div>Error al Registrarse. Inténtelo nuevamente.</div>
	</label>
	<script type="text/javascript">
	$(document).ready(function() {
		document.getElementById('nombre').focus();
	    setTimeout(function() {
	        $(".alerta").fadeOut(500);
	    },3000);
	});
	</script>
<?php } ?>