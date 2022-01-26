<?php include'config.php';

$id = $_POST['id'];
$unidadID = $_POST['unidad'];

$unidadRow = $con->query("SELECT uo_nombre, sc_atencionDe, sc_atencionHasta FROM unidadesodontologicas, sucursales WHERE unidadesodontologicas.uo_idSucursal = sucursales.IDSucursal AND unidadesodontologicas.IDUnidadOdontologica = '$unidadID'")->fetch_assoc();

		$atencionDe = str_replace(":","",$unidadRow['sc_atencionDe']);
		$atencionHasta = str_replace(":","",$unidadRow['sc_atencionHasta']);
	

$fechaMostrar = $id;
$fechaSeleccionada = str_replace("/","",$id);

$fechaParaMostrar = strtotime ( $fechaMostrar ) ;
	
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

$horasSql = $con->query("SELECT * FROM horas WHERE hr_horaInt >= '$atencionDe' AND hr_horaInt < '$atencionHasta' ORDER BY IDHora");

?>
<style type="text/css">
	.contenedorAgenda #horasCalendario {
		display: initial;
	}
</style>
	<div class="tituloFecha"><?php echo '<b>'.$fechaParaMostrarFull.' | Unidad: '.$unidadRow['uo_nombre'].'</b>'; ?></div>
			<div class="contenedorHoras">
				<?php while($horasRow = $horasSql->fetch_assoc()){
					$horaInactiva = '';
					$tituloHora = '';
					$classHora = '';

					$citasSql = $con->query("SELECT * FROM citas WHERE ct_estado<='1' AND ct_horaCitaDe <= '$horasRow[hr_horaInt]' AND ct_horaCitaHasta >= '$horasRow[hr_horaInt]' AND ct_fechaInicio = '$fechaSeleccionada' AND ct_idUnidad = '$unidadID'");
					if($citasSql->num_rows > 0){

						$horaInactiva = 'horaInactiva';

						$citasRow = $citasSql->fetch_assoc();
						$verPacienteRow = $con->query("SELECT IDPaciente, pc_nombres FROM pacientes WHERE IDPaciente = '$citasRow[ct_idPaciente]'")->fetch_assoc();
						$verSucursalRow = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$citasRow[ct_idSucursal]'")->fetch_assoc();
						$verDoctorRow = $con->query("SELECT IDDoctor, dc_nombres FROM doctores WHERE IDDoctor = '$citasRow[ct_idDoctor]'")->fetch_assoc();
						$classHora = 'class="tooltip top"';
						$tituloHora = '
						<b>Sucursal:</b> '.$verSucursalRow['sc_nombre'].'<br>
						<b>Unidad:</b> '.$unidadRow['uo_nombre'].'<br>
						<b>Doctor:</b> '.$verDoctorRow['dc_nombres'].'<br>
						<b>Paciente:</b> '.$verPacienteRow['pc_nombres'].'<br>
						<b>Duración:</b> '.$citasRow['ct_duracion'].' minutos';
					}

				?>
					<label <?php echo $classHora ?> >

						<span class="<?php echo $horaInactiva ?>">
						
							<?php echo $horasRow['hr_hora'] ?>
							
						</span>
					  <span class="tiptext"><?php echo $tituloHora ?></span>
					</label>

				<?php } ?>
			</div>