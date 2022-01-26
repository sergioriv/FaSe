<?php include'config.php';
$id 			= $_POST['id'];
$sucursalID 	= $_POST['sucursal'];
$unidadID 		= $_POST['unidad'];
$doctorID 		= $_POST['doctor'];
$tratamientoID 	= $_POST['tratamiento'];
$tipoTratamiento = ( $_POST['tipoTratamiento']==1 ? 1 : 0 );
$tratamientoPrecio 	= $_POST['tratamientoPrecio'];
$tratamientoPresupuesto 	= $_POST['tratamientoPresupuesto'];
$pacienteID 	= $_POST['paciente'];
$citaID 		= $_POST['cita'];


$unidad = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica='$unidadID'")->fetch_assoc();

$sucursalRow = $con->query("SELECT IDSucursal, sc_atencionDe, sc_atencionHasta FROM sucursales WHERE IDSucursal = '$sucursalID'")->fetch_assoc();

$doctorRow = $con->query("SELECT IDDoctor, dc_nombres, dc_atencionDe, dc_atencionHasta, dc_horarioLibreDe, dc_horarioLibreHasta FROM doctores WHERE IDDoctor = '$doctorID'")->fetch_assoc();

//		$lapsoTiempo = 20;
//  	$intervaloTiempo = ($lapsoTiempo/5);

$fechaMostrar = $id;
$fechaSeleccionada = str_replace("/","",$id);

$horarioEspecialSql = $con->query("SELECT * FROM doctoreshorarios WHERE dch_idDoctor = '$doctorID' AND dch_fechaInt = '$fechaSeleccionada' ORDER BY IDDocHorario DESC");

	if($horarioEspecialSql->num_rows >= 1){

		$horarioEspecial = $horarioEspecialSql->fetch_assoc();
		$horarioDoctorDe = $horarioEspecial['dch_atencionDe'];
		$horarioDoctorHasta = $horarioEspecial['dch_atencionHasta'];

		$horarioLibreDe = str_replace(":", "", $horarioEspecial['dch_horarioLibreDe']);
		$horarioLibreHasta = str_replace(":", "", $horarioEspecial['dch_horarioLibreHasta']);

	} else {

		$horarioDoctorDe = $doctorRow['dc_atencionDe'];
		$horarioDoctorHasta = $doctorRow['dc_atencionHasta'];

		$horarioLibreDe = str_replace(":", "", $doctorRow['dc_horarioLibreDe']);
		$horarioLibreHasta = str_replace(":", "", $doctorRow['dc_horarioLibreHasta']);
	}

$sinHorario = '';
if($horarioDoctorDe=='00:00' && $horarioDoctorHasta=='00:00'){
	$sinHorario = 'El Doctor <b>'.$doctorRow['dc_nombres'].'</b> no tiene un horario asignado.';
}
	

if(!empty($unidadID)){
		if($sucursalRow['sc_atencionDe'] >= $horarioDoctorDe){
			$atencionDe = str_replace(":","",$sucursalRow['sc_atencionDe']);
		} else {
			$atencionDe = str_replace(":","",$horarioDoctorDe);
		}

		if($sucursalRow['sc_atencionHasta'] <= $horarioDoctorHasta){
			$atencionHasta = str_replace(":","",$sucursalRow['sc_atencionHasta']);
		} else {
			$atencionHasta = str_replace(":","",$horarioDoctorHasta);
		}
}

$fechaParaMostrar = strtotime ( $fechaMostrar );

$fechaParaGuardarAno = date ( 'Y' , $fechaParaMostrar);
$fechaParaGuardarMes = date ( 'm' , $fechaParaMostrar);
$fechaParaGuardarDia = date ( 'd' , $fechaParaMostrar);

$fechaParaMostrarFull = date ( 'l, d F Y' , $fechaParaMostrar);	
$fechaParaMostrarFull = str_replace("January","Enero",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("February","Febrero",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("March","Marzo",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("April","Abril",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("May","Mayo",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("June","Junio",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("July","Julio",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("August","Agosto",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("September","Septiembre",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("October","Octubre",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("November","Noviembre",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("December","Diciembre",$fechaParaMostrarFull);

$fechaParaMostrarFull = str_replace("Sunday","Domingo",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("Monday","Lunes",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("Tuesday","Martes",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("Wednesday","Miércoles",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("Thursday","Jueves",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("Friday","Viernes",$fechaParaMostrarFull);
$fechaParaMostrarFull = str_replace("Saturday","Sábado",$fechaParaMostrarFull);

$horasSql = $con->query("SELECT * FROM horas WHERE hr_horaInt >= '$atencionDe' AND hr_horaInt < '$atencionHasta' ORDER BY IDHora ASC");
//$horasSql = $con->query("SELECT @row AS row, horas.* FROM horas JOIN (SELECT @row:= 0) R WHERE hr_horaInt >= '$atencionDe' AND hr_horaInt < '$atencionHasta' AND (@row:= @row+ 1) HAVING (row % $intervaloTiempo) = 1");

?>
<style type="text/css">
	.contenedorAgenda #horasCalendario {
		display: initial;
	}
</style>
	<div class="tituloFecha">
		<?php echo '<b>'.$fechaParaMostrarFull.'</b><br><span style="color: var(--colorSecondary)">Seleccione la hora de la Cita | Unidad: '. $unidad['uo_nombre'] .'</span>'; ?>
	</div>
	<div class="tituloFecha"><?php echo $sinHorario ?></div>
			<div class="contenedorHoras">
				<?php while($horasRow = $horasSql->fetch_assoc()){
					$horaInactiva = '';
					$tituloHora = '';
					$classHora = '';

					if($horasRow['hr_horaInt'] >= $horarioLibreDe && $horasRow['hr_horaInt'] < $horarioLibreHasta){
						$horaInactiva = 'horaInactiva';
						$classHora = 'class="tooltip top"';
						$tituloHora = 'Descanso';
					}

					$citasSql = $con->query("SELECT * FROM citas WHERE ct_estado IN(0,1) AND ct_horaCitaDe <= '$horasRow[hr_horaInt]' AND ct_horaCitaHasta >= '$horasRow[hr_horaInt]' AND ct_fechaInicio = '$fechaSeleccionada' AND (ct_idDoctor = '$doctorID' OR ct_idUnidad = '$unidadID') ORDER BY ct_horaCitaDe ASC ");
					if($citasSql->num_rows > 0){

						$horaInactiva = 'horaInactiva';

						$citasRow = $citasSql->fetch_assoc();
						$verPacienteRow = $con->query("SELECT IDPaciente, pc_nombres FROM pacientes WHERE IDPaciente = '$citasRow[ct_idPaciente]'")->fetch_assoc();
						$verSucursalRow = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$citasRow[ct_idSucursal]'")->fetch_assoc();
						$verUnidadaRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$citasRow[ct_idUnidad]'")->fetch_assoc();
						$classHora = 'class="tooltip top"';
						$tituloHora = '
							<b>Sucursal:</b> '.$verSucursalRow['sc_nombre'].'<br>
							<b>Unidad:</b> '.$verUnidadaRow['uo_nombre'].'<br>
							<b>Paciente:</b> '.$verPacienteRow['pc_nombres'].'<br>
							<b>Duración:</b> '.$citasRow['ct_duracion'].' minutos';
					}

				?>
					<label <?php echo $classHora ?> >

						<a id="<?php echo $horasRow['hr_horaInt'] ?>" 
						hora="<?php echo $horasRow['hr_hora'] ?>"
						horaHasta="<?php echo $atencionHasta ?>"
						fecha="<?php echo $fechaSeleccionada ?>"
						anno="<?php echo $fechaParaGuardarAno ?>"
						mes="<?php echo $fechaParaGuardarMes ?>"
						dia="<?php echo $fechaParaGuardarDia ?>"
						sucursal="<?php echo $sucursalID ?>"
						unidad="<?php echo $unidadID ?>"
						doctor="<?php echo $doctorID ?>"
						tratamiento="<?php echo $tratamientoID ?>"
						tipoTratamiento="<?php echo $tipoTratamiento ?>"
						tratamientoPrecio="<?php echo $tratamientoPrecio ?>"
						tratamientoPresupuesto="<?php echo $tratamientoPresupuesto ?>"
						paciente="<?php echo $pacienteID ?>"
						class="horaSelected <?php echo $horaInactiva ?>"
						cita="<?php echo $citaID ?>">
						
							<?php echo $horasRow['hr_hora'] ?>
							
						</a>
					  <span class="tiptext"><?php echo $tituloHora ?></span>
					</label>

				<?php } ?>
			</div>

			<div id="duracionCita"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '.horaSelected', function(){  
			var consultoriosId 			= $(this).attr("id");
			var consultoriosHora 		= $(this).attr("hora");
			var consultoriosHoraHasta 	= $(this).attr("horaHasta");
			var consultoriosFecha 		= $(this).attr("fecha");
			var consultoriosAnno 		= $(this).attr("anno");
			var consultoriosMes 		= $(this).attr("mes");
			var consultoriosDia 		= $(this).attr("dia");
			var sucursal 				= $(this).attr("sucursal");
			var unidad 					= $(this).attr("unidad");
			var doctor 					= $(this).attr("doctor");
			var tratamiento 			= $(this).attr("tratamiento");
			var tipoTratamiento			= $(this).attr("tipoTratamiento");
			var tratamientoPrecio 			= $(this).attr("tratamientoPrecio");
			var tratamientoPresupuesto 			= $(this).attr("tratamientoPresupuesto");
			var paciente 				= $(this).attr("paciente");
			var citaID 					= $(this).attr("cita");
		    if(consultoriosId != '' && consultoriosFecha != '' && tratamiento > 0)
		    {  
		    	$.ajax({
		        	url:"cita-duracion.php",
		            method:"POST",  
		            data:{
		            	id:consultoriosId,
		            	hora:consultoriosHora,
		            	horaHasta:consultoriosHoraHasta,
		            	fecha:consultoriosFecha,
		            	fechaAnno:consultoriosAnno,
		            	fechaMes:consultoriosMes,
		            	fechaDia:consultoriosDia,
		            	sucursal:sucursal,
		            	unidad:unidad,
		            	doctor:doctor,
		            	tratamiento:tratamiento,
		            	tipoTratamiento:tipoTratamiento,
		            	tratamientoPrecio:tratamientoPrecio,
		            	tratamientoPresupuesto:tratamientoPresupuesto,
		            	paciente:paciente,
		            	citaID:citaID
		            },  
		            cache: false,
					success:function(data){  
						$('#duracionCita').html(data);
					}
		    	});  
			}

		});
	});
</script>