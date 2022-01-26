<?php include'config-lobby.php'; include'generarCodigo.php'; include'smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$codigoVerificacion = generarCodigo(6);
$destinatario = $_SESSION['consultoriosLobbyCorreo'];

if($destinatario){
?>
	<div id="msj-login">
		<input type="radio" id="alertExito">
		<label class="alerta exito" for="alertExito">
			<div>Enviando. . .</div>
		</label>
	</div>
<?php
	
	$con->query("UPDATE usuarios SET us_codVerificacion = '$codigoVerificacion' WHERE IDUsuario='$_SESSION[consultoriosLobbyRol]' AND us_estado = '1'");

//	$colorPrincipal = '#00a0e3';


	$html = "
		<style type='text/css'>
			span.im {
				color: black;
			}
		</style>
		<table border='0' style='border-collapse: collapse; width: 100%; font-family: Helvetica, Arial, sans-serif; color: black; font-size: 18px;'>
		  <tr style='height: 50px; border-bottom: 1px solid ".$colorPrincipal.";'>
			<td style='padding: 10px; font-size: 24px;'>Recuperar contraseña</td>
		  </tr>
		  <tr>
			<td style='padding: 10px; text-align: center;'>
			<p>Código de verificación: <b>".$codigoVerificacion."</b></p>
			<p style='font-size: 13px;'><i>Nota: Este mensaje ha sido generado automaticamente. Por favor no lo responda.</i></p>
			</td>
		  </tr>
		  <tr style='height: 50px; border-top: 1px solid ".$colorPrincipal.";'>
			<td style='padding: 10px; font-size: 12px;'>powered by <a href='https://mantiztechnology.com/'>MantizTechnology</a></td>
		  </tr>
		</table>
		";



		$mail->AddAddress($destinatario);
		$mail->Subject = utf8_decode('Recuperar contraseña | FaSe');
		$mail->msgHTML(utf8_decode($html));

		if($mail->send()){ $_SESSION['consultoriosLobbyMsj']=2; } else { $_SESSION['consultoriosLobbyMsj']=1; }



?>
	<script type="text/javascript">
		$('#lobbyForm').load('validar-password-codigo.php');
	</script>
<?php } else { header("location:./"); } ?>