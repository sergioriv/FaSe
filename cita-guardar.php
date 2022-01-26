<?php include'config.php'; include'key.php'; include'smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$citaAnno = $_POST['annoCita'];
$citaMes = $_POST['mesCita'];
$citaDia = $_POST['diaCita'];
$citaHora = $_POST['horaCita'];
$citaHoraInt = $_POST['horaInt'];
$citaDuracion = $_POST['duracion'];
$citaSucursal = $_POST['sucursal'];
$citaUnidad = $_POST['unidad'];
$citaDoctor = $_POST['doctor'];
$citaTratamiento = $_POST['tratamiento'];
$citaTipoTratamiento = $_POST['tipoTratamiento'];
$citaTratamientoPrecio = $_POST['tratamientoPrecio'];
$citaTratamientoPresupuesto = $_POST['tratamientoPresupuesto'];
$citaPaciente = $_POST['paciente'];
$citaCitaID = $_POST['citaID'];
$citaNota = nl2br(trim($_POST['notaCita']));
$citaControl = $_POST['control'];
$citaEnviarCorreo = $_POST['enviarCorreo'];


$citaVerificar = $con->query("SELECT COUNT(*) AS cant FROM citas WHERE ct_idSucursal = '$citaSucursal' AND ct_idUnidad = '$citaUnidad' AND ct_estado IN(0,1) AND ct_anoCita='$citaAnno' AND ct_mesCita='$citaMes' AND ct_diaCita='$citaDia' AND ct_horaCita='$citaHora' ORDER BY ct_horaCitaDe ASC")->fetch_assoc();
if($citaVerificar['cant'] > 0){
	
	$_SESSION['consultoriosExito'] = 1;


		if($_POST['dash']==1){
?>
			<script type="text/javascript">window.location="dashboard";</script>
		<?php
		} else {
?>
			<script type="text/javascript">window.location="citas";</script>
<?php
		}

		return false;
} else{


	if($citaEnviarCorreo==1){
		$citaCorreoEnviar = $_POST['correoEnviar'];
	} else {
		$citaCorreoEnviar = "";
	}


	$citaFecha = $citaAnno.'/'.$citaMes.'/'.$citaDia;

	$citaHasta = $citaDuracion-5;

	$citaHoraHasta = strtotime ( '+'.$citaHasta.'minute' , strtotime ( $citaHora ) ) ;
	$citaHoraHastaFull = date ( 'Hi' , $citaHoraHasta);

	$citaFechaHasta = strtotime ( '+'.$citaHasta.'minute' , strtotime ( $citaFecha ) ) ;
	$citaFechaHastaFull = date ( 'Ymd' , $citaFechaHasta);

	if( strlen($citaHoraInt) <= 3 ){ $horaOrden = '0'.$citaHoraInt; } else { $horaOrden = $citaHoraInt; }

		if($citaCitaID!=""){
			$con->query("UPDATE citas SET ct_estado = '2' WHERE IDCita = '$citaCitaID'");

			// EMAIL PACIENTE DE CANCELACION
		}
	/*
		$citaTratamientoSql = $con->query("SELECT IDTratamiento, tr_precio FROM tratamientos WHERE IDTratamiento = '$citaTratamiento'");
		$citaTratamientoRow = $citaTratamientoSql->fetch_assoc();
	*/
		//VALIDACION DE SESION
		$validarSesion = $con->query("SELECT * FROM citas WHERE ct_idPaciente='$citaPaciente' AND ct_idTratamiento='$citaTratamiento' ORDER BY IDCita DESC");
		$validarSesionRow = $validarSesion->fetch_assoc();

		//nuevo tratamiento = 1
		//siguiente sesion = 2
		
		if($validarSesionRow['ct_terminado']==1 || $validarSesionRow['ct_terminado']==2){
			$terminado = 2;
			$precioCita = 0;
			$ctInicial = 0;
		}
		else {
			$terminado = 1;
			$precioCita = $citaTratamientoPrecio;
			$ctInicial = 1;
		}


	$citaGuardar = $con->query("INSERT INTO citas SET 
	 ct_idClinica = '$sessionClinica',
	 ct_idUsuario = '$sessionUsuario',
	 ct_idSucursal = '$citaSucursal',
	 ct_idUnidad = '$citaUnidad',
	 ct_idDoctor = '$citaDoctor',
	 ct_idPaciente  = '$citaPaciente',
	 ct_idTratamiento  = '$citaTratamiento',
	 ct_tipoConvenio  = '$citaTipoTratamiento',
	 ct_idTrataConvenio  = '$citaTratamientoPresupuesto',
	 ct_anoCita = '$citaAnno',
	 ct_mesCita = '$citaMes',
	 ct_diaCita = '$citaDia',
	 ct_horaCita = '$citaHora',
	 ct_duracion = '$citaDuracion',
	 ct_nota = '$citaNota',
	 ct_control = '$citaControl',
	 ct_correoEnviado = '$citaCorreoEnviar',
	 ct_horaCitaDe = '$citaHoraInt',
	 ct_horaCitaHasta = '$citaHoraHastaFull',
	 ct_fechaInicio = '$citaAnno$citaMes$citaDia',
	 ct_fechaFin = '$citaFechaHastaFull',
	 ct_fechaOrden = '$citaAnno$citaMes$citaDia$horaOrden',
	 ct_costo = '$precioCita',
	 ct_inicial = '$ctInicial',
	 ct_terminado = '$terminado',
	 ct_estado = '0',
	 ct_fechaCreacion = '$fechaHoy'
	 ");

	if($citaGuardar){

		$_SESSION['consultoriosExito']=2;
		$citaID = $con->insert_id;
		
		// EMAIL PACIENTE DE ASIGNACION
		$pacienteRow = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$citaPaciente'")->fetch_assoc();

		$doctorRow = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$citaDoctor'")->fetch_assoc();

		$sucursalRow = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$citaSucursal'")->fetch_assoc();

		$tratamientoRow = $con->query("SELECT * FROM tratamientos WHERE IDTratamiento = '$citaTratamiento'")->fetch_assoc();


		if($citaEnviarCorreo==1 && $citaCorreoEnviar!=""){
			
			$destinatario = $citaCorreoEnviar;

			$encodeID = "id=$citaID";
			$encodeID = utf8_encode($encodeID);
			$encodeID = $llave_encriptacion.$encodeID.$llave_encriptacion; //concateno la llave para encriptar la cadena
			$encodeID = base64_encode($encodeID);//codifico la cadena 
		
			$asunto = "Nueva Cita | ".$clinicaRow['cl_nombre'];
			
			if($clinicaRow['cl_logo']!=""){ $titulo = "<img style='max-width: 150px; max-height: 60px;' src='$ruta$clinicaRow[cl_logo]'>"; } else { $titulo = strtoupper($clinicaRow['cl_nombre']); }

			$html = "
			<style type='text/css'>
				span.im {
					color: black !important;
				}
			</style>
			<table border='0' style='border-collapse: collapse; width: 100%; font-family: Helvetica, Arial, sans-serif; color: black; font-size: 15px;'>
			  <tr style='height: 100px; border-bottom: 1px solid ".$colorPrincipal.";'>
				<td style='padding: 10px; font-size: 20px; text-align: center;'>".$titulo."</td>
			  </tr>
			  <tr>
				<td style='padding: 10px; text-align: center;'>
				<p>Tu cita ha sido programada con las siguientes características</p>
				<p>Fecha:<br><span style='font-size: 18px; color: ".$colorPrincipal.";'>".$citaFecha."</span></p>
				<p>Hora:<br><span style='font-size: 18px; color: ".$colorPrincipal.";'>".$citaHora."</span></p>
				<p>Paciente:<br><span style='font-size: 18px; color: ".$colorPrincipal.";'>".$pacienteRow['pc_nombres']."</span></p>
				<p>Doctor:<br><span style='font-size: 18px; color: ".$colorPrincipal.";'>".$doctorRow['dc_nombres']."</span></p>
				<p>Sede:<br><span style='font-size: 18px; color: ".$colorPrincipal.";'>".$sucursalRow['sc_nombre']."</span></p>
				<p>Tratamiento:<br><span style='font-size: 18px; color: ".$colorPrincipal.";'>".$tratamientoRow['tr_nombre']."</span></p>
				<p style='height: 50px; margin-top: 40px;'>
					<a href='".$ruta.'confirmacion?'.$encodeID."' style='background: #25d025; color: white; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none;'>Confirmar Cita</a>
					<a href='".$ruta.'cancelacion?'.$encodeID."' style='background: #dc3030; color: white; padding: 8px 15px; border-radius: 3px; cursor: pointer; text-decoration: none;'>Cancelar cita</a>
				</p>
				<p style='font-size: 13px;'>Recuerda llegar entre 15 y 20 minutos antes y presentarte en la plataforma de servicio. Te informamos que para la modificación o cancelación de la cita, debes hacerlo con un mínimo de 2 horas de anticipación. En caso de llegar tarde, la cita se cancelará y deberá reprogramarla nuevamente.</p>
				<p style='font-size: 14px;'><i>Nota: Este mensaje ha sido generado automaticamente. Por favor no lo responda.</i></p>
				</td>
			  </tr>
			  <tr style='height: 50px; border-top: 1px solid ".$colorPrincipal.";'>
				<td style='padding: 10px; font-size: 12px;'>powered by <a href='https://mantiztechnology.com/'>MantizTechnology</a></td>
			  </tr>
			</table>
			";

			$mail->AddAddress($destinatario);
			$mail->Subject = utf8_decode($asunto);
			$mail->msgHTML(utf8_decode($html));
			$mail->send();

		}

	} else { $_SESSION['consultoriosExito']=1; }



	if($_POST['dash']==1){

		if(!empty($_POST['dashDate'])){
			$dashDate = $_POST['dashDate'];
		} else {
			$dashDate = date('Y-m-d');
		}
	?>
		<script type="text/javascript">
					$.ajax({
			        	url: "extras/dashComparativo.php",
				        method:"POST",
			            data:{fecha:'<?= $dashDate ?>'}, 
				        success:function(data){
							$('#unidadComparativoCont').html(data);
						}
				    });
		</script>
	<?php
	} else {
		header("location:citas");
	}
}
?>