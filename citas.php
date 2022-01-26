<?php include'config.php'; include 'pagination-modal-params.php';

/*if($sessionRol==2 || $sessionRol==5){
	$sucursalName = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$sucursalNameID'")->fetch_assoc();
}*/

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>

<body>
	<div class="contenedorPrincipal">

		<div id="msj" class="contenedorAlerta"><?php include'mensajes.php'; ?></div>
		<div id="msj-evolucion" class="contenedorAlerta"></div>

	<div class="contenedorTabs">
		<input id="tab-1" type="radio" name="tab-group" checked />
		<label for="tab-1" class="labelTab">Citas pendientes</label>
		<input id="tab-2" type="radio" name="tab-group" />
		<label for="tab-2" class="labelTab">Citas sin evolución</label>
		<input id="tab-3" type="radio" name="tab-group" />
		<label for="tab-3" class="labelTab">Histórico citas</label>

		<div class="contenidoTab">
			<div id="content-1">

<?php
if($sessionRol==1){
	$citasPendientesQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==2){
	$citasPendientesQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==3){
	$citasPendientesQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==5){
	$userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
	$citasPendientesQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_estado IN (0,1) AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
}

$citasPendientesSql = $con->query($citasPendientesQuery);

$numeroCitasPendientes = $citasPendientesSql->num_rows;

				$pagConfig = array(
			        'totalRows' => $numeroCitasPendientes,
			        'perPage' => $numeroResultados,
			        'link_func' => 'paginationCitasPendientes'
			    );
    $pagination =  new Pagination($pagConfig);

$citasPendientesSql = $con->query($citasPendientesQuery." LIMIT $numeroResultados");
?>
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countPendientes">Cantidad: [<?php echo $numeroCitasPendientes ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListPendientes" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCitasPendientes();">
					</span>
				</div>

				<div id="showResultsCitasPendientes">
					<table class="tableList">
						<thead>
							<tr>
								<th class="estado">&nbsp</th>
								<th class="columnaCorta">Fecha de Cita</th>
								<th colspan="2">Paciente</th>
							<?php if($sessionRol==1||$sessionRol==3){ ?><th>Sucursal | Unidad</th><?php } ?>
							<?php if($sessionRol!=3){ ?><th colspan="2">Doctor</th><?php } ?>
								<th>Tratamiento</th>
								<th class="columnaTCita">&nbsp</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while($citasRow = $citasPendientesSql->fetch_assoc()){

								$estadoEvolucion = 'iconGray';
								
								if( $citasRow['ct_estado']==2){
                                    $titleEstado = 'Cancelada';
                                    $estadoCita = ' estadoCancelado ';
                                    $estadoEvolucion = 'icon-cancelada'; }
                                else
                                if( $citasRow['ct_asistencia']==2){
                                    $titleEstado = 'Realizada';
                                    $estadoCita = ' cita-realizada ';
                                    $estadoEvolucion = 'icon-realizada'; }
                                else
                                if( $citasRow['ct_asistencia']==1){
                                    $titleEstado = 'Sin asistencia';
                                    $estadoCita = ' cita-sinasistencia ';
                                    $estadoEvolucion = 'icon-sinasistencia'; }
                                else
                                if( $citasRow['ct_evolucionada']==0 && ($citasRow['ct_fechaInicio'].str_replace(':','',$citasRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                    $titleEstado = 'Sin evolución';
                                    $estadoCita = ' cita-sinevolucion ';
                                    $estadoEvolucion = 'icon-sinevolucion'; }
                                else
                                if( $citasRow['ct_estado']==1){
                                    $titleEstado = 'Confirmada';
                                    $estadoCita = ' cita-confirmada ';
                                    $estadoEvolucion = 'icon-confirmada'; }
                                else {
                                    $titleEstado = 'Creada';
                                    $estadoCita = ' cita-creada ';
                                    $estadoEvolucion = 'icon-creada'; }


								$pacienteUrl = str_replace(" ","-", $citasRow['pc_nombres']);

								$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].' '.$citasRow['ct_horaCita'];

								if($citasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
								} else { $iSC = ''; $cSC = ''; }

								if($citasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
								} else { $iDC = ''; $cDC = ''; }

								if($citasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
								} else { $iTR = ''; $cTR = ''; }

								if($citasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
							    else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

							    $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$citasRow[ct_idUnidad]'")->fetch_assoc();

							?>
							<tr>
								<td class="estado <?php echo $estadoCita ?>" title="<?= $titleEstado ?>"></td>
							    <td class="columnaCorta"><?php echo $fechaCita ?></td>
							    <td class="imgUser">
							    	<?php
							    	if(file_exists( $citasRow['pc_foto'] )){ echo "<img src='$citasRow[pc_foto]'>"; }
							    	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td><a id="<?php echo $citasRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasRow['pc_nombres']; ?></a></td>
							<?php if($sessionRol==1||$sessionRol==3){ ?>
								<td class="<?php echo $cSC ?>"><?php echo $iSC.$citasRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td><?php } ?>
							<?php if($sessionRol!=3){ ?>
								<td class="imgUser <?php echo $cDC ?>">
									<?php
							    	if(file_exists( $citasRow['dc_foto'] )){ echo "<img src='$citasRow[dc_foto]'>"; }
							    	else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasRow['dc_nombres']; ?></td><?php } ?>
							    <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasRow['tr_nombre']; ?></td>
							    <td class="columnaTCita"><?php echo $tipoCita ?></td>
							    <td class="tableOption">
							    <?php if($citasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
							    	<a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $citasRow["IDCita"] ?>&id=<?php echo $citasRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
							    <?php } elseif($citasRow['ct_estado'] < 2 ) { ?>
                                    <a title="<?= $titleEstado ?>" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
                                <?php } ?>
                                <a title="Información Cita" data-id="<?php echo $citasRow['IDCita'] ?>" data-div="showResultsCitasPendientes" data-site="ct_pendientes" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
							    </td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>



			<div id="content-2">
				
<?php $hoyHora_int = (int) date('Hi');
if($sessionRol==1){
	$citasSinEvoluQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==2){
	$citasSinEvoluQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==3){
	$citasSinEvoluQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==5){
	$userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
	$citasSinEvoluQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND citas.ct_fechaInicio<='$fechaHoySinEsp' AND citas.ct_horaCitaDe<='$hoyHora_int' AND citas.ct_evolucionada = '0' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
}

$citasSinEvoluSql = $con->query($citasSinEvoluQuery);

$numeroCitasSinEvolu = $citasSinEvoluSql->num_rows;

				$pagConfig = array(
			        'totalRows' => $numeroCitasSinEvolu,
			        'perPage' => $numeroResultados,
			        'link_func' => 'paginationCitasSinEvolu'
			    );
    $pagination =  new Pagination($pagConfig);

$citasSinEvoluSql = $con->query($citasSinEvoluQuery." LIMIT $numeroResultados");
?>

				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countSinEvolucion">Cantidad: [<?php echo $numeroCitasSinEvolu ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListSinEvolu" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCitasSinEvolu();">
					</span>
				</div>

				<div id="showResultsCitasSinEvolu">
					<table class="tableList">
						<thead>
							<tr>
								<th class="estado">&nbsp</th>
								<th class="columnaCorta">Fecha de Cita</th>
								<th colspan="2">Paciente</th>
							<?php if($sessionRol==1||$sessionRol==3){ ?><th>Sucursal | Unidad</th><?php } ?>
							<?php if($sessionRol!=3){ ?><th colspan="2">Doctor</th><?php } ?>
								<th>Tratamiento</th>
								<th class="columnaTCita">&nbsp</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while($citasRow = $citasSinEvoluSql->fetch_assoc()){								

								$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].' '.$citasRow['ct_horaCita'];

								if($citasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
								} else { $iSC = ''; $cSC = ''; }

								if($citasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
								} else { $iDC = ''; $cDC = ''; }

								if($citasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
								} else { $iTR = ''; $cTR = ''; }

								if($citasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
							    else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

							    $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$citasRow[ct_idUnidad]'")->fetch_assoc();

							?>
							<tr>
								<td class="estado cita-sinevolucion" title="Sin evolución"></td>
							    <td class="columnaCorta"><?php echo $fechaCita ?></td>
							    <td class="imgUser">
							    	<?php
							    	if(file_exists( $citasRow['pc_foto'] )){ echo "<img src='$citasRow[pc_foto]'>"; }
							    	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td><a id="<?php echo $citasRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasRow['pc_nombres']; ?></a></td>
							<?php if($sessionRol==1||$sessionRol==3){ ?>
								<td class="<?php echo $cSC ?>"><?php echo $iSC.$citasRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td><?php } ?>
							<?php if($sessionRol!=3){ ?>
								<td class="imgUser <?php echo $cDC ?>">
									<?php
							    	if(file_exists( $citasRow['dc_foto'] )){ echo "<img src='$citasRow[dc_foto]'>"; }
							    	else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasRow['dc_nombres']; ?></td><?php } ?>
							    <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasRow['tr_nombre']; ?></td>
							    <td class="columnaTCita"><?php echo $tipoCita ?></td>
							    <td class="tableOption">
							    <?php if($citasRow['ct_fechaOrden'] <= $fechaEvolucionCita){ ?>
							    	<a title="Sin evolución" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion icon-sinevolucion"><i class="fa fa-share-alt"></i></a>
							    <?php } ?>
							    <a title="Información Cita" data-id="<?php echo $citasRow['IDCita'] ?>" data-div="showResultsCitasSinEvolu" data-site="ct_sinevolucion" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
							    </td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>



			<div id="content-3">
				
<?php 
if($sessionRol==1){
	$citasHistoricoQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND (citas.ct_fechaInicio BETWEEN $fechaMesInicio AND $fechaHoySinEsp) AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==2){
	$citasHistoricoQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND (citas.ct_fechaInicio BETWEEN $fechaMesInicio AND $fechaHoySinEsp) AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==3){
	$citasHistoricoQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND (citas.ct_fechaInicio BETWEEN $fechaMesInicio AND $fechaHoySinEsp) AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==5){
	$userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
	$citasHistoricoQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND (citas.ct_fechaInicio BETWEEN $fechaMesInicio AND $fechaHoySinEsp) AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
}

$citasHistoricoSql = $con->query($citasHistoricoQuery);

$numeroCitasHistorico = $citasHistoricoSql->num_rows;

				$pagConfig = array(
			        'totalRows' => $numeroCitasHistorico,
			        'perPage' => $numeroResultados,
			        'link_func' => 'paginationCitasHistorico'
			    );
    $pagination =  new Pagination($pagConfig);

$citasHistoricoSql = $con->query($citasHistoricoQuery." LIMIT $numeroResultados");
?>

				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countHistorico">Cantidad: [<?php echo $numeroCitasHistorico ?>]</span></div>
					<div class="titulo_optional_search form">
						<a class="consultorioDescargar" data-page="citas_historico" data-rango-de="citasHistRangoDe" data-rango-hasta="citasHistRangoHasta"><i class="fa fa-download"></i>Descargar</a>
						<input type="date" id="citasHistRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationCitasHistorico();" value="<?= date('Y-m-01') ?>">
						<input type="date" id="citasHistRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationCitasHistorico();" value="<?= date('Y-m-d') ?>">
					</div>
					<!--
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListSinEvolu" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCitasHistorico();">
					</span>
					-->
				</div>

				<div id="showResultsCitasHistorico">
					<table class="tableList">
						<thead>
							<tr>
								<th class="estado">&nbsp</th>
								<th class="columnaCorta">Fecha de Cita</th>
								<th colspan="2">Paciente</th>
							<?php if($sessionRol==1||$sessionRol==3){ ?><th>Sucursal | Unidad</th><?php } ?>
							<?php if($sessionRol!=3){ ?><th colspan="2">Doctor</th><?php } ?>
								<th>Tratamiento</th>
								<th class="columnaTCita">&nbsp</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while($citasRow = $citasHistoricoSql->fetch_assoc()){
								
								$estadoEvolucion = 'iconGray';
								
								if( $citasRow['ct_estado']==2){
                                    $titleEstado = 'Cancelada';
                                    $estadoCita = ' estadoCancelado ';
                                    $estadoEvolucion = 'icon-cancelada'; }
                                else
                                if( $citasRow['ct_asistencia']==2){
                                    $titleEstado = 'Realizada';
                                    $estadoCita = ' cita-realizada ';
                                    $estadoEvolucion = 'icon-realizada'; }
                                else
                                if( $citasRow['ct_asistencia']==1){
                                    $titleEstado = 'Sin asistencia';
                                    $estadoCita = ' cita-sinasistencia ';
                                    $estadoEvolucion = 'icon-sinasistencia'; }
                                else
                                if( $citasRow['ct_evolucionada']==0 && ($citasRow['ct_fechaInicio'].str_replace(':','',$citasRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                    $titleEstado = 'Sin evolución';
                                    $estadoCita = ' cita-sinevolucion ';
                                    $estadoEvolucion = 'icon-sinevolucion'; }
                                else
                                if( $citasRow['ct_estado']==1){
                                    $titleEstado = 'Confirmada';
                                    $estadoCita = ' cita-confirmada ';
                                    $estadoEvolucion = 'icon-confirmada'; }
                                else {
                                    $titleEstado = 'Creada';
                                    $estadoCita = ' cita-creada ';
                                    $estadoEvolucion = 'icon-creada'; }


								$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].' '.$citasRow['ct_horaCita'];

								if($citasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
								} else { $iSC = ''; $cSC = ''; }

								if($citasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
								} else { $iDC = ''; $cDC = ''; }

								if($citasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
								} else { $iTR = ''; $cTR = ''; }

								if($citasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
							    else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

							    $unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$citasRow[ct_idUnidad]'")->fetch_assoc();

							?>
							<tr>
								<td class="estado <?php echo $estadoCita ?>" title="<?= $titleEstado ?>"></td>
							    <td class="columnaCorta"><?php echo $fechaCita ?></td>
							    <td class="imgUser">
							    	<?php
							    	if(file_exists( $citasRow['pc_foto'] )){ echo "<img src='$citasRow[pc_foto]'>"; }
							    	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td><a id="<?php echo $citasRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasRow['pc_nombres']; ?></a></td>
							<?php if($sessionRol==1||$sessionRol==3){ ?>
								<td class="<?php echo $cSC ?>"><?php echo $iSC.$citasRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td><?php } ?>
							<?php if($sessionRol!=3){ ?>
								<td class="imgUser <?php echo $cDC ?>">
									<?php
							    	if(file_exists( $citasRow['dc_foto'] )){ echo "<img src='$citasRow[dc_foto]'>"; }
							    	else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasRow['dc_nombres']; ?></td><?php } ?>
							    <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasRow['tr_nombre']; ?></td>
							    <td class="columnaTCita"><?php echo $tipoCita ?></td>
							    <td class="tableOption">
							    <?php if($citasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $citasRow["IDCita"] ?>&id=<?php echo $citasRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                    <?php } elseif($citasRow['ct_estado'] < 2) { ?>
                                        <a title="<?= $titleEstado ?>" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
                                    <?php } ?>
                                    <a title="Información Cita" data-id="<?php echo $citasRow['IDCita'] ?>" data-div="showResultsCitasHistorico" data-site="ct_historico" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
							    </td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>

		</div>

	</div>

		
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>


 	<script type="text/javascript" src="js/label.js"></script>
	<script type="text/javascript">

		function paginationCitasPendientes(page_num) {
		    page_num = page_num?page_num:0;
		    var busquedaPendientes = $('#searchListPendientes').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/citasPendientesData.php',
		        data:'page='+page_num+'&busqueda='+busquedaPendientes,
		        success: function (html) {
		            $('#showResultsCitasPendientes').html(html);
		        }
		    });
		}
		function paginationCitasSinEvolu(page_num) {
		    page_num = page_num?page_num:0;
		    var busquedaSinEvolu = $('#searchListSinEvolu').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/citasSinEvoluData.php',
		        data:'page='+page_num+'&busqueda='+busquedaSinEvolu,
		        success: function (html) {
		            $('#showResultsCitasSinEvolu').html(html);
		        }
		    });
		}
		function paginationCitasHistorico(page_num) {
		    page_num = page_num?page_num:0;
		    var busquedaDe = $('#citasHistRangoDe').val();
		    var busquedaHasta = $('#citasHistRangoHasta').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/citasHistoricoData.php',
		        data:'page='+page_num+'&de='+busquedaDe+'&hasta='+busquedaHasta,
		        success: function (html) {
		            $('#showResultsCitasHistorico').html(html);
		        }
		    });
		}


		$(document).on('click', '.consultorioEliminar', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosT = $(this).attr("t");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"desactivar.php",
		            method:"POST",  
		            data:{id:consultoriosId,t:consultoriosT},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

		$(document).on('click', '.consultoriosEvolucion', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosEv = 1;
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"cita-evolucionar.php",
		            method:"POST",  
		            data:{id:consultoriosId,ev:consultoriosEv},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

		$(document).on('click', '.guardarMedicamento', function(){  
			var valVadecum = $('#vadecum').val();
			var cantMedicamento = $('#cantidadMedicamento').val();
			var medicamentoCitaID = $('#medicamentoCitaID').val();
		    if(valVadecum != 0 && cantMedicamento>0)
		    {
		    	$.ajax({
		        	url:"cita-medicamentos-guardar.php",
			        method:"POST",
		            data:{valVadecum:valVadecum,cantMedicamento:cantMedicamento,citaID:medicamentoCitaID}, 
			        success:function(data){  
						$('#listMedicamentos').html(data);
					}
			    });  
			}   
		});

		$(document).on('click', '.consultorioEditarPaciente', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"paciente.php",
			        method:"POST",
		            data:{id:consultoriosId}, 
			        success:function(data){  
						$('.contenedorPrincipal').html(data);  
						//$('#consultoriosModal').modal('show');  
					}
			    });  
			}         
		});
	</script>
</body>
</html>