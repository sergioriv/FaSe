<?php include'config.php';

$citaID = $_POST['id'];
$asistencia = $_POST['asistencia'];
$descripcion = $_POST['descripcion'];
$finalidad = $_POST['finalidad'];
$externa = $_POST['externa'];
$ripsID = $_POST['rips'];
$rips1ID = $_POST['rips1'];
$rips2ID = $_POST['rips2'];
$rips3ID = $_POST['rips3'];
$tratamiento = $_POST['tratamiento'];
$pacienteID = $_POST['pacienteID'];
$tratamientoID = $_POST['tratamientoID'];
$proximaCita = $_POST['proximaCita'];
$ev = $_POST['ev'];

$firma_paciente = $_POST['firma_evo_paciente'];
$firma_usuario = $_POST['firma_evo_usuario'];
$progress_tratamiento = $_POST['progress_tratamiento'];

if($asistencia==0){
	$descripcion = '';
	$ripsID = 0;
	$tratamientoID = 1000;
	$query = $con->query("UPDATE citas SET ct_idTratamiento='$tratamientoID', ct_costo='0', ct_inicial='1', ct_terminado='3', ct_evolucionada='1', ct_asistencia='1', ct_descripcion='$descripcion', ct_idRip='$ripsID', ct_idRip1='$ripsID', ct_idRip2='$ripsID', ct_idRip3='$ripsID', ct_idFinalidad='0', ct_idCausaExterna='0' WHERE IDCita = '$citaID'");
	//$con->query("UPDATE citas SET ct_idTratamiento='$tratamientoID', ct_costo='0', ct_inicial='1', ct_terminado='3', ct_asistencia='1', ct_descripcion='$descripcion', ct_idRip='$ripsID', ct_idRip1='$rips1ID', ct_idRip2='$rips2ID', ct_idRip3='$rips3ID', ct_idFinalidad='$finalidad', ct_idCausaExterna='$externa' WHERE IDCita = '$citaID'");
} else {

	$query = $con->query("UPDATE citas SET ct_descripcion='$descripcion', ct_idRip='$ripsID', ct_idRip1='$rips1ID', ct_idRip2='$rips2ID', ct_idRip3='$rips3ID', ct_idFinalidad='$finalidad', ct_idCausaExterna='$externa', ct_evolucionada='1', ct_asistencia='2', ct_trataPorcentaje='$progress_tratamiento', ct_evoFirmaPaciente='$firma_paciente', ct_evoFirmaUsuario='$firma_usuario' WHERE IDCita = '$citaID'");


		$progress_tratamiento_inicial = $con->query("SELECT IDCita, ct_costo, ct_terminado FROM citas 
					WHERE ct_idTratamiento = '$tratamientoID' AND ct_idPaciente = '$pacienteID' AND ct_inicial = '1' AND IDCita <= '$citaID' ORDER BY IDCita DESC")->fetch_assoc();

		$progress_tratamiento_suma = $con->query("SELECT SUM(ct_trataPorcentaje) AS porcentaje FROM citas 
					WHERE ct_idTratamiento = '$tratamientoID' AND ct_idPaciente = '$pacienteID' AND IDCita BETWEEN '$progress_tratamiento_inicial[IDCita]' AND '$citaID' ORDER BY IDCita DESC")->fetch_assoc();

			$costo_tratamiento = $progress_tratamiento_inicial['ct_costo'];
			$con->query("UPDATE citas SET ct_costo='$costo_tratamiento' WHERE IDCita = '$citaID'");
		

		if( $progress_tratamiento_suma['porcentaje'] >= 100 && $progress_tratamiento_inicial['ct_terminado']!=3 ){

			 
			// PRESUPUESTO CAMBIO ESTADO
	
			$tratamientoPresupuesto = $con->query("SELECT ct_idTrataConvenio FROM citas WHERE IDCita = '$citaID'")->fetch_assoc();
			$estadoTratamientoPresupuesto = $con->query("UPDATE presupuestotratamientos SET ppt_activo=0 WHERE IDPresupuestoTrata = '$tratamientoPresupuesto[ct_idTrataConvenio]'");

			//  CAMBIO DE ESTADO A 3
			$terminacionRow = $con->query("SELECT * FROM citas WHERE ct_idTratamiento = '$tratamientoID' AND ct_idPaciente = '$pacienteID' AND ct_inicial = '1' AND IDCita <= '$citaID' ORDER BY IDCita DESC")->fetch_assoc();

			$terminacionTresRow = $con->query("SELECT * FROM citas WHERE ct_terminado = '3' AND ct_idTratamiento = '$tratamientoID' AND ct_idPaciente = '$pacienteID' AND IDCita > '$terminacionRow[IDCita]'")->fetch_assoc();
			
			if($terminacionTresRow['IDCita']){
				$terminadoSql = $con->query("SELECT * FROM citas WHERE IDCita >= '$terminacionRow[IDCita]' AND IDCita <= '$terminacionTresRow[IDCita]' AND ct_idTratamiento = '$tratamientoID' AND ct_idPaciente = '$pacienteID'");
				while($terminadoRow = $terminadoSql->fetch_assoc()){
					$con->query("UPDATE citas SET ct_terminado='3', ct_terminadoFecha='$fechaHoy' WHERE IDCita = '$terminadoRow[IDCita]'");
				}
			} else {
				$terminadoSql = $con->query("SELECT * FROM citas WHERE IDCita >= '$terminacionRow[IDCita]' AND ct_idTratamiento = '$tratamientoID' AND ct_idPaciente = '$pacienteID'");
				while($terminadoRow = $terminadoSql->fetch_assoc()){
					$con->query("UPDATE citas SET ct_terminado='3', ct_terminadoFecha='$fechaHoy' WHERE IDCita = '$terminadoRow[IDCita]'");
				}
			}


			$citaTermianda = $con->query("UPDATE citas SET ct_inicial=3 WHERE IDCita = '$citaID'");

		}

}



// TAREA
$tareaID = $_POST['tareaID'];
$tareaNota = nl2br(trim($_POST['notaAlerta']));
$tareaFecha = $_POST['fechaAlerta'];
$tareaTipo = $_POST['tipoTarea'];
$tareaResponsable = $_POST['responsableAlerta'];
$tareaFechaCreacion = date('Y/m/d');

if($tareaID == 0 && !empty($tareaFecha)){
	$con->query("INSERT INTO tareas SET tar_idClinica='$sessionClinica', tar_idCita='$citaID', tar_idTipo='$tareaTipo', tar_fecha='$tareaFecha', tar_responsable='$tareaResponsable', tar_nota='$tareaNota', tar_estado='0', tar_creada='$tareaFechaCreacion'");
} else {
	$con->query("UPDATE tareas SET tar_idTipo='$tareaTipo', tar_fecha='$tareaFecha', tar_responsable='$tareaResponsable', tar_nota='$tareaNota' WHERE IDTarea = '$tareaID'");
}




if($proximaCita==1){

	if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }

	$pacienteRow = $con->query("SELECT pc_nombres FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();
	$pacienteUrl = 'cita?id='.$pacienteID.'&paciente='.str_replace(" ","-", $pacienteRow['pc_nombres']);
?>
	<script type="text/javascript">
		setTimeout("location.href = '<?php echo $pacienteUrl ?>'");
	</script>
<?php
} else {

	if($ev==1){
		if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }
	?>
		<script type="text/javascript">
			setTimeout("location.href = '<?php echo $_SESSION[concultoriosAntes] ?>'");
		</script>
	<?php
	} else if($ev=='paciente') {

		if($query){
	?>
		
				<input type="radio" id="alertExito">
				<label for="alertExito" class="alerta exito">
					<div>Sus cambios han sido guardados con exito.</div>
					<div class="close">&times;</div>
				</label>
		
	<?php
		} else {
	?>
		
				<input type="radio" id="alertError">
				<label class="alerta error" for="alertError">
					<div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div>
					<div class="close">&times;</div>
				</label>
		
	<?php
		}
	?>
		<script type="text/javascript">
			paginationPcCitas(0);
			$("#consultoriosModal").modal('hide');
		</script>

	<?php
	} else if($ev=='doctor') {

		if($query){
	?>
		
				<input type="radio" id="alertExito">
				<label for="alertExito" class="alerta exito">
					<div>Sus cambios han sido guardados con exito.</div>
					<div class="close">&times;</div>
				</label>
		
	<?php
		} else {
	?>
		
				<input type="radio" id="alertError">
				<label class="alerta error" for="alertError">
					<div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div>
					<div class="close">&times;</div>
				</label>
		
	<?php
		}
	?>
		<script type="text/javascript">
			paginationHsDoctorCitas(0);
			$("#consultoriosModal").modal('hide');
		</script>

	<?php
	} else if($ev=='dash'){
	?>
		<script type="text/javascript">
			dashComparativo();
			$("#consultoriosModal").modal('hide');
		</script>
	<?php
	}
}?>