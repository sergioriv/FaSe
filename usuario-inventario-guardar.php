<?php include'config.php'; include'generarCodigo.php'; include'smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$usuarioInventarioID = $_POST['id'];
$sucursal = $_POST['sucursal'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$enviarAlertas = $_POST['enviarAlertas'];

$passwordUser = generarCodigo(6);
$passwordUsuarios = password_hash($passwordUser, PASSWORD_DEFAULT);

if(!$_POST['id']){

	$query = $con->query("INSERT INTO usuariosinventario SET ui_idClinica='$sessionClinica', ui_idUsuario='$sessionIDUsuario', ui_idSucursal='$sucursal', ui_nombres='$nombre', ui_correo='$correo', ui_enviarCorreo='$enviarAlertas', ui_estado='1', ui_fechaCreacion='$fechaHoy'");
	$id = $con->insert_id;

	if($query){ $_SESSION['consultoriosExito']=2;

		$con->query("INSERT INTO usuarios SET us_idClinica='$sessionClinica', us_idRol='4', us_id='$id', us_nombre='$nombre', us_correo='$correo', us_password='$passwordUsuarios', us_estado='1', us_fechaCreacion='$fechaHoy'");

		if($correo!=""){
			if($clinicaRow['cl_logo']!=""){ $titulo = "<img style='max-width: 150px; max-height: 60px;' src='$ruta$clinicaRow[cl_logo]'>"; }
			else { $titulo = strtoupper($clinicaRow['cl_nombre']); }

			$html="
			<style type='text/css'>
				span.im {
					color: black;
				}
			</style>
			<table border='0' style='border-collapse: collapse; width: 100%; font-family: Helvetica, Arial, sans-serif; color: black; font-size: 15px;'>
			  <tr style='height: 100px; border-bottom: 1px solid ".$colorPrincipal.";'>
				<td style='padding: 10px; font-size: 20px; text-align: center;'>".$titulo."</td>
			  </tr>
			  <tr>
				<td>
					<p></p>
					<p>Hola, <b>".strtoupper($nombre)."</b>.</p>
					<p><b>".strtoupper($clinicaRow['cl_nombre'])."</b> ahora utiliza la platafoma de Gestion de Citas <b>FaSe</b>, en ella podrás llevar el control del inventario.</p>
					<p><br></p>
					<p>Tus datos de acceso son:</p>
					<p>Correo: ".$correo."<br>Contraseña: ".$passwordUser."<br>Rol: Inventario</p>
					<p></p>
					<p>Accede a través de ".$ruta."</p>
					<p><br><br><br><br></p>
					<p style='font-size: 14px; text-align: center;'><i>Este mensaje ha sido generado automaticamente. Por favor no lo responda.</i></p>
				</td>
			  </tr>
			  <tr style='height: 50px; border-top: 1px solid ".$colorPrincipal.";'>
				<td style='padding: 10px; font-size: 12px;'>powered by <a href='https://mantiztechnology.com/'>MantizTechnology</a></td>
			  </tr>
			</table>
			";


			$mail->AddAddress($correo);
			$mail->Subject = utf8_decode('Bienvenido/a | FaSe');
			$mail->msgHTML(utf8_decode($html));

			if(!$mail->send()){ $_SESSION['consultoriosExito']=100; $_SESSION['consultoriosMSJPASS']=$passwordUser; }

		}



	} else { $_SESSION['consultoriosExito']=1; }

} else {
	$id = $_POST['id'];
	$query = $con->query("UPDATE usuariosinventario SET ui_idSucursal='$sucursal', ui_nombres='$nombre', ui_correo='$correo', ui_enviarCorreo='$enviarAlertas' WHERE IDUserInventario = '$id'");

	if($query){ $_SESSION['consultoriosExito']=3;

		$con->query("UPDATE usuarios SET us_nombre='$nombre', us_correo='$correo' WHERE us_id = '$id' AND us_idRol='4'");

		// EMAIL USUARIO DE ACTUALIZACION DE CORREO

	} else { $_SESSION['consultoriosExito']=1; }
}
header("Location:$_SESSION[concultoriosAntes]");
?>