<?php include'config.php';

$citaID = $_POST['citaID'];
$extra = $_POST['extra'];
$site = $_POST['site'];
$div = $_POST['div'];
$action = $_POST['action'];

$consentimientoID = $_POST['consentimiento'];
$firma_usuario = $_POST['usuario'];
$firma_paciente = $_POST['paciente'];


if(!empty($extra)){
	$send_id = ", id:$extra";
}else{ $send_id = ''; }

$estado = 0;
if($action==0){ $estado = 2; }
else if($action==1){ $estado = 1; }


$consecutivo_concentimientoClinica = 0;
$consecutivo_concentimientoPaciente = 0;


$citaSql = $con->query("SELECT ct_idPaciente FROM citas WHERE IDCita = '$citaID'")->fetch_assoc();


$pacienteConcecutivo = $con->query("SELECT MAX(ctm_consecutivoPaciente) AS max FROM concentimientos WHERE ctm_idPaciente = '$citaSql[ct_idPaciente]'")->fetch_assoc();
$consecutivo_concentimientoPaciente = $pacienteConcecutivo['max'] + 1;

$clinicaConcecutivo = $con->query("SELECT MAX(ctm_consecutivoClinica) AS max FROM concentimientos WHERE ctm_idClinica = '$sessionClinica'")->fetch_assoc();
$consecutivo_concentimientoClinica = $clinicaConcecutivo['max'] + 1;


$con->query("INSERT INTO concentimientos SET ctm_idClinica='$sessionClinica', ctm_idCita='$citaID', ctm_idPaciente='$citaSql[ct_idPaciente]', ctm_idUsuario='$sessionIDUsuario', ctm_consentimiento='$consentimientoID', ctm_firmaPaciente='$firma_paciente', ctm_firmaDoctor='$firma_usuario', ctm_fechaCreacion='$fechaHoy', ctm_consecutivoClinica='$consecutivo_concentimientoClinica', ctm_consecutivoPaciente='$consecutivo_concentimientoPaciente'");

$ajax_return = "#".$div;

if('ct_pendientes' == $site){ 

	$ajax_url = "get/citasPendientesData.php";
}
if('ct_sinevolucion' == $site){ 

	$ajax_url = "get/citasSinEvoluData.php";
}
if('ct_historico' == $site){ 

	$ajax_url = "get/citasHistoricoData.php";
}
if('pc_citas' == $site){ 

	$ajax_url = "get/pcCitasData.php";
}
if('dc_citas' == $site){ 

	$ajax_url = "get/hsDoctorCitasData.php";
}

?>


<script type="text/javascript">
				$.ajax({
		        	url: '<?= $ajax_url ?>',
			        method:"POST",
		            data:{page:0, id:'<?= $extra ?>'}, 
			        success:function(data){
						$('<?= $ajax_return ?>').html(data);
						$('#consultoriosModal').modal('hide');
					}
			    });
	
</script>