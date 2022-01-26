<?php include'config.php';

$citaID = $_POST['citaID'];
$date = $_POST['date'] ? $_POST['date'] : date('Y-m-d');

$firma_usuario = $_POST['usuario'];
$firma_paciente = $_POST['paciente'];

$consecutivo_concentimientoClinica = 0;
$consecutivo_concentimientoPaciente = 0;


$citaSql = $con->query("SELECT ct_idPaciente FROM citas WHERE IDCita = '$citaID'")->fetch_assoc();


$pacienteConcecutivo = $con->query("SELECT MAX(ctm_consecutivoPaciente) AS max FROM concentimientos WHERE ctm_idPaciente = '$citaSql[ct_idPaciente]'")->fetch_assoc();
$consecutivo_concentimientoPaciente = $pacienteConcecutivo['max'] + 1;

$clinicaConcecutivo = $con->query("SELECT MAX(ctm_consecutivoClinica) AS max FROM concentimientos WHERE ctm_idClinica = '$sessionClinica'")->fetch_assoc();
$consecutivo_concentimientoClinica = $clinicaConcecutivo['max'] + 1;


$con->query("INSERT INTO concentimientos SET ctm_idClinica='$sessionClinica', ctm_idCita='$citaID', ctm_idPaciente='$citaSql[ct_idPaciente]', ctm_idUsuario='$sessionIDUsuario', ctm_firmaPaciente='$firma_paciente', ctm_firmaDoctor='$firma_usuario', ctm_fechaCreacion='$fechaHoy', ctm_consecutivoClinica='$consecutivo_concentimientoClinica', ctm_consecutivoPaciente='$consecutivo_concentimientoPaciente'");

?>
<script type="text/javascript">
				$.ajax({
		        	url: "extras/dashComparativo.php",
			        method:"POST",
		            data:{fecha:'<?= $date ?>'}, 
			        success:function(data){
						$('#unidadComparativoCont').html(data);
						$('#consultoriosModal').modal('hide');
					}
			    });
</script>