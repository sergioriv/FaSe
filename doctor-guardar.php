<?php include'config.php'; include'generarCodigo.php'; include'smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

$passwordUser = generarCodigo(6);
$passwordUsuarios = password_hash($passwordUser, PASSWORD_DEFAULT);

if(!empty($firma_concent_doctor)){
	$guardarFirma = "dc_firma='$firma_concent_doctor',";
} else { $guardarFirma = ""; }

if(!$_POST['id']){
	$query = $con->query("INSERT INTO doctores SET dc_idClinica='$sessionClinica', dc_idUsuario='$sessionUsuario', dc_nombres='$nombre', dc_tarjeta='$tarjeta', dc_idIdentificacion='$tipoIdentificacion', dc_identificacion='$identificacion', dc_telefonoFijo='$telefono', dc_telefonoCelular='$celular', dc_correo='$correo', dc_enviarCorreo='$enviarAlertas', dc_idCiudad='$ciudad', dc_direccion='$direccion', dc_genero='$genero', $guardarFirma dc_atencionDe='$horarioDcDe', dc_atencionHasta='$horarioDcHasta', dc_horarioLibreDe='$horarioLibreDcDe', dc_horarioLibreHasta='$horarioLibreDcHasta', dc_estado='1', dc_fechaCreacion='$fechaHoy'");
	$id = $con->insert_id;

	if($query){ $_SESSION['consultoriosExito']=2;

		$con->query("INSERT INTO usuarios SET us_idClinica='$sessionClinica', us_idRol='3', us_id='$id', us_nombre='$nombre', us_correo='$correo', us_password='$passwordUsuarios', us_estado='1', us_fechaCreacion='$fechaHoy'");

		$especialidades = $con->query("SELECT * FROM especialidades WHERE esp_idClinica='$sessionClinica' AND esp_estado='1'");
		while($especialidadesRow = $especialidades->fetch_assoc()){
			
			if($_POST["especialidad_".$especialidadesRow['IDEspecialidad']] == 1 ){
				$con->query("INSERT INTO doctoresespecialidades SET de_idDoctor='$id', de_idEspecialidad='$especialidadesRow[IDEspecialidad]'");
			}
		}

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
					<p><b>".strtoupper($clinicaRow['cl_nombre'])."</b> ahora utiliza la platafoma de Gestion de Citas <b>FaSe</b>, en ella podrás ver tu agenda de citas.</p>
					<p><br></p>
					<p>Tus datos de acceso son:</p>
					<p>Correo: ".$correo."<br>Contraseña: ".$passwordUser."<br>Rol: Doctor</p>
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
	$query = $con->query("UPDATE doctores SET dc_nombres='$nombre', dc_tarjeta='$tarjeta', dc_idIdentificacion='$tipoIdentificacion', dc_identificacion='$identificacion', dc_telefonoFijo='$telefono', dc_telefonoCelular='$celular', dc_correo='$correo', dc_enviarCorreo='$enviarAlertas', dc_idCiudad='$ciudad', dc_direccion='$direccion', dc_genero='$genero', $guardarFirma dc_atencionDe='$horarioDcDe', dc_atencionHasta='$horarioDcHasta', dc_horarioLibreDe='$horarioLibreDcDe', dc_horarioLibreHasta='$horarioLibreDcHasta' WHERE IDDoctor='$id'");

	if($query){ $_SESSION['consultoriosExito']=3;

		$con->query("UPDATE usuarios SET us_nombre='$nombre', us_correo='$correo' WHERE us_id = '$id' AND us_idRol='3'");


		$con->query("DELETE FROM doctoresespecialidades WHERE de_idDoctor='$id'");
		$especialidades = $con->query("SELECT * FROM especialidades WHERE esp_idClinica='$sessionClinica' AND esp_estado='1'");
		while($especialidadesRow = $especialidades->fetch_assoc()){
			
			if($_POST["especialidad_".$especialidadesRow['IDEspecialidad']] == 1 ){
				$con->query("INSERT INTO doctoresespecialidades SET de_idDoctor='$id', de_idEspecialidad='$especialidadesRow[IDEspecialidad]'");
			}
		}

		// EMAIL DOCTOR DE ACTUALIZACION DE CORREO

	} else { $_SESSION['consultoriosExito']=1; }
}

if (isset($_FILES["filePhoto"]))
	{
		$max_ancho = 200;
		$max_alto = 200;
		$file = $_FILES["filePhoto"];
		$nombreFoto = $file["name"];
		
		if($_FILES['filePhoto']['type']=='image/png' || 
			$_FILES['filePhoto']['type']=='image/jpeg' || 
			$_FILES['filePhoto']['type']=='image/jpg' || 
			$_FILES['filePhoto']['type']=='image/bmp'){

			/*
				UPDATE_SIZE RECOGER EL TAMAÑO ACTUAL DE LA IMAGEN
			 */
		
			$medidasimagen= getimagesize($_FILES['filePhoto']['tmp_name']);
			$carpeta = "img-clientes/".$sessionClinica."/";
			$inicial = 'D';
			$src = $carpeta.$inicial.$id.".jpg";
			mysqli_query($con,"UPDATE doctores SET dc_foto='$src' WHERE IDDoctor = '$id'");

			if($medidasimagen[0] < $max_ancho && $_FILES['filePhoto']['size'] < 100000){
				move_uploaded_file($_FILES['filePhoto']['tmp_name'], $src);
			} else {

				//Redimensionar
				$rtOriginal=$_FILES['filePhoto']['tmp_name'];

				if($_FILES['filePhoto']['type']=='image/jpeg' || $_FILES['filePhoto']['type']=='image/jpg'){
					$original = imagecreatefromjpeg($rtOriginal);
				}
				else if($_FILES['filePhoto']['type']=='image/png'){
					$original = imagecreatefrompng($rtOriginal);
				}
				else if($_FILES['filePhoto']['type']=='image/bmp'){
					$original = imagecreatefrombmp($rtOriginal);
				}
				 
				list($ancho,$alto)=getimagesize($rtOriginal);

				$x_ratio = $max_ancho / $ancho;
				$y_ratio = $max_alto / $alto;


				if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
				    $ancho_final = $ancho;
				    $alto_final = $alto;
				}
				elseif (($x_ratio * $alto) < $max_alto){
				    $alto_final = ceil($x_ratio * $alto);
				    $ancho_final = $max_ancho;
				}
				else{
				    $ancho_final = ceil($y_ratio * $ancho);
				    $alto_final = $max_alto;
				}

				$lienzo=imagecreatetruecolor($ancho_final,$alto_final); 

				imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);

				if($_FILES['filePhoto']['type']=='image/jpeg' || $_FILES['filePhoto']['type']=='image/jpg'){
					imagejpeg($lienzo,$src);
				}
				else if($_FILES['filePhoto']['type']=='image/png'){
					imagepng($lienzo,$src);
				}
				else if($_FILES['filePhoto']['type']=='image/bmp'){
					imagebmp($lienzo,$src);
				}

			}

			/*
				UPDATE_SIZE: AQUI SE ACTUALIZA EL VALOR DE ESPACIO TOTAL
			 */

		}
		
	}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>