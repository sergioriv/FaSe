<?php include'config.php'; include'pagination-modal-params.php';

if($sessionRol==3){
	$id = $sessionUsuario;
} else {
	$id = $_POST['id'];	
}

$doctorSql = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$id'");
$doctorRow = $doctorSql->fetch_assoc();

$selectCiudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$doctorRow[dc_idCiudad]'");
$selectCiudadRow = $selectCiudadSql->fetch_assoc();

if($doctorRow['dc_genero']=='M'){ $tituloModal = "Doctor: "; }
elseif($doctorRow['dc_genero']=='F'){ $tituloModal = "Doctora: "; }
elseif($id==0){ $tituloModal = "Nuevo Doctor "; }

$arrayHoraInicio = explode(":", $doctorRow['dc_atencionDe']);
$arrayHoraSalida = explode(":", $doctorRow['dc_atencionHasta']);
$arrayHoraLibreInicio = explode(":", $doctorRow['dc_horarioLibreDe']);
$arrayHoraLibreSalida = explode(":", $doctorRow['dc_horarioLibreHasta']);

@$minutosHoraInicio = ( $arrayHoraInicio[0] * 60 ) + $arrayHoraInicio[1];
@$minutosHoraSalida = ( $arrayHoraSalida[0] * 60 ) + $arrayHoraSalida[1];
@$minutosHoraLibreInicio = ( $arrayHoraLibreInicio[0] * 60 ) + $arrayHoraLibreInicio[1];
@$minutosHoraLibreSalida = ( $arrayHoraLibreSalida[0] * 60 ) + $arrayHoraLibreSalida[1];

?>
<style>
	#sliderRangeCoral .slider-selection { background: lightcoral; }
</style>
<form class="form" id="formDoctor" method="post" action="doctor-guardar.php" enctype="multipart/form-data">
	<div class="modal-header modal-header-form">
	  <div class="titulo tituloSecundario"><?php echo $tituloModal.$doctorRow['dc_nombres']; ?></div>
	  <button class="boton boton-primario">Guardar</button>
	</div>

		<div class="contenedorTabs">
			<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Información</label>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Especialidades</label>
		<?php if($id){ ?>
			<input id="tab-3" type="radio" name="tab-group" />
			<label for="tab-3" class="labelTab">Citas</label>
			<input id="tab-4" type="radio" name="tab-group" />
			<label for="tab-4" class="labelTab">Recetados</label>
			<input id="tab-5" type="radio" name="tab-group" />
			<label for="tab-5" class="labelTab">Horarios Personalizados</label>
			<input id="tab-6" type="radio" name="tab-group" />
			<label for="tab-6" class="labelTab">Mis referidos</label>
		<?php } ?>
			<div class="contenidoTab">
				<div class="divForm" id="content-1">
					<div class="contentAvatar">
						<div class="avatar">
							<div title="Click para cambiar imagen" id="msjPhoto" class="cargaImg doc" onclick="$('#filePhoto').click()">
								<?php
									if($doctorRow['dc_foto']!=''){ echo "<img src='$doctorRow[dc_foto]'/>"; }
								?>				
							</div>
				        	<input type="file" accept="image/png, .jpeg, .jpg, .bmp" name="filePhoto" id="filePhoto">
						</div>
						<div>
							<div class="contenedorRadio">
								<input type="radio" id="m" name="genero" value="M" <?php if($doctorRow['dc_genero']=='M' || $id==0){echo"checked";}?>>
								<label for="m" class="labelRadio">Masculino</label>
								<input type="radio" id="f" name="genero" value="F" <?php if($doctorRow['dc_genero']=='F'){echo"checked";} ?>>
								<label for="f" class="labelRadio">Femenino</label>
							</div>
							<div class="container3PartForm">
								<input type="text" name="nombre" value="<?php echo $doctorRow['dc_nombres'] ?>" class="formulario__input top" data-label="Nombre Completo" required>
								<span></span>
								<input type="text" name="tarjeta" value="<?php echo $doctorRow['dc_tarjeta'] ?>" class="formulario__input" data-label="Tarjeta Profesional" required>
							</div>
							<div class="container3PartForm">
								<select name="tipoIdentificacion" class="formulario__input" data-label="Tipo de Identificación">
									<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php
										$tipoIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE ti_estado='1' ORDER BY ti_nombre");
						            	while($tipoIdentiRow = $tipoIdentiSql->fetch_assoc()){
						            		$tipoIdentiSelected = '';
						            		if($tipoIdentiRow['IDTipoIdentificacion']==$doctorRow['dc_idIdentificacion']){ $tipoIdentiSelected = "selected"; }
						            		echo "<option value=".$tipoIdentiRow['IDTipoIdentificacion']." ".$tipoIdentiSelected.">".$tipoIdentiRow['ti_label']."</option>";	
										}
						            ?>
					            </select>
					            <span></span>
								<input type="text" name="identificacion" value="<?php echo $doctorRow['dc_identificacion'] ?>" class="formulario__input" data-label="Número de Identificacion">
							</div>
							<div class="container3PartForm">
								<input type="text" name="telefono" value="<?php echo $doctorRow['dc_telefonoFijo'] ?>" class="formulario__input" data-label="Teléfono Fijo">
								<span></span>
								<input type="text" name="celular" value="<?php echo $doctorRow['dc_telefonoCelular'] ?>" class="formulario__input" data-label="Telefono Celular">
							</div>
						</div>
					</div>
					<div class="container1Part">
						<input type="email" name="correo" value="<?php echo $doctorRow['dc_correo'] ?>" class="formulario__input" data-label="Correo Electronico">
					</div>
					<div class="container3PartForm">
			            <select name="ciudad" id="ciudad" class="formulario__input" data-label="Ciudad">
								<?php
									if($doctorRow['dc_idCiudad']!=0){
						            	echo "<option value=".$selectCiudadRow['IDCiudad']." selected>".$selectCiudadRow['cd_nombre']."</option>";
						            }
						        ?>
					    </select>
					    <span></span>
			            <input type="text" name="direccion" value="<?php echo $doctorRow['dc_direccion'] ?>" class="formulario__input" data-label="Dirección">
			        </div>

			        <div class="tituloRange">Horario general de atención</div>
		            <div class="containerPartRange">
						<span id="horaInicioHorario">--</span>
						<input id="rangoHorario" type="text" value="" data-slider-min="0" data-slider-max="1425" data-slider-step="15" data-slider-value="[<?= $minutosHoraInicio.','.$minutosHoraSalida ?>]"/>
						<span id="horaFinalHorario">--</span>
			        </div>

			        <input type="hidden" id="horarioDcDe" name="horarioDcDe">
					<input type="hidden" id="horarioDcHasta" name="horarioDcHasta">
					<input type="hidden" id="horarioLibreDcDe" name="horarioLibreDcDe">
					<input type="hidden" id="horarioLibreDcHasta" name="horarioLibreDcHasta">

					<div class="tituloRange">Bloque de descanso <i>(opcional)</i></div>
		            <div class="containerPartRange">
						<span id="horaInicioHorarioLibre">--</span>
						<input id="rangoHorarioLibre" type="text" value="" data-slider-min="0" data-slider-max="1425" data-slider-step="15" data-slider-id="sliderRangeCoral" data-slider-value="[<?= $minutosHoraLibreInicio.','.$minutosHoraLibreSalida ?>]"/>
						<span id="horaFinalHorarioLibre">--</span>
			        </div>
					<div class="contenedorCheckbox SliderSwitch pointer">
						<label for="switch">Enviar notificaciones
						<input id="switch" type="checkbox" name="enviarAlertas" value="1" <?php if($doctorRow['dc_enviarCorreo']==1){echo"checked";} ?>>
						<div class="SliderSwitch__container">
							<div class="SliderSwitch__toggle"></div>
						</div>
						</label>
					</div>
					<div class="containerFirmas">
						<div class="content_signature">

						<?php if (!$doctorRow['dc_firma']){ ?>
							<div id="firma_doctor_image" class="ocultar">
						<?php } else { ?>
							<div id="firma_doctor_image" class="">
						<?php } ?>
								<img src="<?php echo $doctorRow['dc_firma'] ?>">
							</div>
						
						<?php if (!$doctorRow['dc_firma']){ ?>
							<canvas id="signature_pad_concent_doctor" class="signature_pad" width=400 height=200></canvas>
						<?php } else { ?>
							<canvas id="signature_pad_concent_doctor" class="signature_pad ocultar" width=400 height=200></canvas>
						<?php } ?>

							<div class="option_signature_pad">
								Firma
								<span id="clear_signature_concent_doctor" title="Limpiar"><i class="fa fa-times"></i></span>
							</div>

							<div class="option_signature_botton boton" onclick="$('#firma_file_doctor').click()">Cargar imágen</div>

							<input type="hidden" name="firma_concent_doctor" id="firma_concent_doctor">
							<input type="file" accept="image/png, .jpeg, .jpg, .bmp" style="display:none" id="firma_file_doctor">

						</div>
					</div>
				</div>
				

				<div class="divForm" id="content-2">
					<div class="container6PartDivision">
					<?php $especialidades = $con->query("SELECT * FROM especialidades WHERE esp_idClinica='$sessionClinica' AND esp_estado='1'");
						while($especialidadesRow = $especialidades->fetch_assoc()){
							$especialidadCheck = '';
							$especialidadDoctor = $con->query("SELECT * FROM doctoresespecialidades WHERE de_idDoctor='$id' AND de_idEspecialidad='$especialidadesRow[IDEspecialidad]'")->fetch_assoc();
							if($especialidadDoctor){
								$especialidadCheck = 'checked';
							}		
					?>
						<div class="contenedorCheckbox">
							<input type="checkbox" id="<?php echo 'especialidad_'.$especialidadesRow['IDEspecialidad'] ?>" name="<?php echo 'especialidad_'.$especialidadesRow['IDEspecialidad'] ?>" value="1" <?php echo $especialidadCheck ?>>
							<label for="<?php echo 'especialidad_'.$especialidadesRow['IDEspecialidad'] ?>" class="labelChek"><?php echo $especialidadesRow['esp_nombre'] ?></label>
						</div>
						<span></span>
					<?php } ?>
					</div>
				</div>


				<div class="divForm" id="content-3">
					
					<div id="msj-evolucion" class="contenedorAlerta"></div>
					<div class="container7PartForm">
						<select id="hsDoctorTipoEvolucion" class="formulario__input" data-label="Tipo evolución" onchange="paginationHsDoctorCitas();">
							<option selected value="">-- Seleccionar --</option>
							<option value="1">Evolucionadas</option>
							<option value="2">Sin evolución</option>
						</select>
						<select id="hsDoctorTratamiento" class="formulario__input" data-label="Tratamiento" onchange="paginationHsDoctorCitas();">
							<option selected value="">-- Seleccionar --</option>
							<?php
								$hsDcTratamientos = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' ORDER BY tr_nombre ASC");
								while($hsDcTratamientosRow = $hsDcTratamientos->fetch_assoc()){
									echo "<option value=".$hsDcTratamientosRow['IDTratamiento'].">".$hsDcTratamientosRow['tr_nombre']."</option>";
								}
							?>
						</select>
						<input type="date" id="dateRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationHsDoctorCitas();">
						<input type="date" id="dateRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationHsDoctorCitas();">
					</div>
					<?php 
						$historialDoctorQuery = "SELECT * FROM citas, pacientes, sucursales, tratamientos WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idDoctor = '$id' ORDER BY citas.ct_fechaOrden DESC";

						$rowCountHsDoctorCitas = $con->query($historialDoctorQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountHsDoctorCitas,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationHsDoctorCitas'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $historialDoctorSql = $con->query($historialDoctorQuery." LIMIT $numeroResultados");

					    if($rowCountHsDoctorCitas > 0){
					?>
							<div class="containerPart titulo tituloSecundario"><span></span><a href="excel-citas-doctor.php?id=<?php echo $id ?>"><i class="fa fa-download"></i>Historial Citas</a></div>
					<?php } ?>

					<div id="showResultsHsDoctorCitas">
						<table class="tableList">
							<thead>
								<tr>
									<th class="estado">&nbsp</th>
									<th class="columnaCorta">Fecha de Cita</th>
									<th>Sucursal | Unidad</th>
									<th>Tratamiento</th>
									<th>Paciente</th>
									<th>Estado tratamiento</th>
									<th class="columnaTCita">&nbsp</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($HistoDocRow = $historialDoctorSql->fetch_assoc()){

										$pacienteUrl = str_replace(" ","-", $HistoDocRow['pc_nombres']);
										
										if($HistoDocRow['ct_terminado']==3){
						            		$estadoHDoc = 'Terminado '.$HistoDocRow['ct_terminadoFecha'];
						            	} else { $estadoHDoc = 'Activo'; }

										$fechaCitaHD = $HistoDocRow['ct_anoCita'].'/'.$HistoDocRow['ct_mesCita'].'/'.$HistoDocRow['ct_diaCita'].' '.$HistoDocRow['ct_horaCita'];

										$estadoEvolucionHD = 'iconGray';
                                
                                        if( $HistoDocRow['ct_estado']==2){
                                            $titleEstadoHD = 'Cancelada';
                                            $estadoCitaHD = ' estadoCancelado ';
                                            $estadoEvolucionHD = 'icon-cancelada'; }
                                        else
                                        if( $HistoDocRow['ct_asistencia']==2){
                                            $titleEstadoHD = 'Realizada';
                                            $estadoCitaHD = ' cita-realizada ';
                                            $estadoEvolucionHD = 'icon-realizada'; }
                                        else
                                        if( $HistoDocRow['ct_asistencia']==1){
                                            $titleEstadoHD = 'Sin asistencia';
                                            $estadoCitaHD = ' cita-sinasistencia ';
                                            $estadoEvolucionHD = 'icon-sinasistencia'; }
                                        else
                                        if( $HistoDocRow['ct_evolucionada']==0 && ($HistoDocRow['ct_fechaInicio'].str_replace(':','',$HistoDocRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
                                            $titleEstadoHD = 'Sin evolución';
                                            $estadoCitaHD = ' cita-sinevolucion ';
                                            $estadoEvolucionHD = 'icon-sinevolucion'; }
                                        else
                                        if( $HistoDocRow['ct_estado']==1){
                                        	$titleEstadoHD = 'Confirmada';
                                            $estadoCitaHD = ' cita-confirmada ';
                                            $estadoEvolucionHD = 'icon-confirmada'; }
                                        else {
                                            $titleEstadoHD = 'Creada';
                                            $estadoCitaHD = ' cita-creada ';
                                            $estadoEvolucionHD = 'icon-creada'; }


										if($HistoDocRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
										} else { $iSC = ''; $cSC = ''; }

										if($HistoDocRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
										} else { $iPC = ''; $cPC = ''; }

										if($HistoDocRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
										} else { $iTR = ''; $cTR = ''; }

										if($HistoDocRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
						   				else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

						   				$unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$HistoDocRow[ct_idUnidad]'")->fetch_assoc();
								?>
										<tr>
											<td class="estado <?php echo $estadoCitaHD ?>" title="<?= $titleEstadoHD ?>"></td>
											<td><?php echo $fechaCitaHD ?></td>
											<td class="<?php echo $cSC ?>"><?php echo $iSC.$HistoDocRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td>
											<td class="<?php echo $cTR ?>"><?php echo $iTR.$HistoDocRow['tr_nombre'] ?></td>
											<td class="<?php echo $cPC ?>"><?php echo $iPC.$HistoDocRow['pc_nombres'] ?></td>
											<td align="center"><?php echo $estadoHDoc ?></td>
											<td class="columnaTCita"><?php echo $tipoCita ?></td>
											<td class="tableOption">
												<?php if($HistoDocRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
				                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $HistoDocRow["IDCita"] ?>&id=<?php echo $HistoDocRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
				                                <?php } elseif($HistoDocRow['ct_estado'] < 2) { ?>
				                                    <a title="<?= $titleEstadoHD ?>" id="<?php echo $HistoDocRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucionHD ?>"><i class="fa fa-share-alt"></i></a>
				                                <?php } ?>
				                                <a title="Información Cita" data-id="<?php echo $HistoDocRow['IDCita'] ?>" data-extra="<?= $id ?>" data-div="showResultsHsDoctorCitas" data-site="dc_citas" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
											</td>
										</tr>
									<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>


				<div class="divForm" id="content-4">
					<?php $recetaDoctorQuery = "SELECT * FROM citas, citamedicamentos, vadecum, pacientes WHERE citamedicamentos.cm_idCita = citas.IDCita AND citamedicamentos.cm_idVadecum = vadecum.IDVadecum AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idDoctor = '$id'";

						$rowCountRecetaDoctor = $con->query($recetaDoctorQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountRecetaDoctor,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationRecetaDoctor'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $recetaDoctorSql = $con->query($recetaDoctorQuery." LIMIT $numeroResultados");

					?>
					<div id="showResultsRecetaDoctor">
						<table class="tableList tableSinheight tablePadding">
							<thead>
								<tr>
									<th class="columnaCorta">Fecha asignación</th>
									<th>Cant.</th>
									<th>Medicamento</th>
									<th>Paciente</th>
								</tr>
							</thead>
							<tbody>
								<?php while($recetaDoctorRow = $recetaDoctorSql->fetch_assoc()){

										if($recetaDoctorRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
										} else { $iPC = ''; $cPC = ''; }
								?>
								<tr>
									<td><?php echo $recetaDoctorRow['cm_fechaCreacion'] ?></td>
									<td><?php echo $recetaDoctorRow['cm_cantidad'] ?></td>
									<td class="selectMedicamento"><?php echo '<span>'.$recetaDoctorRow['vd_medicamento']
				                        				.'</span><i>'.$recetaDoctorRow['vd_presentacion'].'</i>' ?></td>
									<td class="<?php echo $cPC ?>"><?php echo $iPC.$recetaDoctorRow['pc_nombres'] ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>


				<div class="divForm" id="content-5">
					<div id="msjNuevoHorario"></div>
					<?php $horariosPersonalizadosQuery = "SELECT * FROM doctoreshorarios WHERE dch_idDoctor = '$id' ORDER BY dch_fechaInt DESC";

						$rowCountHorarioPersonalizado = $con->query($horariosPersonalizadosQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountHorarioPersonalizado,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationHorariosPersonalizados'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $horariosPersonalizadosSql = $con->query($horariosPersonalizadosQuery." LIMIT $numeroResultados");

					?>
					<div class="titulo tituloSecundario"><a class="consultorioNuevoHorario"><?php echo $iconoNuevo ?>Nuevo horario</a></div>
					<div id="showHorariosPersonalizados">
						<table class="tableList">
							<thead>
								<tr>
									<th class="columnaCorta">Fecha</th>
									<th>Horario de atención</th>
									<th>Bloque de descanso</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($horariosPersonalizadosRow = $horariosPersonalizadosSql->fetch_assoc()){ ?>
								<tr>
									<td><?php echo $horariosPersonalizadosRow['dch_fecha'] ?></td>
									<td class="centro"><?php echo $horariosPersonalizadosRow['dch_atencionDe'].' / '.$horariosPersonalizadosRow['dch_atencionHasta'] ?></td>
									<td class="centro"><?php echo $horariosPersonalizadosRow['dch_horarioLibreDe'].' / '.$horariosPersonalizadosRow['dch_horarioLibreHasta'] ?></td>
									<td class="tableOption">
										<a title="Eliminar" id="<?php echo $horariosPersonalizadosRow['IDDocHorario'] ?>" class="consultorioEliminarHorario eliminar"><?php echo $iconoEliminar ?></a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>


				<div class="divForm" id="content-6">
					<?php $dcReferidosQuery = "SELECT * FROM citas AS ct
									INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
									INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
									WHERE pc.pc_idReferido = 'D-$id' AND ct.ct_inicial = '1' ORDER BY ct.ct_fechaOrden DESC ";

					$rowCountDcReferidos = $con->query($dcReferidosQuery)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountDcReferidos,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationDcReferidos'
						);
					    $pagination =  new Pagination($pagConfig);

					$dcReferidosSql = $con->query($dcReferidosQuery." LIMIT $numeroResultados");

					if($rowCountDcReferidos>0){
					?>
					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><span class="cantRegistros" id="countDcReferidos">Cantidad: [<?php echo $rowCountDcReferidos ?>]</span></div>
						<div class="titulo_optional_search form">
							<a class="consultorioDescargar" data-page="doctor_referidos" data-rango-de="dcReferidoRangoDe" data-rango-hasta="dcReferidoRangoHasta" data-rango-id="<?= $id ?>"><i class="fa fa-download"></i>Descargar</a>
							<input type="date" id="dcReferidoRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationDcReferidos();">
							<input type="date" id="dcReferidoRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationDcReferidos();">
						</div>
					</div>
					<?php } ?>
							
					<div id="showResultsDcReferidos">
						<table class="tableList">
							<thead>
								<tr>
									<th class="columnaCorta">Fecha</th>
									<th>Paciente</th>
									<th>Tratamiento</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
								<?php while($dcReferidosRow = $dcReferidosSql->fetch_assoc()){ 
									$fechaDcReferido = $dcReferidosRow['ct_anoCita'].'/'.$dcReferidosRow['ct_mesCita'].'/'.$dcReferidosRow['ct_diaCita'];
								?>
								<tr>
									<td align="center"><?php echo $fechaDcReferido ?></td>
									<td><?php echo $dcReferidosRow['pc_nombres'] ?></td>
									<td><?php echo $dcReferidosRow['tr_nombre'] ?></td>
									<td align="right"><?php echo '$'.number_format($dcReferidosRow['ct_costo'], 0, ".", ",")  ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>

				</div>
			</div>
		</div>

	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<!--<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>-->
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label.js"></script>
<script type="text/javascript" src="js/cargaImg.js"></script>
<script type="text/javascript">

validar('#formDoctor');
$('#ciudad').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-ciudades.php',
		dataType: 'json',
		delay: 250,
		processResults: function (data){
			return {
				results: data.items,
				  "pagination": {
				    "more": data.pag
				  }
			};
		},
		cache: true
	}
});

// Rango de Horario
$('#rangoHorario').slider({});
$('#rangoHorarioLibre').slider({});

$("#rangoHorario").on("slide", function(slideEvt) {
	sumar_minutos( slideEvt.value[0], 'horaInicioHorario' , 'horarioDcDe' );
	sumar_minutos( slideEvt.value[1], 'horaFinalHorario' , 'horarioDcHasta' );
});
$("#rangoHorarioLibre").on("slide", function(slideEvt) {
	sumar_minutos( slideEvt.value[0], 'horaInicioHorarioLibre' , 'horarioLibreDcDe' );
	sumar_minutos( slideEvt.value[1], 'horaFinalHorarioLibre' , 'horarioLibreDcHasta' );
});

sumar_minutos( <?php echo $minutosHoraInicio ?>, 'horaInicioHorario' , 'horarioDcDe' );
sumar_minutos( <?php echo $minutosHoraSalida ?>, 'horaFinalHorario' , 'horarioDcHasta' );
sumar_minutos( <?php echo $minutosHoraLibreInicio ?>, 'horaInicioHorarioLibre' , 'horarioLibreDcDe' );
sumar_minutos( <?php echo $minutosHoraLibreSalida ?>, 'horaFinalHorarioLibre' , 'horarioLibreDcHasta' );

	function sumar_minutos(valorMinuto, contenedor, input) {

		 var min_a_sumar= valorMinuto // toma los minutos
		 var fecha = new Date(0,0);
		 var nuevosMinutos = fecha.setMinutes(fecha.getMinutes() + valorMinuto);
		 var horaFinal = fecha.getHours();
		 var minutoFinal = fecha.getMinutes();
		 horaFinal = horaFinal < 10 ? '0'+horaFinal : horaFinal;
		 minutoFinal = minutoFinal < 10 ? '0'+minutoFinal : minutoFinal;

		 document.getElementById(contenedor).innerHTML = horaFinal+':'+minutoFinal;
		 document.getElementById(input).value = horaFinal+':'+minutoFinal;

	}


//FIRMA 
var signaturePad_concent_doctor = new SignaturePad(document.querySelector('#signature_pad_concent_doctor'), {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)'
});

var imageLoaderDoctor = document.getElementById('firma_file_doctor');
imageLoaderDoctor.addEventListener('change', handleImageDoctor, false);
function handleImageDoctor(e) {
	var reader = new FileReader();
	reader.onload = function (event) {
        $('#firma_doctor_image').html( '<img src="'+event.target.result+'"/>' );
        $('#firma_concent_doctor').val(event.target.result);
    }
	reader.readAsDataURL(e.target.files[0]);
    $('#firma_doctor_image').removeClass('ocultar');
    $('#signature_pad_concent_doctor').addClass('ocultar');
};

$(document).on('click', '#clear_signature_concent_doctor', function() {
    signaturePad_concent_doctor.clear();
    $('#firma_concent_doctor').val(null);
    $('#firma_doctor_image').addClass('ocultar');
    $('#signature_pad_concent_doctor').removeClass('ocultar');
});

$(document).on('mouseup', '#signature_pad_concent_doctor', function() {
    $('#firma_concent_doctor').val(document.querySelector('#signature_pad_concent_doctor').toDataURL());
});

<?php if($id){ ?>
		function paginationHsDoctorCitas(page_num) {
			page_num = page_num?page_num:0;
			var hsDoctorTipoEvolucion = $('#hsDoctorTipoEvolucion').val();
			var hsDoctorTratamiento = $('#hsDoctorTratamiento').val();
			var hsDoctorRangoDe = $('#dateRangoDe').val();
			var hsDoctorRangoHasta = $('#dateRangoHasta').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/hsDoctorCitasData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>+'&hsDoctorTipoEvolucion='+hsDoctorTipoEvolucion+'&hsDoctorTratamiento='+hsDoctorTratamiento+'&hsDoctorRangoHasta='+hsDoctorRangoHasta+'&hsDoctorRangoDe='+hsDoctorRangoDe,
		        success: function (html) {
		            $('#showResultsHsDoctorCitas').html(html);
		        }
		    });
		}

		function paginationRecetaDoctor(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/recetaDoctorData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsRecetaDoctor').html(html);
		        }
		    });
		}

		function paginationHorariosPersonalizados(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/dcHorariosPersonalizadosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showHorariosPersonalizados').html(html);
		        }
		    });
		}

		function paginationDcReferidos(page_num) {
			page_num = page_num?page_num:0;
			var dcReferidoRangoDe = $('#dcReferidoRangoDe').val();
			var dcReferidoRangoHasta = $('#dcReferidoRangoHasta').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/dcReferidosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>+'&dcReferidoRangoDe='+dcReferidoRangoDe+'&dcReferidoRangoHasta='+dcReferidoRangoHasta,
		        success: function (html) {
		            $('#showResultsDcReferidos').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultoriosEvolucion', function(){  
			var consultoriosId = $(this).attr("id");
			$(this).removeClass('iconGray');
			var consultoriosEv = 'doctor';
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

		$(document).on('click', '.consultorioNuevoHorario', function(){  
			var doctorID = <?php echo $id ?>;
		    if(doctorID > 0)
		    {
		    	$.ajax({
		        	url:"doctor-nuevo-horario.php",
			        method:"POST",
		            data:{id:doctorID}, 
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
			    });  
			}   
		});


		$(document).on('click', '.consultorioEliminarHorario', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId > 0)
		    {  
		    	$.ajax({
		        	url:"doctor-horario-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId},
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
<?php } ?>
</script>