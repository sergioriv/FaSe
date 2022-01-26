<?php include'config.php';
include'date.php';
include'pagination-modal-params.php';
include'actualizar-estados.php';

//$fechaActualVal = date('Y/m/d');

function __substr($var){
	$substr = $var;
	if( strlen( $var ) > 22 ){ $substr = rtrim( substr($var, 0, 22) ).'...'; }
	return $substr;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php' ?>
	<link href="css/calendar.min.css" rel="stylesheet">

</head>

<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

	<div class="contenedorTabs">
		<input id="tab-1" type="radio" name="tab-group"  />
		<label for="tab-1" class="labelTab">Consultas</label>
		<input id="tab-2" type="radio" name="tab-group" />
		<label for="tab-2" class="labelTab">Estadisticas</label>
		<input id="tab-3" type="radio" name="tab-group" />
		<label for="tab-3" class="labelTab">Avisos</label>
		<input id="tab-4" type="radio" name="tab-group"/>
		<label for="tab-4" class="labelTab">Tareas</label>
		<input id="tab-5" type="radio" name="tab-group" checked/>
		<label for="tab-5" class="labelTab">Agenda</label>
		<input id="tab-5" type="radio" name="tab-group"/>
		<label for="tab-5" class="labelTab titulo tituloSecundario"><a class="consultorioNuevoPaciente"><?php echo $iconoNuevo ?>Nuevo Paciente</a></label>

		<div class="contenidoTab">
			<div id="content-1" class="contenedorDashboard">

				<div class="caja-cont caja caja-content-calendar">
					<div class="titulo">Horario unidad</div>
					<div class="caja-grid form">
						<select id="sucursalDashboard" class="formulario__input" data-label="Sucursal" required>
							<option selected hidden value="">-- Seleccionar --</option>
								<?php
									$sucursalesSelectSql = $con->query("SELECT * FROM sucursales WHERE sc_idClinica = '$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");
					            	while($sucursalesSelectRow = $sucursalesSelectSql->fetch_assoc()){

					            		$sucursalDisabled = '';
					            		$sucursalDisabledMsj = '';
					            		$countUnidades = $con->query("SELECT COUNT(*) AS cantidad FROM unidadesodontologicas WHERE uo_idSucursal = '$sucursalesSelectRow[IDSucursal]' AND uo_estado = 1")->fetch_assoc();
					            		if($countUnidades['cantidad'] == 0){
					            			$sucursalDisabled = 'disabled';
					            			$sucursalDisabledMsj = ' <i>(sin unidades)</i>';
					            		}
					            		echo "<option value=".$sucursalesSelectRow['IDSucursal']." $sucursalDisabled>".$sucursalesSelectRow['sc_nombre'].$sucursalDisabledMsj."</option>";	
									}
					            ?>
						</select>
						<select id="unidadDashboard" class="formulario__input" data-label="Unidad" required>
				        	<option selected hidden value="">-- Seleccionar Sucursal --</option>
				        </select>
					</div>
					<div class="contenedorAgenda">
						<div class="agendaCalendario">
							<div class="calendar" data-color="normal"></div>
						</div>
						<div id="horasCalendario">
							<div class="contenedorHoras">
								<?php $horasSql = $con->query("SELECT * FROM horas ORDER BY IDHora");
									while($horasRow = $horasSql->fetch_assoc()){
								?>
									<label>
										<span class="horaInactiva">
											<?php echo $horasRow['hr_hora'] ?>
										</span>
									</label>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="caja-cont caja">
					<div class="titulo">Citas de doctores</div>	
					<div class="caja-grid">
						<?php $dashDoctorHoyQuery = "SELECT IDDoctor, dc_nombres FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado=1 ORDER BY dc_nombres ASC";

							$dashRowCountDoctorHoy = $con->query($dashDoctorHoyQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $dashRowCountDoctorHoy,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashDoctorHoy'
								);
								$pagination =  new Pagination($pagConfig);

							$dashDoctorHoySql = $con->query($dashDoctorHoyQuery." LIMIT $numeroResultados");
						?>
						<div class="caja-cont caja">
							<div class="titulo">Hoy</div>
							<div id="showResultsDashDoctorHoy">
								<table class="tableList">
									<thead>
										<tr>
											<th>Cant.</th>
											<th>Nombre</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dashDoctorHoyRow = $dashDoctorHoySql->fetch_assoc()){
											$dashCtDoctorHoy = $con->query("SELECT COUNT(*) AS cantCitas FROM citas WHERE ct_idDoctor='$dashDoctorHoyRow[IDDoctor]' AND ct_estado IN(0,1) AND ct_fechaInicio='$fechaHoySinEsp'")->fetch_assoc();
										?>
											<tr>
												<td align="center"><?php echo $dashCtDoctorHoy['cantCitas'] ?></td>
												<td><a id="<?php echo $dashDoctorHoyRow['IDDoctor'] ?>" class="consultorioDashEditar" data-page="doctor"><?php echo $dashDoctorHoyRow['dc_nombres'] ?></a></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>
						</div>

						<?php $dashDoctorMesQuery = "SELECT IDDoctor, dc_nombres FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado=1 ORDER BY dc_nombres ASC";

							$dashRowCountDoctorMes = $con->query($dashDoctorMesQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $dashRowCountDoctorMes,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashDoctorMes'
								);
								$pagination =  new Pagination($pagConfig);

							$dashDoctorMesSql = $con->query($dashDoctorMesQuery." LIMIT $numeroResultados");
						?>
						<div class="caja-cont caja">
							<div title="Click para Cambiar" class="titulo titulo-change" id="contDCMes"><?php echo $hoyMesMes ?></div>
							<div class="titulo tituloInput form" id="contSelectDCMes">
								<select class="formulario__input" id="selectDCMes">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
							<div class="info" id="listDCMes">
								<table class="tableList">
									<thead>
										<tr>
											<th>Cant.</th>
											<th>Nombre</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dashDoctorMesRow = $dashDoctorMesSql->fetch_assoc()){
											$dashCtDoctorMes = $con->query("SELECT COUNT(*) AS cantCitas FROM citas WHERE ct_idDoctor='$dashDoctorMesRow[IDDoctor]' AND ct_estado IN(0,1) AND ct_anoCita='$hoyAno' AND ct_mesCita='$hoyMes'")->fetch_assoc();
										?>
											<tr>
												<td align="center"><?php echo $dashCtDoctorMes['cantCitas'] ?></td>
												<td><a id="<?php echo $dashDoctorMesRow['IDDoctor'] ?>" class="consultorioDashEditar" data-page="doctor"><?php echo $dashDoctorMesRow['dc_nombres'] ?></a></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>
						</div>
					</div>
				</div>


				<div class="caja-cont caja">
					<div class="titulo">Ventas por Referente</div>

					<div class="cajas-grid">

						<div class="caja-cont caja">
							<?php $dashRefVnMesQuery = "SELECT SUM(ct.ct_costo) AS recaudo, vn.IDVendedor, vn.vn_nombre FROM vendedores AS vn
						        INNER JOIN pacientes AS pcref ON pcref.pc_idReferido = CONCAT('V-', vn.IDVendedor)
						        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
						        WHERE vn.vn_idClinica = '$sessionClinica' AND vn.vn_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$hoyMes' AND ct.ct_anoCita = '$hoyAno'
						        GROUP BY vn.IDVendedor
						        ORDER BY vn.vn_nombre ASC";

							$dashRowCountRefVnMes = $con->query($dashRefVnMesQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $dashRowCountRefVnMes,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashRefVn'
								);
								$pagination =  new Pagination($pagConfig);

							$dashRefVnMesSql = $con->query($dashRefVnMesQuery." LIMIT $numeroResultados");
							?>

							<div title="Click para Cambiar" class="titulo titulo-change" id="contRefVn"><?php echo $hoyMesMes ?> | Vendedores</div>
							<div class="titulo tituloInput form" id="contSelectRefVn">
								<select class="formulario__input" id="selectRefVn">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
								<div class="info" id="listRefVn">
									<table class="tableList">
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php while($dashRefVnMesRow = $dashRefVnMesSql->fetch_assoc()){

											?>
												<tr>
													<td><?= $dashRefVnMesRow['vn_nombre'] ?></td>
													<td align="right"><?= '$'.number_format($dashRefVnMesRow['recaudo'], 0, ".", ","); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
									<?php echo $pagination->createLinks(); ?>
								</div>
						</div>


						<div class="caja-cont caja">
							<?php $dashRefDcMesQuery = "SELECT SUM(ct.ct_costo) AS recaudo, dc.IDDoctor, dc.dc_nombres FROM doctores AS dc
						        INNER JOIN pacientes AS pcref ON pcref.pc_idReferido = CONCAT('D-', dc.IDDoctor)
						        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
						        WHERE dc.dc_idClinica = '$sessionClinica' AND dc.dc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$hoyMes' AND ct.ct_anoCita = '$hoyAno'
						        GROUP BY dc.IDDoctor
						        ORDER BY dc.dc_nombres ASC";

							$dashRowCountRefDcMes = $con->query($dashRefDcMesQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $dashRowCountRefDcMes,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashRefDc'
								);
								$pagination =  new Pagination($pagConfig);

							$dashRefDcMesSql = $con->query($dashRefDcMesQuery." LIMIT $numeroResultados");
							?>

							<div title="Click para Cambiar" class="titulo titulo-change" id="contRefDc"><?php echo $hoyMesMes ?> | Doctores</div>
							<div class="titulo tituloInput form" id="contSelectRefDc">
								<select class="formulario__input" id="selectRefDc">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
								<div class="info" id="listRefDc">
									<table class="tableList">
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php while($dashRefDcMesRow = $dashRefDcMesSql->fetch_assoc()){
												if( $dashRefDcMesRow['IDDoctor'] ){	
											?>
												<tr>
													<td><?= $dashRefDcMesRow['dc_nombres'] ?></td>
													<td align="right"><?= '$'.number_format($dashRefDcMesRow['recaudo'], 0, ".", ","); ?></td>
												</tr>
											<?php }} ?>
										</tbody>
									</table>
									<?php echo $pagination->createLinks(); ?>
								</div>
						</div>
						

						<div class="caja-cont caja">
							<?php $dashRefPcMesQuery = "SELECT SUM(ct.ct_costo) AS recaudo, pc.IDPaciente, pc.pc_nombres FROM pacientes AS pc
						        INNER JOIN pacientes AS pcref ON pcref.pc_idReferido = CONCAT('P-', pc.IDPaciente)
						        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
						        WHERE pc.pc_idClinica = '$sessionClinica' AND pc.pc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$hoyMes' AND ct.ct_anoCita = '$hoyAno'
						        GROUP BY pc.IDPaciente
						        ORDER BY pc.pc_nombres ASC";

							$dashRowCountRefPcMes = $con->query($dashRefPcMesQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $dashRowCountRefPcMes,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashRefPc'
								);
								$pagination =  new Pagination($pagConfig);

							$dashRefPcMesSql = $con->query($dashRefPcMesQuery." LIMIT $numeroResultados");
							?>

							<div title="Click para Cambiar" class="titulo titulo-change" id="contRefPc"><?php echo $hoyMesMes ?> | Pacientes</div>
							<div class="titulo tituloInput form" id="contSelectRefPc">
								<select class="formulario__input" id="selectRefPc">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
								<div class="info" id="listRefPc">
									<table class="tableList">
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php while($dashRefPcMesRow = $dashRefPcMesSql->fetch_assoc()){

																		
											?>
												<tr>
													<td><?= $dashRefPcMesRow['pc_nombres'] ?></td>
													<td align="right"><?= '$'.number_format($refPaciente['recaudo'], 0, ".", ","); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
									<?php echo $pagination->createLinks(); ?>
								</div>
						</div>

					</div>
				</div>


				<div class="caja-cont caja">
					<div class="titulo">Ventas por Referencia de origen</div>

					<div class="cajas-grid">

						<div class="caja-cont caja">
							<?php $dashRefSocialMesSql = $con->query("SELECT SUM(ct.ct_costo) AS recaudo, ref.IDReferencia, ref.ref_nombre FROM referencias AS ref
						        INNER JOIN pacientes AS pcref ON pcref.pc_idReferencia = ref.IDReferencia
						        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
						        WHERE pcref.pc_idClinica = '$sessionClinica' AND pcref.pc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$hoyMes' AND ct.ct_anoCita = '$hoyAno' AND ref.IDReferencia IN(1,2,3,4) 
						        GROUP BY ref.IDReferencia
						        ORDER BY ref.ref_nombre ASC");
							?>

							<div title="Click para Cambiar" class="titulo titulo-change" id="contRefSocial"><?php echo $hoyMesMes ?> | Redes Sociales</div>
							<div class="titulo tituloInput form" id="contSelectRefSocial">
								<select class="formulario__input" id="selectRefSocial">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
								<div class="info" id="listRefSocial">
									<table class="tableList">
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php while($dashRefSocialMesRow = $dashRefSocialMesSql->fetch_assoc()){

											?>
												<tr>
													<td><?= $dashRefSocialMesRow['ref_nombre'] ?></td>
													<td align="right"><?= '$'.number_format($dashRefSocialMesRow['recaudo'], 0, ".", ","); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
						</div>


						<div class="caja-cont caja">
							<?php $dashRefCampMesSql = $con->query("SELECT SUM(ct.ct_costo) AS recaudo, ref.IDReferencia, ref.ref_nombre FROM referencias AS ref
						        INNER JOIN pacientes AS pcref ON pcref.pc_idReferencia = ref.IDReferencia
						        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
						        WHERE pcref.pc_idClinica = '$sessionClinica' AND pcref.pc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$hoyMes' AND ct.ct_anoCita = '$hoyAno' AND ref.IDReferencia IN(5,6,7,8,9) 
						        GROUP BY ref.IDReferencia
						        ORDER BY ref.ref_nombre ASC");
							?>

							<div title="Click para Cambiar" class="titulo titulo-change" id="contRefCamp"><?php echo $hoyMesMes ?> | Publicidad</div>
							<div class="titulo tituloInput form" id="contSelectRefCamp">
								<select class="formulario__input" id="selectRefCamp">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
								<div class="info" id="listRefCamp">
									<table class="tableList">
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php while($dashRefCampMesRow = $dashRefCampMesSql->fetch_assoc()){

											?>
												<tr>
													<td><?= $dashRefCampMesRow['ref_nombre'] ?></td>
													<td align="right"><?= '$'.number_format($dashRefCampMesRow['recaudo'], 0, ".", ","); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
						</div>


						<div class="caja-cont caja">
							<?php $dashRefOtrosMesSql = $con->query("SELECT SUM(ct.ct_costo) AS recaudo, ref.IDReferencia, ref.ref_nombre FROM referencias AS ref
						        INNER JOIN pacientes AS pcref ON pcref.pc_idReferencia = ref.IDReferencia
						        INNER JOIN citas AS ct ON ct.ct_idPaciente = pcref.IDPaciente
						        WHERE pcref.pc_idClinica = '$sessionClinica' AND pcref.pc_estado = '1' AND ct.ct_inicial = '1' AND ct.ct_mesCita = '$hoyMes' AND ct.ct_anoCita = '$hoyAno' AND ref.IDReferencia IN(10,11) 
						        GROUP BY ref.IDReferencia
						        ORDER BY ref.ref_nombre ASC");
							?>

							<div title="Click para Cambiar" class="titulo titulo-change" id="contRefOtros"><?php echo $hoyMesMes ?> | Eventos y Otros</div>
							<div class="titulo tituloInput form" id="contSelectRefOtros">
								<select class="formulario__input" id="selectRefOtros">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
								<div class="info" id="listRefOtros">
									<table class="tableList">
										<thead>
											<tr>
												<th>Nombre</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php while($dashRefOtrosMesRow = $dashRefOtrosMesSql->fetch_assoc()){

											?>
												<tr>
													<td><?= $dashRefOtrosMesRow['ref_nombre'] ?></td>
													<td align="right"><?= '$'.number_format($dashRefOtrosMesRow['recaudo'], 0, ".", ","); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
						</div>

					</div>
				</div>

				<div class="caja-grid-solo">
					<div class="caja-cont caja">
						<?php $citasAtendidasQuery = "SELECT 
								ct.ct_anoCita,
								ct.ct_mesCita,
								ct.ct_diaCita,
								pc.pc_nombres,
								dc.dc_nombres,
								tr.tr_nombre,
								ct.ct_costo,
								ct.ct_trataPorcentaje
									FROM citas AS ct
									INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
									INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
									INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
									WHERE ct.ct_idClinica = '$sessionClinica' AND ct.ct_evolucionada=1 AND ct.ct_estado IN(0,1) AND ct.ct_fechaInicio BETWEEN '$fechaMesInicio' AND '$fechaHoySinEsp' ORDER BY IDCita DESC";

							$dashRowCountCitasAtendidas = $con->query($citasAtendidasQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $dashRowCountCitasAtendidas,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashCitasAtendidas'
								);
								$pagination =  new Pagination($pagConfig);

							$dashCitasAtendidasSql = $con->query($citasAtendidasQuery." LIMIT $numeroResultados");
						?>
						<div class="titulo titulo-change" id="contCTatendidas">
							<div title="Click para Cambiar" id="contCTatendidasTitle">Citas atendidas <?= date('Y/m/01').' - '.date('Y/m/d') ?></div>
							<i title="Descargar" class="consultorioDescargar fa fa-download" data-page="citasAtendidas" data-rango-de="selectCTatendidas_de" data-rango-hasta="selectCTatendidas_hasta"></i>
						</div>
						<div class="titulo tituloInput form" id="contSelectCTatendidas">
							<div class="caja-grid">
								<input type="date" id="selectCTatendidas_de" class="formulario__input" value="<?= date('Y-m-01') ?>">
								<input type="date" id="selectCTatendidas_hasta" class="formulario__input" value="<?= date('Y-m-d') ?>">
							</div>
						</div>

						<div id="dashList_citasAtendidas">
							<table class="tableList">
								<thead>
									<tr>
										<th>Fecha de Cita</th>
										<th>Paciente</th>
										<th>Doctor</th>
										<th>Tratamiento</th>
										<th>Vr Total Tratamiento</th>
										<th>Valor cita</th>
									</tr>
								</thead>
								<tbody>
									<?php while($dashCitasAtendidasRow = $dashCitasAtendidasSql->fetch_assoc()){
										$valorCitaAtendidaOP = ( $dashCitasAtendidasRow['ct_costo'] * $dashCitasAtendidasRow['ct_trataPorcentaje'] ) / 100;

										$valorTRCitaAtendida = '$ '.number_format($dashCitasAtendidasRow['ct_costo'], 0, ".", ",");
										$valorCitaAtendida = '$ '.number_format($valorCitaAtendidaOP, 0, ".", ",");

									?>
										<tr>
											<td align="center" class="columnaCorta"><?= $dashCitasAtendidasRow['ct_anoCita'].'/'.$dashCitasAtendidasRow['ct_mesCita'].'/'.$dashCitasAtendidasRow['ct_diaCita'] ?></td>
											<td><?= $dashCitasAtendidasRow['pc_nombres'] ?></td>
											<td><?= $dashCitasAtendidasRow['dc_nombres'] ?></td>
											<td><?= $dashCitasAtendidasRow['tr_nombre'] ?></td>
											<td align="right"><?= $valorTRCitaAtendida ?></td>
											<td align="right"><?= '('.$dashCitasAtendidasRow['ct_trataPorcentaje'].'%) '.$valorCitaAtendida ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<?php echo $pagination->createLinks(); ?>
						</div>

					</div>
				</div>

			</div>


			<div id="content-2" class="contenedorDashboard">
				<div class="caja-cont caja">
					<div class="titulo">Recaudos por forma de pago</div>	
					<div class="cajas-grid">
						<div class="caja-cont caja">
							<div class="titulo titulo-change" id="contFPDia">
								<div title="Click para Cambiar" id="contFPDiaTitle">Hoy</div>
								<i title="Descargar" class="consultorioDescargar fa fa-download" data-page="recaudosDia" data-search="selectFPDia"></i>
							</div>
							<div class="titulo tituloInput form" id="contSelectFPDia">
								<input type="date" id="selectFPDia" class="formulario__input" value="<?= date('Y-m-d') ?>">
							</div>
							<div class="info" id="listFPDia">
								<?php $formasPagoFPSql = $con->query("SELECT * FROM fomaspago"); ?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoFPSql->fetch_assoc()){
											$abonosFP = $con->query("SELECT SUM(ab_abono) AS sumaFP FROM abonos WHERE ab_idClinica='$sessionClinica' AND ab_idFormaPago='$formasPagoRow[IDFormaPago]' AND ab_estado=1 AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='$hoyMes' AND DAY(ab_fechaCreacion)='$hoyDia'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosFP['sumaFP'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>	
						</div>
						<div class="caja-cont caja">
							<div title="Click para Cambiar" class="titulo titulo-change" id="contFPMes"><?php echo $hoyMesMes ?></div>
							<div class="titulo tituloInput form" id="contSelectFPMes">
								<select class="formulario__input" id="selectFPMes">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
							<div class="info" id="listFPMes">
								<?php $formasPagoFPSql = $con->query("SELECT * FROM fomaspago"); ?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoFPSql->fetch_assoc()){
											$abonosFP = $con->query("SELECT SUM(ab_abono) AS sumaFP FROM abonos WHERE ab_idClinica='$sessionClinica' AND ab_idFormaPago='$formasPagoRow[IDFormaPago]' AND ab_estado=1 AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='$hoyMes'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosFP['sumaFP'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="caja-cont caja">
							<div title="Click para Cambiar" class="titulo titulo-change" id="contFPAnio"><?php echo date('Y') ?></div>
							<div class="titulo tituloInput form" id="contSelectFPAnio">
								<select class="formulario__input" id="selectFPAnio">
									<option selected hidden value="">-- Seleccionar --</option>
									<?php 
										$file_anios = file_get_contents("extras/anios.json");
										$json_anios = json_decode($file_anios, true);

										foreach ($json_anios as $value) {
											echo "<option value=".$value['anio'].">".$value['anio']."</option>";
										}

									?>
								</select>
							</div>
							<div class="info" id="listFPAnio">
								<?php $formasPagoFPSql = $con->query("SELECT * FROM fomaspago"); ?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoFPSql->fetch_assoc()){
											$abonosFP = $con->query("SELECT SUM(ab_abono) AS sumaFP FROM abonos WHERE ab_idClinica='$sessionClinica' AND ab_idFormaPago='$formasPagoRow[IDFormaPago]' AND ab_estado=1 AND YEAR(ab_fechaCreacion)='$hoyAno'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosFP['sumaFP'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<div class="caja-cont caja">
					<div class="titulo">Comprobantes Egreso por forma de pago</div>	
					<div class="cajas-grid">
						<div class="caja-cont caja">
							<div class="titulo titulo-change" id="contCEDia">
								<div title="Click para Cambiar" id="contCEDiaTitle">Hoy</div>
								<i title="Descargar" class="consultorioDescargar fa fa-download" data-page="recaudosDiaCE" data-search="selectCEDia"></i>
							</div>
							<div class="titulo tituloInput form" id="contSelectCEDia">
								<input type="date" id="selectCEDia" class="formulario__input" value="<?= date('Y-m-d') ?>">
							</div>
							<div class="info" id="listCEDia">
								<?php $formasPagoCESql = $con->query("SELECT * FROM fomaspago"); ?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoCESql->fetch_assoc()){
											$abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$formasPagoRow[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$hoyAno' AND MONTH(pra_fechaCreacion)='$hoyMes' AND DAY(pra_fechaCreacion)='$hoyDia'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosCE['sumaCE'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>	
						</div>
						<div class="caja-cont caja">
							<div title="Click para Cambiar" class="titulo titulo-change" id="contCEMes"><?php echo $hoyMesMes ?></div>
							<div class="titulo tituloInput form" id="contSelectCEMes">
								<select class="formulario__input" id="selectCEMes">
									<option selected hidden value="">-- Seleccionar --</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
							<div class="info" id="listCEMes">
								<?php $formasPagoCESql = $con->query("SELECT * FROM fomaspago"); ?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoCESql->fetch_assoc()){
											$abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$formasPagoRow[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$hoyAno' AND MONTH(pra_fechaCreacion)='$hoyMes'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosCE['sumaCE'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="caja-cont caja">
							<div title="Click para Cambiar" class="titulo titulo-change" id="contCEAnio"><?php echo date('Y') ?></div>
							<div class="titulo tituloInput form" id="contSelectCEAnio">
								<select class="formulario__input" id="selectCEAnio">
									<option selected hidden value="">-- Seleccionar --</option>
									<?php 
										$file_anios = file_get_contents("extras/anios.json");
										$json_anios = json_decode($file_anios, true);

										foreach ($json_anios as $value) {
											echo "<option value=".$value['anio'].">".$value['anio']."</option>";
										}

									?>
								</select>
							</div>
							<div class="info" id="listCEAnio">
								<?php $formasPagoCESql = $con->query("SELECT * FROM fomaspago"); ?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoCESql->fetch_assoc()){
											$abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$formasPagoRow[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$hoyAno'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosCE['sumaCE'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<div class="caja-cont caja">
					<?php $dashSCQuery = "SELECT IDSucursal, sc_nombre FROM sucursales WHERE sc_idClinica='$sessionClinica' AND sc_estado=1 ORDER BY sc_nombre";

					$rowCountDashSC = $con->query($dashSCQuery)->num_rows;

						$pagConfig = array(
							'totalRows' => $rowCountDashSC,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationDashSucursales'
						);
						$pagination =  new Pagination($pagConfig);

					$dashSCSql = $con->query($dashSCQuery." LIMIT $numeroResultados");
					?>
					<div title="Click para Cambiar" class="titulo titulo-change" id="contSCMes">Consolidado <?php echo $hoyMesMes ?></div>
					<div class="titulo tituloInput form" id="contSelectSCMes">
						<select class="formulario__input" id="selectSCMes">
							<option selected hidden value="">-- Seleccionar --</option>
							<option value="01">Enero</option>
							<option value="02">Febrero</option>
							<option value="03">Marzo</option>
							<option value="04">Abril</option>
							<option value="05">Mayo</option>
							<option value="06">Junio</option>
							<option value="07">Julio</option>
							<option value="08">Agosto</option>
							<option value="09">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</div>
					<div id="dashListSCConsolidado">
						<table class="tableList">
							<thead>
								<tr>
									<th>Sucursal</th>
									<th>Citas</th>
									<th>Ventas</th>
									<th>Recaudos</th>
								</tr>
							</thead>
							<tbody>
								<?php while($dashSCRow = $dashSCSql->fetch_assoc()){

									$dashSCCitas = $con->query("SELECT COUNT(*) AS citasSC FROM citas WHERE ct_idSucursal='$dashSCRow[IDSucursal]' AND ct_anoCita='$hoyAno' AND ct_mesCita='$hoyMes' AND ct_estado IN(0,1)")->fetch_assoc();

									$dashSCVentas = $con->query("SELECT SUM(ct_costo) AS ventasSC FROM citas WHERE ct_idSucursal='$dashSCRow[IDSucursal]' AND ct_anoCita='$hoyAno' AND ct_mesCita='$hoyMes' AND ct_inicial='1' AND ct_estado IN(0,1)")->fetch_assoc();

									$dashSCRecaudos = $con->query("SELECT SUM(ab_abono) AS recaudosSC FROM abonos WHERE ab_idSucursal='$dashSCRow[IDSucursal]' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='$hoyMes' AND ab_estado='1'")->fetch_assoc();
								?>
									<tr>
										<td><a id="<?php echo $dashSCRow['IDSucursal'] ?>" class="consultorioDashEditar" data-page="sucursal"><?php echo $dashSCRow['sc_nombre'] ?></a></td>
										<td align="center"><?php echo $dashSCCitas['citasSC'] ?></td>
										<td align="right"><?php echo '$'.number_format($dashSCVentas['ventasSC'], 0, ".", ",") ?></td>
										<td align="right"><?php echo '$'.number_format($dashSCRecaudos['recaudosSC'], 0, ".", ",") ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>

				<div class="caja-grid">
					<div><canvas id="grafico1"></canvas></div>
					<div><canvas id="grafico2"></canvas></div>
					<div><canvas id="grafico3"></canvas></div>
					<div><canvas id="grafico4"></canvas></div>
				</div>

			</div>

			<div id="content-3" class="contenedorDashboard">

				<div class="cajas-grid">
					<div class="caja-cont caja">
						<div class="titulo">Citas por evolucionar</div>
						<div class="info">
							<?php $sinEvolucionQuery = "SELECT DISTINCT ct_idDoctor, dc_nombres FROM citas INNER JOIN doctores ON citas.ct_idDoctor = doctores.IDDoctor WHERE ct_idClinica='$sessionClinica' AND ct_evolucionada=0 AND ct_fechaInicio <= '$fechaHoySinEsp' ORDER BY dc_nombres ASC ";

							$rowCountSinEvolucion = $con->query($sinEvolucionQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $rowCountSinEvolucion,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashSinEvolucion'
								);
								$pagination =  new Pagination($pagConfig);

							$sinEvolucionSql = $con->query($sinEvolucionQuery." LIMIT $numeroResultados");
							?>
							<div id="showResultsDashSinEvolucion">
								<table class="tableList">
									<tbody>
										<?php while($sinEvolucionRow = $sinEvolucionSql->fetch_assoc()){
											$dcCountSinEvolucion = $con->query("SELECT COUNT(*) AS cont FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_idDoctor='$sinEvolucionRow[ct_idDoctor]' AND ct_evolucionada=0 AND ct_fechaInicio <= '$fechaHoySinEsp'")->fetch_assoc();
										?>
										<tr>
											<td align="center"><?php echo $dcCountSinEvolucion['cont'] ?></td>
											<td align="left"><a id="<?php echo $sinEvolucionRow['ct_idDoctor'] ?>" class="consultorioDashEditar" data-page="doctor"><?php echo $sinEvolucionRow['dc_nombres'] ?></a></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php echo $pagination->createLinks(); ?>
							</div>
						</div>
					</div>


					<div class="caja-cont caja">
						<div class="titulo">Cuentas por pagar a Proveedores</div>
						<div class="info">
							<?php $proveedoresQuery = "SELECT DISTINCT IDProveedor, pr_nombre FROM proveedores AS pr
							INNER JOIN ordenesentrada AS ore ON ore.ore_idProveedor = pr.IDProveedor
							WHERE pr_estado = '1' AND ore_estado = 1 ORDER BY pr_nombre ASC ";

							$rowCountProveedores = $con->query($proveedoresQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $rowCountProveedores,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashProveedores'
								);
								$pagination =  new Pagination($pagConfig);

							$proveedoresSql = $con->query($proveedoresQuery." LIMIT $numeroResultados");
							?>
							<div id="showResultsDashProveedores">
								<table class="tableList">
									<tbody>
										<?php while($proveedoresRow = $proveedoresSql->fetch_assoc()){

										$cuentaProveedor = 0;
											$abonosCuenta = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos AS pra
													INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
													WHERE ore_idProveedor = '$proveedoresRow[IDProveedor]' AND ore_estado = 1 AND pra_estado = 1")->fetch_assoc();

											$facturaCuenta = $con->query("SELECT SUM(ore_facturaValor) AS facturas FROM ordenesentrada WHERE ore_idProveedor = '$proveedoresRow[IDProveedor]' AND ore_estado = 1")->fetch_assoc();

											$cuentaProveedor = $facturaCuenta['facturas'] - $abonosCuenta['abonos'];

											$cuentaProveedor = '$'.number_format($cuentaProveedor, 0, ".", ",");
											
										?>
										<tr>
											<td><a class="consultorioDashEditar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" data-page="proveedor"><?php echo $proveedoresRow['pr_nombre'] ?></a></td>
											<td align="right"><?php echo $cuentaProveedor ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php echo $pagination->createLinks(); ?>
							</div>
						</div>
					</div>


					<div class="caja-cont caja">
						<div class="titulo">Facturas sin pagar</div>
						<div class="info">
							<?php $facturasQuery = "SELECT ore.ore_facturaFechaVencimiento, ore.ore_facturaValor, ore.IDOrdenEntrada, pr.IDProveedor, pr.pr_nombre FROM ordenesentrada AS ore
								INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
								WHERE ore.ore_idClinica = '$sessionClinica' AND ore.ore_pagada = 0 AND ore.ore_estado = 1 ORDER BY ore.ore_facturaFechaVencimiento ASC, ore.ore_numeroFactura ASC ";

							$rowCountFacturas = $con->query($facturasQuery)->num_rows;

								$pagConfig = array(
									'totalRows' => $rowCountFacturas,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationDashFacturas'
								);
								$pagination =  new Pagination($pagConfig);

							$facturasSql = $con->query($facturasQuery." LIMIT $numeroResultados");
							?>
							<div id="showResultsDashFacturas">
								<table class="tableList">
									<tbody>
										<?php while($facturasRow = $facturasSql->fetch_assoc()){

										$cuentaFactura = 0;
											$abonosFactura = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos WHERE pra_idOrden = '$facturasRow[IDOrdenEntrada]' AND pra_estado = 1")->fetch_assoc();

											$cuentaFactura = $facturasRow['ore_facturaValor'] - $abonosFactura['abonos'];

											$cuentaFactura = '$'.number_format($cuentaFactura, 0, ".", ",");
											
										$estadoFactura = 'estadoNeutro';

										$vencimientoFacturaInt = str_replace('/', '', $facturasRow['ore_facturaFechaVencimiento']);
										if( $vencimientoFacturaInt < $fechaHoySinEsp ){
                                            $estadoFactura = 'semaforoRojo';
                                        }
										?>
										<tr>
											<td class="estado <?= $estadoFactura ?>"></td>
											<td><?php echo $facturasRow['ore_facturaFechaVencimiento'] ?></td>
											<td><a class="consultorioDashEditar" id="<?php echo $facturasRow['IDProveedor'] ?>" data-page="proveedor"><?php echo $facturasRow['pr_nombre'] ?></a></td>
											<td align="right"><?php echo $cuentaFactura ?></td>
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


			<div id="content-4" class="contenedorDashboard">
				<?php $tareasPendientesQuery = "SELECT tareas.*, tpt_nombre, pc_nombres FROM tareas 
						INNER JOIN citas ON tareas.tar_idCita = citas.IDCita
						INNER JOIN pacientes ON citas.ct_idPaciente = pacientes.IDPaciente 
						INNER JOIN tipotarea ON tareas.tar_idTipo = tipotarea.IDTipoTarea
						WHERE tar_idClinica='$sessionClinica' AND tar_estado=0 ORDER BY tar_fecha ASC";

					$rowCountTareasPendientes = $con->query($tareasPendientesQuery)->num_rows;

						$pagConfig = array(
							'totalRows' => $rowCountTareasPendientes,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationDashTareasPendientes'
						);
						$pagination =  new Pagination($pagConfig);

					$tareasPendientesSql = $con->query($tareasPendientesQuery." LIMIT $numeroResultados");
				?>
				<div class="caja-grid-solo">
				<div class="caja-cont caja">
					<div class="titulo" id="contFTarea">Pendientes</div>

					<div id="showResultsDashTareasPendientes">
						<table class="tableList">
							<thead>
								<tr>
									<th class="estado">&nbsp;</th>
									<th class="columnaCorta">Creación</th>
									<th class="columnaCorta">Para</th>
									<th>Responsable</th>
									<th>Tipo</th>
									<th>Paciente</th>
									<th class="tableNota">Nota</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php while($tareasPendientesRow = $tareasPendientesSql->fetch_assoc()){

									$responsableNombrePendiente = '';

									$usuarioTareaPendienteSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$tareasPendientesRow[tar_responsable]'")->fetch_assoc();
									if($usuarioTareaPendienteSql['us_idRol']==2){
										$responsableNombrePendienteSql = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
										$responsableNombrePendiente = $responsableNombrePendienteSql['sc_nombre'];
									} elseif($usuarioTareaPendienteSql['us_idRol']==3){
										$responsableNombrePendienteSql = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
										$responsableNombrePendiente = $responsableNombrePendienteSql['dc_nombres'];
									} elseif($usuarioTareaPendienteSql['us_idRol']==4){
										$responsableNombrePendienteSql = $con->query("SELECT ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
										$responsableNombrePendiente = $responsableNombrePendienteSql['ui_nombres'];
									} elseif($usuarioTareaPendienteSql['us_idRol']==5){
										$responsableNombrePendienteSql = $con->query("SELECT uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$usuarioTareaPendienteSql[us_id]'")->fetch_assoc();
										$responsableNombrePendiente = $responsableNombrePendienteSql['uc_nombres'];
									}


									$tareaPacienteUrl = str_replace(" ","-", $tareasPendientesRow['pc_nombres']);

									if( $tareasPendientesRow['tar_fecha'] < date('Y-m-d') ){ $estadoTarea = 'estadoCancelado'; }
									else{ $estadoTarea = 'estadoNeutro'; }

								?>
									<tr>
										<td class="estado <?php echo $estadoTarea ?>"></td>
										<td align="center"><?php echo $tareasPendientesRow['tar_creada'] ?></td>
										<td align="center"><?php echo str_replace('-','/',$tareasPendientesRow['tar_fecha']) ?></td>
										<td><?php echo $responsableNombrePendiente ?></td>
										<td><?php echo $tareasPendientesRow['tpt_nombre'] ?></td>
										<td><a title="<?php echo $tareaPacienteUrl ?>" id="<?php echo $tareasPendientesRow['IDPaciente'] ?>" class="consultorioDashEditar" data-page="paciente"><?php echo $tareasPendientesRow['pc_nombres'] ?></a></td>
										<td><?php echo $tareasPendientesRow['tar_nota'] ?></td>
										<td>
											<a title="Confirmar tarea" class="verTarea" data-id="<?= $tareasPendientesRow['IDTarea'] ?>" data-tipo="pendientes"><i class="fa fa-file-text-o"></i></a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>
				</div>


				<?php $tareasRealizadasQuery = "SELECT tareas.*, tpt_nombre, pc_nombres FROM tareas 
						INNER JOIN citas ON tareas.tar_idCita = citas.IDCita
						INNER JOIN pacientes ON citas.ct_idPaciente = pacientes.IDPaciente 
						INNER JOIN tipotarea ON tareas.tar_idTipo = tipotarea.IDTipoTarea
						WHERE tar_idClinica='$sessionClinica' AND tar_estado=1 ORDER BY tar_completada ASC";

					$rowCountTareasRealizadas = $con->query($tareasRealizadasQuery)->num_rows;

						$pagConfig = array(
							'totalRows' => $rowCountTareasRealizadas,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationDashTareasRealizadas'
						);
						$pagination =  new Pagination($pagConfig);

					$tareasRealizadasSql = $con->query($tareasRealizadasQuery." LIMIT $numeroResultados");
				?>
				<div class="caja-grid-solo">
				<div class="caja-cont caja">
					<div class="titulo" id="contFTarea">Realizadas</div>

					<div id="showResultsDashTareasRealizadas">
						<table class="tableList">
							<thead>
								<tr>
									<th class="estado">&nbsp;</th>
									<th class="columnaCorta">Creación</th>
									<th class="columnaCorta">Completada</th>
									<th>Responsable</th>
									<th>Tipo</th>
									<th>Paciente</th>
									<th class="tableNota">Nota</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php while($tareasRealizadasRow = $tareasRealizadasSql->fetch_assoc()){

									$responsableNombreRealizada = '';

									$usuarioTareaRealizadaSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$tareasRealizadasRow[tar_responsable]'")->fetch_assoc();
                                    if($usuarioTareaRealizadaSql['us_idRol']==2){
                                        $responsableNombreRealizadaSql = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['sc_nombre'];
                                    } elseif($usuarioTareaRealizadaSql['us_idRol']==3){
                                        $responsableNombreRealizadaSql = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['dc_nombres'];
                                    } elseif($usuarioTareaRealizadaSql['us_idRol']==4){
                                        $responsableNombreRealizadaSql = $con->query("SELECT ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['ui_nombres'];
                                    } elseif($usuarioTareaRealizadaSql['us_idRol']==5){
                                        $responsableNombreRealizadaSql = $con->query("SELECT uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$usuarioTareaRealizadaSql[us_id]'")->fetch_assoc();
                                        $responsableNombreRealizada = $responsableNombreRealizadaSql['uc_nombres'];
                                    }

									$tareaPacienteUrl = str_replace(" ","-", $tareasRealizadasRow['pc_nombres']);
								?>
									<tr>
										<td class="estado semaforoVerde"></td>
										<td align="center"><?php echo $tareasRealizadasRow['tar_creada'] ?></td>
										<td align="center"><?php echo $tareasRealizadasRow['tar_completada'] ?></td>
										<td><?php echo $responsableNombreRealizada ?></td>
										<td><?php echo $tareasRealizadasRow['tpt_nombre'] ?></td>
										<td><a title="<?php echo $tareaPacienteUrl ?>" id="<?php echo $tareasRealizadasRow['IDPaciente'] ?>" class="consultorioDashEditar" data-page="paciente"><?php echo $tareasRealizadasRow['pc_nombres'] ?></a></td>
										<td><?php echo $tareasRealizadasRow['tar_nota'] ?></td>
										<td>
											<a title="Ver tarea" class="verTarea" data-id="<?= $tareasRealizadasRow['IDTarea'] ?>" data-tipo="realizadas"><i class="fa fa-file-text-o"></i></a>
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


			<div id="content-5" class="contenedorDashboard" style="overflow-x: scroll;">

				<div id="msj-comparativo" class="contenedorAlerta"></div>
				<div id="msj-evolucion" class="contenedorAlerta"></div>

				<div class="contenedorComparativo">

					<?php
					$min = $con->query("SELECT MIN(sc_atencionDe) AS horaMin, MAX(sc_atencionHasta) AS horaMax FROM sucursales AS sc INNER JOIN unidadesodontologicas AS uo ON uo.uo_idSucursal = sc.IDSucursal WHERE sc_idClinica='$sessionClinica' AND sc_estado='1'")->fetch_assoc();

					$inicioDia = explode(':', $min['horaMin']);
					$inicioDia = $inicioDia[0].'00';

					$finDia = explode(':', $min['horaMax']);
					if( $finDia[0] == 23 ){ $finDia = " AND hr_horaInt <= '2355'"; }
					else{ $finDia = " AND hr_horaInt < '". (intval($finDia[0]) + 1) ."00'"; }
					?>

					<div class="unidadesComparativoInicial">

						<div class="titulo titulo-change tituloComparativo" id="dashComparativo">
							<div title="Click para Cambiar" id="dashComparativoTitle"><?= 'Fecha<br>'.date('Y/m/d') ?></div>
							<i title="Cambiar agenda de día" class="fa fa-refresh"></i>
						</div>

						<div id="dashComparativoFecha" class="titulo tituloComparativo tituloInput form">
							<input type="date" id="dashComparativoFechaInput" class="formulario__input">
						</div>

						<div class="info">
							<?php $horasInicial = $con->query("SELECT * FROM horas WHERE hr_inicial = 1 AND hr_horaInt >= '$inicioDia' $finDia ");
								while($horasInicialRow = $horasInicial->fetch_assoc()){
									echo '<span>'.$horasInicialRow['hr_hora'].'</span>';								}
							?>
						</div>
					</div>

					<div class="unidadComparativoCont" id="unidadComparativoCont">
					<?php $fechaComparativoInt = date('Ymd'); 

					$unidadesComparativo = $con->query("SELECT IDSucursal, IDUnidadOdontologica, sc_nombre, uo_nombre, sc_atencionDe, sc_atencionHasta FROM unidadesodontologicas AS uo INNER JOIN sucursales AS sc ON uo.uo_idSucursal = sc.IDSucursal WHERE sc_idClinica = '$sessionClinica' AND uo_estado = '1' AND sc_estado = '1' ORDER BY sc_nombre ASC, uo_nombre ASC ");
						while($unidadesComparativoRow = $unidadesComparativo->fetch_assoc()){

							$sc_entrada = str_replace(':', '', $unidadesComparativoRow['sc_atencionDe']);
							$sc_salida = str_replace(':', '', $unidadesComparativoRow['sc_atencionHasta']);
							
							$unidad_cita = array();
							$unidad_horaDe = array();
							$unidad_horaHasta = array();

							$citasComparativo = $con->query("SELECT IDCita, ct_horaCitaDe, ct_horaCitaHasta FROM citas WHERE ct_idSucursal = '$unidadesComparativoRow[IDSucursal]' AND ct_idUnidad = '$unidadesComparativoRow[IDUnidadOdontologica]' AND ct_estado IN(0,1) AND ct_anoCita='$hoyAno' AND ct_mesCita='$hoyMes' AND ct_diaCita='$hoyDia' ORDER BY ct_horaCitaDe ASC");
							while($citasComparativoRow = $citasComparativo->fetch_assoc()){

								array_push($unidad_cita, $citasComparativoRow['IDCita']);
								array_push($unidad_horaDe, (int)$citasComparativoRow['ct_horaCitaDe']);
								array_push($unidad_horaHasta, (int)$citasComparativoRow['ct_horaCitaHasta']);
							}

					?>
					<div class="unidadComparativo">
						<div class="titulo tituloComparativo"><?= __substr($unidadesComparativoRow['sc_nombre']).'<br>'.__substr($unidadesComparativoRow['uo_nombre']); ?></div>
						<div class="info">
							<?php $horas = $con->query("SELECT * FROM horas WHERE hr_horaInt >= '$inicioDia' $finDia ");

								$i=0;

								while($horasRow = $horas->fetch_assoc()){

									$classTooltip = '';
									//$tooltip = '';
									$textImp = '';
									$datosExtras = '';
									$title = $horasRow['hr_hora'];

									if( $horasRow['hr_inicial'] == 1 ){
										$classTooltip = 'lineaHora';
									} else { $classTooltip = ''; }

									if($horasRow['hr_horaInt'] >= $sc_entrada && $horasRow['hr_horaInt'] < $sc_salida){

										if( $horasRow['hr_horaInt'] >= $unidad_horaDe[$i] && $horasRow['hr_horaInt'] <= $unidad_horaHasta[$i] ){

											if( $horasRow['hr_horaInt'] == $unidad_horaDe[$i] ){
												$classTooltip .= ' primero ';
											}

											$horaInicial = $horasRow['hr_horaInt']-$incrementoHora;

											$incrementoHora += 5;

											if( $horasRow['hr_inicial'] == 1 && $horasRow['hr_horaInt'] > $unidad_horaDe[$i] && $horasRow['hr_horaInt'] <= $unidad_horaHasta[$i] ){
												$classTooltip .= 'Lleno ';
											}

											$datosExtras = ' data-id='.$unidad_cita[$i];

											$infoCita = $con->query("SELECT dc_nombres, pc_nombres, ct_fechaInicio, ct_duracion, ct_horaCita, ct_estado, ct_evolucionada, ct_asistencia FROM citas AS ct
												INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
												INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
												WHERE IDCita = '$unidad_cita[$i]'
												")->fetch_assoc();
											
											if($textoInicial==0){
												$textImp = $infoCita['pc_nombres'];
											}
											if($textoInicial==1){
												$textImp = 'Dr/a. '.$infoCita['dc_nombres'];
											}
											if($textoInicial==2){
												$textImp = 'Hora inicio: '.$infoCita['ct_horaCita'].' - Duración: '.$infoCita['ct_duracion'].' min';
											}
											$textoInicial++;

											$title = 'Click para ver';
											
											if( $infoCita['ct_asistencia']==2){ $colorCita = ' realizada '; $classTooltip .= ' evolucion '; }
											else
											if( $infoCita['ct_asistencia']==1){ $colorCita = ' sinasistencia '; $classTooltip .= ' evolucion '; }
											else
											if( $infoCita['ct_evolucionada']==0 && ($infoCita['ct_fechaInicio'].str_replace(':','',$infoCita['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){ $colorCita = ' sinevolucion '; $classTooltip .= ' evolucion '; }
											else
											if( $infoCita['ct_estado']==1){ $colorCita = ' confirmada '; $classTooltip .= ' verCita '; }
											else { $colorCita = ' creada '; $classTooltip .= ' verCita '; }

											$classTooltip .= $colorCita.' activado ';
										/*	$tooltip = '<span class="tooltiptext">
													<b>Doctor: </b>'.$infoCita['dc_nombres'].'
													<br><b>Paciente: </b>'.$infoCita['pc_nombres'].'
													<br><b>Duración: </b>'.$infoCita['ct_duracion'].' minutos
												</span>';
										*/	
											if( $horasRow['hr_horaInt'] == $unidad_horaHasta[$i] ){
												$classTooltip .= ' ultimo ';
												$i++;
												$incrementoHora = 0;
												$textoInicial = 0;
											}
											
										} else {
											$incrementoHora = 0;
											$textoInicial = 0;

											if($fechaComparativoInt >= $fechaHoySinEsp){
												$classTooltip .= ' crearCita';

												$datosExtras .= 
												' data-hora='.$horasRow['hr_hora'].
												' data-unidad='.$unidadesComparativoRow['IDUnidadOdontologica'];
											}
										}
									} else {
										$classTooltip .= ' horaInactiva';
									}

									$classTooltip = trim($classTooltip);
									$classTooltip = str_replace("  ", " ", $classTooltip);

									echo '<span class="'.$classTooltip.'" '.$datosExtras.' title="'.$title.'">'.$textImp.'</span>';
								}
							?>
						</div>
					</div>
					<?php } ?>
					</div>
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

	<?php include'footer.php'; ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
	<script src="js/calendar.min.js"></script>
	<script src="js/label.js"></script>
	<script type="text/javascript">
		function paginationDashTareasPendientes(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashTareasPendientesData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResultsDashTareasPendientes').html(html);
		        }
		    });
		}
		function paginationDashTareasRealizadas(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashTareasRealizadasData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResultsDashTareasRealizadas').html(html);
		        }
		    });
		}
		function paginationDashSinEvolucion(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashSinEvolucionData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResultsDashSinEvolucion').html(html);
		        }
		    });
		}
		function paginationDashProveedores(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashProveedoresData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResultsDashProveedores').html(html);
		        }
		    });
		}
		function paginationDashFacturas(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashFacturasData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResultsDashFacturas').html(html);
		        }
		    });
		}
		function paginationDashDoctorHoy(page_num) {
		    page_num = page_num?page_num:0;
		    //var busqueda = $('#searchDoctores').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashDoctorHoyData.php',
		        data:'page='+page_num,
		        //+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsDashDoctorHoy').html(html);
		        }
		    });
		}
		function paginationDashDoctorMes(page_num) {
		    page_num = page_num?page_num:0;
		    var consultoriosDashConsulDCMes = $('#selectDCMes').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashDoctorMesData.php',
		        data:'page='+page_num+'&mes='+consultoriosDashConsulDCMes,
		        //+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#listDCMes').html(html);
		        }
		    });
		}
		function paginationDashRefPc(page_num) {
		    page_num = page_num?page_num:0;
		    var consultoriosDashConsulRefPc = $('#selectRefPc').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashRefPcMesData.php',
		        data:'page='+page_num+'&mes='+consultoriosDashConsulRefPc,
		        //+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#listRefPc').html(html);
		        }
		    });
		}
		function paginationDashSucursales(page_num) {
		    page_num = page_num?page_num:0;
		    var consultoriosDashConsulSCMes = $('#selectSCMes').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashSucursalMesData.php',
		        data:'page='+page_num+'&mes='+consultoriosDashConsulSCMes,
		        //+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#dashListSCConsolidado').html(html);
		        }
		    });
		}
		function paginationDashCitasAtendidas(page_num) {
		    page_num = page_num?page_num:0;
		    var ctAtendidas_de = $( "#selectCTatendidas_de" ).val();
			var ctAtendidas_hasta = $( "#selectCTatendidas_hasta" ).val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/dashCitasAtendidasData.php',
		        data:'page='+page_num+'&atendidas_de='+ctAtendidas_de+'&atendidas_hasta='+ctAtendidas_hasta,
		        //+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#dashList_citasAtendidas').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioDashEditar', function(){  
			var consultoriosId = $(this).attr("id"); 
			var consultoriosPage = $(this).attr("data-page"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url: consultoriosPage + ".php",
			        method:"POST",
		            data:{id:consultoriosId}, 
			        success:function(data){  
						$('.contenedorPrincipal').html(data);
					}
			    });  
			}         
		});

		$('#contSelectSCMes').hide();
		$(document).on('click', '#contSCMes', function(){
			$('#contSelectSCMes').show();
			$('#contSCMes').hide();      
		});
		$( "#selectSCMes" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashConsultar_scmes.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal}, 
			        success:function(data){
						$('#dashListSCConsolidado').html(data);
					}
			    });  
			}
		});

		$('#contSelectDCMes').hide();
		$(document).on('click', '#contDCMes', function(){
			$('#contSelectDCMes').show();
			$('#contDCMes').hide();      
		});
		$( "#selectDCMes" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashConsultar_dcmes.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listDCMes').html(data);
					}
			    });  
			}
			
		});

		$('#contSelectRefPc').hide();
		$(document).on('click', '#contRefPc', function(){
			$('#contSelectRefPc').show();
			$('#contRefPc').hide();      
		});
		$( "#selectRefPc" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "get/dashRefPcMesData.php",
			        method:"POST",
		            data:{page:0,mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listRefPc').html(data);
					}
			    });
			}
			
		});

		$('#contSelectRefDc').hide();
		$(document).on('click', '#contRefDc', function(){
			$('#contSelectRefDc').show();
			$('#contRefDc').hide();      
		});
		$( "#selectRefDc" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "get/dashRefDcMesData.php",
			        method:"POST",
		            data:{page:0,mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listRefDc').html(data);
					}
			    });
			}
			
		});

		$('#contSelectRefSocial').hide();
		$(document).on('click', '#contRefSocial', function(){
			$('#contSelectRefSocial').show();
			$('#contRefSocial').hide();      
		});
		$( "#selectRefSocial" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "get/dashRefSocialMesData.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listRefSocial').html(data);
					}
			    });
			}
			
		});

		$('#contSelectRefCamp').hide();
		$(document).on('click', '#contRefCamp', function(){
			$('#contSelectRefCamp').show();
			$('#contRefCamp').hide();      
		});
		$( "#selectRefCamp" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "get/dashRefCampMesData.php",
			        method:"POST",
		            data:{page:0,mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listRefCamp').html(data);
					}
			    });
			}
			
		});

		$('#contSelectRefOtros').hide();
		$(document).on('click', '#contRefOtros', function(){
			$('#contSelectRefOtros').show();
			$('#contRefOtros').hide();      
		});
		$( "#selectRefOtros" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "get/dashRefOtrosMesData.php",
			        method:"POST",
		            data:{page:0,mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listRefOtros').html(data);
					}
			    });
			}
			
		});

		$('#contSelectRefVn').hide();
		$(document).on('click', '#contRefVn', function(){
			$('#contSelectRefVn').show();
			$('#contRefVn').hide();      
		});
		$( "#selectRefVn" ).change(function() {
			var consultoriosMesVal = $(this).val();	
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "get/dashRefVnMesData.php",
			        method:"POST",
		            data:{page:0,mes:consultoriosMesVal}, 
			        success:function(data){
						$('#listRefVn').html(data);
					}
			    });
			}
			
		});

		$('#contSelectCTatendidas').hide();
		$(document).on('click', '#contCTatendidasTitle', function(){
			$('#contSelectCTatendidas').show();
			$('#contCTatendidas').hide();      
		});
		$( "#selectCTatendidas_de" ).change(function() { listCTatendidas(); });
		$( "#selectCTatendidas_hasta" ).change(function() { listCTatendidas(); });
		function listCTatendidas(){
			var ctAtendidas_de = $( "#selectCTatendidas_de" ).val();
			var ctAtendidas_hasta = $( "#selectCTatendidas_hasta" ).val();
		    if(ctAtendidas_de != '' && ctAtendidas_hasta != '')
		    {
		    	$.ajax({
		        	url: "extras/dashCTatendidas.php",
			        method:"POST",
		            data:{atendidas_de:ctAtendidas_de,atendidas_hasta:ctAtendidas_hasta}, 
			        success:function(data){
						$('#dashList_citasAtendidas').html(data);
					}
			    });  
			}
		};


		$('#contSelectFPDia').hide();
		$(document).on('click', '#contFPDiaTitle', function(){
			$('#contSelectFPDia').show();
			$('#contFPDia').hide();      
		});
		$( "#selectFPDia" ).change(function() {
			var consultoriosDiaVal = $(this).val();
		    if(consultoriosDiaVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashRecaudos_fpdia.php",
			        method:"POST",
		            data:{dia:consultoriosDiaVal}, 
			        success:function(data){
						$('#listFPDia').html(data);
					}
			    });  
			}
			
		});

		$('#contSelectFPMes').hide();
		$(document).on('click', '#contFPMes', function(){
			$('#contSelectFPMes').show();
			$('#contFPMes').hide();      
		});
		$( "#selectFPMes" ).change(function() {
			var consultoriosMesVal = $(this).val();
			var consultoriosAnioVal = $("#selectFPAnio").val();		
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashRecaudos_fpmes.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal,anio:consultoriosAnioVal}, 
			        success:function(data){
						$('#listFPMes').html(data);
					}
			    });  
			}
			
		});

		$('#contSelectFPAnio').hide();
		$(document).on('click', '#contFPAnio', function(){
			$('#contSelectFPAnio').show();
			$('#contFPAnio').hide();      
		});
		$( "#selectFPAnio" ).change(function() {
			var consultoriosAnioVal = $(this).val();
			var consultoriosMesVal = $("#selectFPMes").val();
		    if(consultoriosAnioVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashRecaudos_fpanio.php",
			        method:"POST",
		            data:{anio:consultoriosAnioVal}, 
			        success:function(data){
						$('#listFPAnio').html(data);
					}
			    });

			    $.ajax({
		        	url: "extras/dashRecaudos_fpmes.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal,anio:consultoriosAnioVal}, 
			        success:function(data){
						$('#listFPMes').html(data);
					}
			    });   
			}
			
		});


		$('#contSelectCEDia').hide();
		$(document).on('click', '#contCEDiaTitle', function(){
			$('#contSelectCEDia').show();
			$('#contCEDia').hide();      
		});
		$( "#selectCEDia" ).change(function() {
			var consultoriosDiaVal = $(this).val();
		    if(consultoriosDiaVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashEgresos_cedia.php",
			        method:"POST",
		            data:{dia:consultoriosDiaVal}, 
			        success:function(data){
						$('#listCEDia').html(data);
					}
			    });  
			}
		});

		$('#contSelectCEMes').hide();
		$(document).on('click', '#contCEMes', function(){
			$('#contSelectCEMes').show();
			$('#contCEMes').hide();      
		});
		$( "#selectCEMes" ).change(function() {
			var consultoriosMesVal = $(this).val();
			var consultoriosAnioVal = $("#selectCEAnio").val();		
		    if(consultoriosMesVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashEgresos_cemes.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal,anio:consultoriosAnioVal}, 
			        success:function(data){
						$('#listCEMes').html(data);
					}
			    });  
			}
		});

		$('#contSelectCEAnio').hide();
		$(document).on('click', '#contCEAnio', function(){
			$('#contSelectCEAnio').show();
			$('#contCEAnio').hide();      
		});
		$( "#selectCEAnio" ).change(function() {
			var consultoriosAnioVal = $(this).val();
			var consultoriosMesVal = $("#selectCEMes").val();
		    if(consultoriosAnioVal != '')
		    {
		    	$.ajax({
		        	url: "extras/dashEgresos_ceanio.php",
			        method:"POST",
		            data:{anio:consultoriosAnioVal}, 
			        success:function(data){
						$('#listCEAnio').html(data);
					}
			    });

			    $.ajax({
		        	url: "extras/dashEgresos_cemes.php",
			        method:"POST",
		            data:{mes:consultoriosMesVal,anio:consultoriosAnioVal}, 
			        success:function(data){
						$('#listCEMes').html(data);
					}
			    });   
			}
		});


		$(document).on('click', '.verTarea', function(){
			var tarea = $(this).attr('data-id');
			var tipo = $(this).attr('data-tipo');
			//$( this ).html('');
			//$( this ).closest("tr").find('.estado').addClass('estadoAprobado');

			if(tarea != ''){
				$.ajax({
			       	url: "confirmar-tarea.php",
				    method:"POST",
			        data:{tarea:tarea,tipo:tipo}, 
				    success:function(data){						
						$('#consultoriosDetails').html(data);
						$('#consultoriosModal').modal('show');
					}
				});
			}

		});



		$('#dashComparativoFecha').hide();
		$(document).on('click', '#dashComparativoTitle', function(){
			$('#dashComparativoFecha').show();
			$('#dashComparativo').hide();  
		});
		$( "#dashComparativoFechaInput" ).change(function() {
			dashComparativo();
		});

		function dashComparativo(){
			var consultoriosFecha = $( "#dashComparativoFechaInput" ).val();
		    if(consultoriosFecha != '')
		    {
		    	$.ajax({
		        	url: "extras/dashComparativo.php",
			        method:"POST",
		            data:{fecha:consultoriosFecha}, 
			        success:function(data){
						$('#unidadComparativoCont').html(data);
					}
			    });
			}
			
		};

		$(document).on('click', '#dashComparativoCambiarDia', function(){
			var fecha_inicio = $('#dashComparativoFechaInput').val(); 
		   	$.ajax({
		       	url:"dashboard-cambiar-agenda.php",  
		        method:"POST",
		        data: {fecha_inicio:fecha_inicio},
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');  
				}
		    });
		});

		$(document).on('click', '.crearCita', function(){
			var comparativoHora = $(this).attr('data-hora');
			var comparativoFecha = $( "#dashComparativoFechaInput" ).val();
			var comparativoUnidad = $(this).attr('data-unidad');

			$.ajax({
				url: 'dashboard-crear-cita.php',
				type: 'POST',
				data: {unidad:comparativoUnidad,hora:comparativoHora,fecha:comparativoFecha},
				success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');
				}
			})

		});

		$(document).on('click', '.verCita', function(){
			var verCitaId = $(this).attr('data-id');
			if(verCitaId > 0){
				$.ajax({
					url: 'dashboard-ver-cita.php',
					type: 'POST',
					data: {citaID:verCitaId},
					success:function(data){  
						$('#consultoriosDetails').html(data);
						$('#consultoriosModal').modal('show');  
					}
				});
			}
		});

		$(document).on('click', '.estadoCita', function(){
			var accion = $(this).attr('data-action');
			var date = $(this).attr('data-date');
			var citaID = $(this).attr('data-id');

			if(citaID > 0){
				$.ajax({
					url: 'cita-cambio-estado.php',
					type: 'POST',
					data: {citaID:citaID,date:date,accion:accion},
					success:function(data){  
						$('#msj-comparativo').html(data);
					}
				});				
			}

		});

		$(document).on('click', '.btn_dash_concentimiento', function(){
			var date = $(this).attr('data-date');
			var citaID = $(this).attr('data-id');

			if(citaID > 0){
				$.ajax({
					url: 'dashboard-ver-concentimiento.php',
					type: 'POST',
					data: {citaID:citaID,date:date},
					success:function(data){  
						$('#consultoriosDetails').html(data);
					}
				});				
			}

		});

		$(document).on('click', '.btn_cita_concentimiento_guardar', function(){
			var citaID = $('#ct_info_citaID').val();
			var date = $('#ct_info_date').val();
			var usuario = $('#firma_concent_usuario').val();
			var paciente = $('#firma_concent_paciente').val();

			if(citaID > 0){
				$.ajax({
					url: 'dashboard-concentimiento-guardar.php',
					type: 'POST',
					data: {citaID:citaID,date:date,usuario:usuario,paciente:paciente},
					success:function(data){  
						$('#msj-comparativo').html(data);
					}
				});				
			}

		});

		$(document).on('click', '.evolucion', function(){
			var consultoriosId = $(this).attr("data-id");
			var consultoriosEv = 'dash';
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

		$( "#sucursalDashboard" ).change(function() {
			var sucursalDashboard = $(this).val();

			$.ajax({
				url: 'extras/unidades.php',
				type: 'POST',
				data: {sucursalID:sucursalDashboard},
				success:function(data){  
					$('#unidadDashboard').html(data);
				}
			})
			
		});

	$(document).on('click', '#confirmarCita', function(){
		var formCita = new FormData($("#formCita")[0]);
	   	$.ajax({
	       	url:"confirmar-cita.php",
	        method:"POST",
	       	data: formCita,
	        contentType: false,
			processData: false,
	        success:function(data){   
				$('#consultoriosModal').modal('show');
				$('#consultoriosDetails').html(data);   
			}
	    });
	});

		$(document).on('click', '.consultorioOrdenEntrada', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"orden-entrada.php",
		            method:"POST",  
		            data:{id:consultoriosId},  
		            success:function(data){  
						$('.contenedorPrincipal').html(data);  
					}
		    	});  
			}            
		});

		$(document).on('click', '.consultorioSalida', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"material-salida.php",  
		            method:"POST",  
		            data:{id:consultoriosId},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

		$(document).on('click', '.consultorioNuevoPaciente', function(){
			<?php if($rowCount < $clinicaRow['cl_cantPacientes']){ ?> 
	    	$.ajax({
	        	url:"paciente.php",  
		        method:"POST", 
		        success:function(data){  
					$('.contenedorPrincipal').html(data);   
				}
		    });
		    <?php } else { ?>
		   		$('.contenedorAlerta').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Llegó al limite de registros posibles para Pacientes.</div><div class="close">&times;</div></label>');
		   	<?php } ?>
		});


	</script>
	<script type="text/javascript">
	var yy;
	var calendarArray =[];
	var monthOffset = [6,7,8,9,10,11,0,1,2,3,4,5];
	var monthArray = [["ENE","Enero"],["FEB","Febrero"],["MAR","Marzo"],["ABR","Abril"],["MAY","Mayo"],["JUN","Junio"],["JUL","Julio"],["AGO","Agosto"],["SEP","Septiembre"],["OCT","Octubre"],["NOV","Noviembre"],["DIC","Diciembre"]];
	var letrasArray = ["D","L","M","M","J","V","S"];
	var dayArray = ["1","2","3","4","5","6","7"];
	$(document).ready(function() {
		//$(document).on('click','.calendar-day.have-events',activateDay);
		$(document).on('click','.specific-day',activatecalendar);
		$(document).on('click','.calendar-month-view-arrow',offsetcalendar);
		$(window).resize(calendarScale);
		
		calendarSet();
		calendarScale();

		
		$(document).on('click', '.diaSelected', function(){

			var diasActivos = document.querySelectorAll(".diaSelectedActive");
			for (var i = diasActivos.length - 1; i >= 0; i--) {
				diasActivos[i].classList.remove("diaSelectedActive");
			}

			var unidadDashboard = $('#unidadDashboard');
			var sucursalDashboard = $('#sucursalDashboard');
			var consultoriosIdCalenderDashboard = $(this).attr("id");

			if(sucursalDashboard.val() == ''){ sucursalDashboard.addClass('validar'); }
				else { sucursalDashboard.removeClass('validar'); }
			if(unidadDashboard.val() == ''){ unidadDashboard.addClass('validar'); }
				else { unidadDashboard.removeClass('validar'); }	

		    if(consultoriosIdCalenderDashboard != '' && unidadDashboard.val() != '')
		    {
		    	$(this).addClass("diaSelectedActive");
		    	
		    	$.ajax({
		    		url: "dashboard-horario-ver.php",
		    		type: "POST",
		    		data: {
		    			id:consultoriosIdCalenderDashboard,
		    			unidad:unidadDashboard.val()
		    		},
		            success:function(data){  
						$('#horasCalendario').html(data);
					}
		    	});
	    	
			}            
		});
	});

	</script>

<script type="text/javascript">
function addCommas(nStr)
{
   nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return '  $' + x1 + x2;
}

$(document).ready(ventas());
$(document).ready(recaudos());
$(document).ready(citas());
$(document).ready(citasCanceladas());

// VARIABLES GENERALES
var colorSecundario = '<?php echo $colorSecundario ?>';
var colorPrincipal = '<?php echo $colorPrincipal ?>';
var tipoGrafico = 'line';

	function ventas(){
		$.ajax({
			type: 'POST',
			url: 'graficas/ventas.php',
			data: '',
			success:function(data){

				var vt = eval(data); //vt es una varible array donde almacena los datos de la consulta

				var gr1 = document.getElementById('grafico1').getContext('2d');
				var chart = new Chart(gr1, {
				    // The type of chart we want to create
				    type: tipoGrafico,
				    // The data for our dataset
				    data: {
				        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
				        datasets: [{
				            label: "",
				            data: [vt[0], vt[1], vt[2], vt[3], vt[4], vt[5], vt[6], vt[7], vt[8], vt[9], vt[10], vt[11]],
				              borderColor: colorSecundario,
					          pointBorderColor: colorPrincipal,
					          pointBackgroundColor: colorPrincipal,
					          pointHoverBackgroundColor: colorPrincipal,
					          pointHoverBorderColor: colorPrincipal,
					          pointBorderWidth: 0,
					          pointHoverRadius: 2,
					          pointHoverBorderWidth: 0,
					          pointRadius: 2,
					          fill: false,
					          borderWidth: 3,
				        }]
				    },

				    // Configuration options go here
				    options: {
				    	responsive: true,
				    	title: {
							display: true,
							text: 'Ventas'
						},
						tooltips: {  //popup de informacion
							mode: 'index', 
							intersect: false, //muestra el resultado con solo pararse en la linea vertical
							backgroundColor: 'rgba(0, 0, 0, 0.6)',
							callbacks: {
					            label: function(tooltipItem, data) {
					                var datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					                return addCommas(datasetLabel);
					            }
					        },
						},
						hover: {
							mode: 'index',
							intersect: false
						},
						legend: {
							display: false //activa/desactiva el indicador de la linea
						},
						elements: {
							line: {
								tension: 0.3, //define el tamaño de la curva
							},
						},
						scales: { //lineas del fondo
							xAxes: [{
								gridLines: {
									display: false, //cuadricula X
									drawBorder: true //linea inicial X
								},
							}],
							yAxes: [{
								gridLines: {
									display: true, //cuadricula Y
									drawBorder: true //linea inicial Y
								},
								ticks: {
						            callback: function(valor, index, valores) {
						            	return addCommas(valor);
						            }
						        },
							}]
						},
				    }
				});
			}
		});

	}




	function recaudos(){
		$.ajax({
			type: 'POST',
			url: 'graficas/recaudos.php',
			data: '',
			success:function(data){

				var rec = eval(data); //rec es una varible array donde almacena los datos de la consulta

				var gr2 = document.getElementById('grafico2').getContext('2d');
				var chart = new Chart(gr2, {
				    // The type of chart we want to create
				    type: tipoGrafico,

				    // The data for our dataset
				    data: {
				        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
				        datasets: [{
				            label: "",
				            data: [rec[0], rec[1], rec[2], rec[3], rec[4], rec[5], rec[6], rec[7], rec[8], rec[9], rec[10], rec[11]],
				              borderColor: colorSecundario,
					          pointBorderColor: colorPrincipal,
					          pointBackgroundColor: colorPrincipal,
					          pointHoverBackgroundColor: colorPrincipal,
					          pointHoverBorderColor: colorPrincipal,
					          pointBorderWidth: 0,
					          pointHoverRadius: 2,
					          pointHoverBorderWidth: 0,
					          pointRadius: 2,
					          fill: false,
					          borderWidth: 3,
				        }]
				    },

				    // Configuration options go here
				    options: {
				    	responsive: true,
				    	title: {
							display: true,
							text: 'Recaudos'
						},
						tooltips: {  //popup de informacion
							mode: 'index', 
							intersect: false, //muestra el resultado con solo pararse en la linea vertical
							backgroundColor: 'rgba(0, 0, 0, 0.6)',
							callbacks: {
					            label: function(tooltipItem, data) {
					                var datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					                return addCommas(datasetLabel);
					            }
					        },
						},
						hover: {
							mode: 'index',
							intersect: false
						},
						legend: {
							display: false //activa/desactiva el indicador de la linea
						},
						elements: {
							line: {
								tension: 0.3, //define el tamaño de la curva
							},
						},
						scales: { //lineas del fondo
							xAxes: [{
								gridLines: {
									display: false, //cuadricula X
									drawBorder: true //linea inicial X
								},
							}],
							yAxes: [{
								gridLines: {
									display: true, //cuadricula Y
									drawBorder: true //linea inicial Y
								},
								ticks: {
						            callback: function(valor, index, valores) {
						            	return addCommas(valor);
						            }
						        },
							}]
						},
				    }
				});
			}
		});

	}




	function citas(){
		$.ajax({
			type: 'POST',
			url: 'graficas/citas.php',
			data: '',
			success:function(data){

				var ct = eval(data); //ct es una varible array donde almacena los datos de la consulta

				var gr3 = document.getElementById('grafico3').getContext('2d');
				var chart = new Chart(gr3, {
				    // The type of chart we want to create
				    type: tipoGrafico,

				    // The data for our dataset
				    data: {
				        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
				        datasets: [{
				            label: "Cant",
				            data: [ct[0], ct[1], ct[2], ct[3], ct[4], ct[5], ct[6], ct[7], ct[8], ct[9], ct[10], ct[11]],
				              borderColor: colorSecundario,
					          pointBorderColor: colorPrincipal,
					          pointBackgroundColor: colorPrincipal,
					          pointHoverBackgroundColor: colorPrincipal,
					          pointHoverBorderColor: colorPrincipal,
					          pointBorderWidth: 0,
					          pointHoverRadius: 2,
					          pointHoverBorderWidth: 0,
					          pointRadius: 2,
					          fill: false,
					          borderWidth: 3,
				        }]
				    },

				    // Configuration options go here
				    options: {
				    	responsive: true,
				    	title: {
							display: true,
							text: 'Citas'
						},
						tooltips: {  //popup de informacion
							mode: 'index', 
							intersect: false, //muestra el resultado con solo pararse en la linea vertical
							backgroundColor: 'rgba(0, 0, 0, 0.6)',
						},
						hover: {
							mode: 'index',
							intersect: false
						},
						legend: {
							display: false //activa/desactiva el indicador de la linea
						},
						elements: {
							line: {
								tension: 0.3, //define el tamaño de la curva
							},
						},
						scales: { //lineas del fondo
							xAxes: [{
								gridLines: {
									display: false, //cuadricula X
									drawBorder: true //linea inicial X
								},
							}],
							yAxes: [{
								gridLines: {
									display: true, //cuadricula Y
									drawBorder: true //linea inicial Y
								},
							}]
						},
				    }
				});
			}
		});

	}




	function citasCanceladas(){
		$.ajax({
			type: 'POST',
			url: 'graficas/citas-canceladas.php',
			data: '',
			success:function(data){

				var ctc = eval(data); //ctc es una varible array donde almacena los datos de la consulta

				var gr4 = document.getElementById('grafico4').getContext('2d');
				var chart = new Chart(gr4, {
				    // The type of chart we want to create
				    type: tipoGrafico,

				    // The data for our dataset
				    data: {
				        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
				        datasets: [{
				            label: "Cant",
				            data: [ctc[0], ctc[1], ctc[2], ctc[3], ctc[4], ctc[5], ctc[6], ctc[7], ctc[8], ctc[9], ctc[10], ctc[11]],
				              borderColor: colorSecundario,
					          pointBorderColor: colorPrincipal,
					          pointBackgroundColor: colorPrincipal,
					          pointHoverBackgroundColor: colorPrincipal,
					          pointHoverBorderColor: colorPrincipal,
					          pointBorderWidth: 0,
					          pointHoverRadius: 2,
					          pointHoverBorderWidth: 0,
					          pointRadius: 2,
					          fill: false,
					          borderWidth: 3,
				        }]
				    },

				    // Configuration options go here
				    options: {
				    	responsive: true,
				    	title: {
							display: true,
							text: 'Citas Canceladas'
						},
						tooltips: {  //popup de informacion
							mode: 'index', 
							intersect: false, //muestra el resultado con solo pararse en la linea vertical
							backgroundColor: 'rgba(0, 0, 0, 0.6)',
						},
						hover: {
							mode: 'index',
							intersect: false
						},
						legend: {
							display: false //activa/desactiva el indicador de la linea
						},
						elements: {
							line: {
								tension: 0.3, //define el tamaño de la curva
							},
						},
						scales: { //lineas del fondo
							xAxes: [{
								gridLines: {
									display: false, //cuadricula X
									drawBorder: true //linea inicial X
								},
							}],
							yAxes: [{
								gridLines: {
									display: true, //cuadricula Y
									drawBorder: true //linea inicial Y
								},
							}]
						},
				    }
				});
			}
		});

	}


</script>



</body>
</html>