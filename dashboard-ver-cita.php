<?php include'config.php'; include'encrypt.php';

$citaID = $_POST['citaID'];

$citaSql = $con->query("SELECT pc_nombres, pc_telefonoFijo, pc_telefonoCelular, dc_nombres, sc_nombre, uo_nombre, tr_nombre, tr_idCups, ct_anoCita, ct_mesCita, ct_diaCita, ct_fechaInicio, ct_horaCita, ct_duracion, ct_nota, ct_evolucionada, ct_asistencia, ct_estado
	FROM citas AS ct
		INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
		INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
		INNER JOIN sucursales AS sc ON ct.ct_idSucursal = sc.IDSucursal
		INNER JOIN unidadesodontologicas AS uo ON ct.ct_idUnidad = uo.IDUnidadOdontologica
		INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
		WHERE ct.IDCita = $citaID")->fetch_assoc();

	if($citaSql['pc_telefonoCelular']>0){
        $pacienteTelefono = $citaSql['pc_telefonoCelular'];
    } else {
        $pacienteTelefono = $citaSql['pc_telefonoFijo'];
    }

	if($citaSql['tr_idCups']!=0){
		$cups = $con->query("SELECT cup_codigo FROM cups WHERE IDCups = '$citaSql[tr_idCups]'")->fetch_assoc();
		$cup = $cups['cup_codigo'].' | ';
	}

	$citaHoraHasta = strtotime ( '+'.$citaSql['ct_duracion'].'minute' , strtotime ( $citaSql['ct_horaCita'] ) ) ;
	$citaHoraHastaFull = date ( 'H:i' , $citaHoraHasta);

	if( $citaSql['ct_asistencia']==2){ $citaEstado = ' - Realizada'; }
		elseif( $citaSql['ct_asistencia']==1){ $citaEstado = ' - Sin asistencia'; }
		elseif( $citaSql['ct_evolucionada']==0 && ($citaSql['ct_fechaInicio'].str_replace(':','',$citaSql['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){ $citaEstado = ' - Sin evolución '; }
		elseif( $citaSql['ct_estado']==1){ $citaEstado = ' - Confirmada'; }
		else { $citaEstado = ' - Creada'; }

?>
<style type="text/css">
	.tableList tbody th { text-align: left; }
	.content-2d{
		display: flex;
		flex-direction: row;
		justify-content: space-around;
		text-align: center;
	}
</style>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Información Cita <?= $citaEstado ?></h4>
</div>
<div class="form">
<div class="modal-body divForm">

	<div class="containerPart titulo tituloSecundario">
		<span></span>
		<a href="cita-pdf.php?q=<?= encrypt( 'id='.$citaID ) ?>"><i class="fa fa-download"></i>Descargar</a>
	</div>

	<table class="tableList tableListAuto tableListTop">
		<tr>
			<th class="columnaCorta">Paciente</th>
			<td><?php echo $citaSql['pc_nombres'] ?></td>
		</tr>
		<tr>
			<th class="columnaCorta">Teléfono</th>
			<td><?php echo $pacienteTelefono ?></td>
		</tr>
		<tr>
			<th>Sucursal</th>
			<td><?php echo $citaSql['sc_nombre'] ?></td>
		</tr>
		<tr>
			<th>Unidad</th>
			<td><?php echo $citaSql['uo_nombre'] ?></td>
		</tr>
		<tr>
			<th>Doctor</th>
			<td><?php echo $citaSql['dc_nombres'] ?></td>
		</tr>
		<tr>
			<th>Tratamiento</th>
			<td><?php echo $cup.$citaSql['tr_nombre'] ?></td>
		</tr>
		<tr>
			<th>Fecha</th>
			<td><?php echo $citaSql['ct_anoCita'].'/'.$citaSql['ct_mesCita'].'/'.$citaSql['ct_diaCita'] ?></td>
		</tr>
		<tr>
			<th>Hora</th>
			<td><?php echo $citaSql['ct_horaCita'].' - '.$citaHoraHastaFull ?></td>
		</tr>
		<tr>
			<th>Duración</th>
			<td><?php echo $citaSql['ct_duracion'].' minutos' ?></td>
		</tr>
		<tr>
			<th>Motivo consulta</th>
			<td><?php echo $citaSql['ct_nota'] ?></td>
		</tr>
	</table>
	<p>&nbsp</p>
	<div class="content-2d">
	<?php
		if( $citaSql['ct_fechaInicio'].str_replace(':','',$citaSql['ct_horaCita'])>=$fechaHoySinEsp.date('Hi') ){ ?>
		
			<?php if($citaSql['ct_evolucionada']==0){
				if($citaSql['ct_estado']==0){
			?>
					<a class="estadoCita boton boton-warning" data-action="0" data-id="<?= $citaID ?>">Cancelar Cita</a>
					<a class="estadoCita boton boton-primario" data-action="1" data-id="<?= $citaID ?>">Confirmar Cita</a>
			<?php
				} else if($citaSql['ct_estado']==1){
			?>
					<a class="estadoCita boton boton-warning" data-action="0" data-id="<?= $citaID ?>">Cancelar Cita</a>
			<?php
				}

			}
			?>
	<?php } ?>	
		<a class="boton boton-secundario btn_dash_concentimiento" data-id="<?= $citaID ?>">Consentimiento</a>
	</div>
	
</div>
   
</div>

<script type="text/javascript">
	$('.estadoCita').attr('data-date', $( "#dashComparativoFechaInput" ).val() );
	$('.btn_cita_concentimiento').attr('data-date', $( "#dashComparativoFechaInput" ).val() );
</script>