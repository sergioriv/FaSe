<?php include'../config.php';

function __substr($var){
	$substr = $var;
	if( strlen( $var ) > 22 ){ $substr = rtrim( substr($var, 0, 22) ).'...'; }
	return $substr;
}

$fechaComparativo = explode("-", $_POST['fecha']);
$fechaComparativoInt = str_replace('-', '', $_POST['fecha']);
$anioComparativo = $fechaComparativo[0];
$mesComparativo = $fechaComparativo[1];
$diaComparativo = $fechaComparativo[2];

			$min = $con->query("SELECT MIN(sc_atencionDe) AS horaMin, MAX(sc_atencionHasta) AS horaMax FROM sucursales AS sc INNER JOIN unidadesodontologicas AS uo ON uo.uo_idSucursal = sc.IDSucursal WHERE sc_idClinica='$sessionClinica' AND sc_estado='1'")->fetch_assoc();

					$inicioDia = explode(':', $min['horaMin']);
					$inicioDia = $inicioDia[0].'00';

					$finDia = explode(':', $min['horaMax']);
					if( $finDia[0] == 23 ){ $finDia = " AND hr_horaInt <= '2355'"; }
					else{ $finDia = " AND hr_horaInt < '". ($finDia[0] + 1) ."00'"; }


			$unidadesComparativo = $con->query("SELECT IDSucursal, IDUnidadOdontologica, sc_nombre, uo_nombre, sc_atencionDe, sc_atencionHasta FROM unidadesodontologicas AS uo INNER JOIN sucursales AS sc ON uo.uo_idSucursal = sc.IDSucursal WHERE sc_idClinica = '$sessionClinica' AND uo_estado = '1' AND sc_estado = '1' ORDER BY sc_nombre ASC, uo_nombre ASC ");
						while($unidadesComparativoRow = $unidadesComparativo->fetch_assoc()){

							$sc_entrada = str_replace(':', '', $unidadesComparativoRow['sc_atencionDe']);
							$sc_salida = str_replace(':', '', $unidadesComparativoRow['sc_atencionHasta']);
							
							$unidad_cita = array();
							$unidad_horaDe = array();
							$unidad_horaHasta = array();

							$citasComparativo = $con->query("SELECT IDCita, ct_horaCitaDe, ct_horaCitaHasta FROM citas WHERE ct_idSucursal = '$unidadesComparativoRow[IDSucursal]' AND ct_idUnidad = '$unidadesComparativoRow[IDUnidadOdontologica]' AND ct_estado IN(0,1) AND ct_anoCita='$anioComparativo' AND ct_mesCita='$mesComparativo' AND ct_diaCita='$diaComparativo' ORDER BY ct_horaCitaDe ASC");
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


<script type="text/javascript">
	$('#dashComparativoFecha').hide();
	$('#dashComparativo').show();
	$('#dashComparativoTitle').html("<?php echo 'Fecha<br>'.$anioComparativo.'/'.$mesComparativo.'/'.$diaComparativo ?>");

<?php if( $fechaComparativoInt < $fechaHoySinEsp ){ ?>
	$('#dashComparativoCambiarDia').hide();
<?php } else { ?>
	$('#dashComparativoCambiarDia').show();
<?php } ?>
</script>