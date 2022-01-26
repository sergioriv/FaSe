<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

	$compleanosHoy = new DateTime($fechaHoy);
	$cumpleanos = new DateTime($nacimiento);
	$diffCumpleanos = $compleanosHoy->diff($cumpleanos);
	$edad = $diffCumpleanos->y;

$nombreCompleto = $apellido1.' '.$apellido2.' '.$nombre;

//if($referencia!=12){ $referido = 0; }

if(!empty($firma_paciente)){
	$guardarFirma = "pc_firma='$firma_paciente',";
} else { $guardarFirma = ""; }

if(!$_POST['id']){
	$query = $con->query("INSERT INTO pacientes SET 
		pc_idClinica='$sessionClinica', 
		pc_idUsuario='$sessionUsuario',
		pc_apellido1='$apellido1',
		pc_apellido2='$apellido2',
		pc_nombre='$nombre',
		pc_nombres='$nombreCompleto',
		pc_idIdentificacion='$tipoIdentificacion',
		pc_identificacion='$identificacion',
		pc_idSexo='$sexo',
		pc_fechaNacimiento='$nacimiento',
		pc_edad='$edad',
		pc_idEstadoCivil='$estadocivil',
		pc_idEscolaridad='$escolaridad',
		pc_idDep='$departamentos',
		pc_idCiudad='$ciudad',
		pc_idZona='$zona',
		pc_direccion='$direccion',
		pc_telefonoFijo='$telefono',
		pc_telefonoCelular='$celular',
		pc_correo='$correo',
		pc_enviarCorreo='$enviarAlertas',
		pc_idEps='$eps',
		pc_idRegimen='$regimen',
		pc_idAfiliacion='$afiliacion',
		pc_idOcupacion='$ocupacion',
		pc_idEtnia='$etnia',
		pc_responsable='$responsable',
		pc_idReferencia='$referencia',
		pc_idReferido='$referido',
		pc_etiqueta='$etiqueta',
		$guardarFirma
		pc_estado='1',
		pc_fechaCreacion='$fechaHoy'");

	$id = $con->insert_id;

	$query2 = $con->query("INSERT INTO evolucionpaciente SET ev_idPaciente='$id'");
	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
} else {
	$id = $_POST['id'];

	$query = $con->query("UPDATE pacientes SET 
		pc_apellido1='$apellido1',
		pc_apellido2='$apellido2',
		pc_nombre='$nombre',
		pc_nombres='$nombreCompleto',
		pc_idIdentificacion='$tipoIdentificacion',
		pc_identificacion='$identificacion',
		pc_idSexo='$sexo',
		pc_fechaNacimiento='$nacimiento',
		pc_edad='$edad',
		pc_idEstadoCivil='$estadocivil',
		pc_idEscolaridad='$escolaridad',
		pc_idDep='$departamentos',
		pc_idCiudad='$ciudad',
		pc_idZona='$zona',
		pc_direccion='$direccion',
		pc_telefonoFijo='$telefono',
		pc_telefonoCelular='$celular',
		pc_correo='$correo',
		pc_enviarCorreo='$enviarAlertas',
		pc_idEps='$eps',
		pc_idRegimen='$regimen',
		pc_idAfiliacion='$afiliacion',
		pc_idOcupacion='$ocupacion',
		pc_idEtnia='$etnia',
		pc_responsable='$responsable',
		pc_idReferencia='$referencia',
		pc_idReferido='$referido',
		$guardarFirma
		pc_etiqueta='$etiqueta' WHERE IDPaciente='$id'");

	$query2 = $con->query("UPDATE evolucionpaciente SET
		ev_higieneOral='$higieneOral',
		ev_seda='$sedaDental',
		ev_cepillo='$cepillo',
		ev_enjuagues='$enjuagues',
		ev_cantVeces='$cantVeces',
		ev_atm='$atm',
		ev_labios='$labios',
		ev_lengua='$lengua',
		ev_paladar='$paladar',
		ev_pisoBoca='$pisoBoca',
		ev_carrillos='$carrillos',
		ev_glandulasSalivares='$glandulasSalivares',
		ev_maxilares='$maxilares',
		ev_senosMaxilares='$senosMaxilares',
		ev_muscMasticadores='$muscMasticadores',
		ev_ganglios='$ganglios',
		ev_oclusion='$oclusion',
		ev_frenillos='$frenillos',
		ev_mucosas='$mucosas',
		ev_encias='$encias',
		ev_amigdalas='$amigdalas',
		ev_superNumerarios='$superNumerarios',
		ev_abrasion='$abrasion',
		ev_manchas='$manchas',
		ev_patologiaPulpar='$patologiaPulpar',
		ev_maloclusiones='$maloclusiones',
		ev_incluidos='$incluidos',
		ev_trauma='$trauma',
		ev_habitos='$habitos',
		ev_bolsas='$bolsas',
		ev_placaBlanda='$placaBlanda',
		ev_calculos='$calculos',
		ev_observaciones='$observacionesEvolucion' WHERE ev_idPaciente='$id'");
	if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
}


/*
	GUARDAR FIRMA
 */
/*
if($firma_paciente){
	$data_uri = $firma_paciente;
	$encoded_image = explode(",", $data_uri)[1];
	$decoded_image = base64_decode($encoded_image);
	file_put_contents("firmas/pc-".$id.".png", $decoded_image);
}
*/

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
		
			$medidasimagen= getimagesize($_FILES['filePhoto']['tmp_name']);
			$carpeta = "img-users/";
			$inicial = 'P';
			$src = $carpeta.$inicial.$id.".jpg";
			mysqli_query($con,"UPDATE pacientes SET pc_foto='$src' WHERE IDPaciente = '$id'");

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

		}
		
	}

unset($_SESSION['consultoriosQuery']);

header("Location:$_SESSION[concultoriosAntes]");
?>