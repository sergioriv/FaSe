<?php include'config.php';

$id = $_POST['id'];
$tabla = $_POST['t'];

if($tabla=='paciente'){
	$query = $con->query("UPDATE pacientes SET pc_estado='0' WHERE IDPaciente = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=4;
		$_SESSION['consultoriosPacienteEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='doctor'){
	$query = $con->query("UPDATE doctores SET dc_estado='0' WHERE IDDoctor = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=5;
		$_SESSION['consultoriosDoctorEliminado']=$id;

		$con->query("UPDATE usuarios SET us_estado = '0' WHERE us_id = '$id' AND us_idRol=3");

	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='material'){
	$query = $con->query("UPDATE materiales SET mt_estado='0' WHERE IDMaterial = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=6;
		$_SESSION['consultoriosMaterialEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='sucursal'){
	$query = $con->query("UPDATE sucursales SET sc_estado='0' WHERE IDSucursal = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=7;
		$_SESSION['consultoriosSucursalEliminado']=$id;

		$con->query("UPDATE usuarios SET us_estado = '0' WHERE us_id = '$id' AND us_idRol=2");
		
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='proveedor'){
	$query = $con->query("UPDATE proveedores SET pr_estado='0' WHERE IDProveedor = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=8;
		$_SESSION['consultoriosProveedorEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='eps'){
	$query = $con->query("UPDATE eps SET eps_estado='0' WHERE IDEps = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=9;
		$_SESSION['consultoriosEpsEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='ciudad'){
	$query = $con->query("UPDATE ciudades SET cd_estado='0' WHERE IDCiudad = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=10;
		$_SESSION['consultoriosCiudadEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='tratamiento'){
	$query = $con->query("UPDATE tratamientos SET tr_estado='0' WHERE IDTratamiento = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=11;
		$_SESSION['consultoriosTratamientoEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='tipoIdenti'){
	$query = $con->query("UPDATE tiposidentificacion SET ti_estado='0' WHERE IDTipoIdentificacion = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=12;
		$_SESSION['consultoriosTipoIdentiEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='cita'){
	$query = $con->query("UPDATE citas SET ct_estado='2' WHERE IDCita = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=13;
		$_SESSION['consultoriosCitaEliminado']=$id;

		//ENVIAR CORREO AL PACIENTE DE LA CANCELACION
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='usInventario'){
	$query = $con->query("UPDATE usuariosinventario SET ui_estado='0' WHERE IDUserInventario = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=14;
		$_SESSION['consultoriosUserInventario']=$id;

		$con->query("UPDATE usuarios SET us_estado = '0' WHERE us_id = '$id' AND us_idRol=4");
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='usCitas'){
	$query = $con->query("UPDATE usuarioscitas SET uc_estado='0' WHERE IDUserCitas = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=17;
		$_SESSION['consultoriosUserCitas']=$id;

		$con->query("UPDATE usuarios SET us_estado = '0' WHERE us_id = '$id' AND us_idRol=5");
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='especialidad'){
	$query = $con->query("UPDATE especialidades SET esp_estado='0' WHERE IDEspecialidad = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=18;
		$_SESSION['consultoriosEspecialidadEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='combo'){
	$query = $con->query("UPDATE tratamientos SET tr_estado='0' WHERE IDTratamiento = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=19;
		$_SESSION['consultoriosComboEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='convenio'){
	$query = $con->query("UPDATE convenios SET cnv_estado='0' WHERE IDConvenio = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=20;
		$_SESSION['consultoriosConvenioEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}
if($tabla=='vendedor'){
	$query = $con->query("UPDATE vendedores SET vn_estado='0' WHERE IDVendedor = '$id'");
	if($query){
		$_SESSION['consultoriosExito']=21;
		$_SESSION['consultoriosVendedorEliminado']=$id;
	} else { $_SESSION['consultoriosExito']=1; }
}

if($tabla=='citaPaciente'){

	$query = $con->query("UPDATE citas SET ct_estado='2' WHERE IDCita = '$id'");
	if($query){ include('pagination-modal-params.php');

		$pcCitaEliminado = $con->query("SELECT citas.IDCita, citas.ct_idPaciente, pacientes.IDPaciente, pacientes.pc_nombres, citas.ct_anoCita, citas.ct_mesCita, citas.ct_diaCita, citas.ct_horaCita FROM citas, pacientes WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.IDcita = '$id'")->fetch_assoc();
		$pacienteUrl = str_replace(" ","-", $pcCitaEliminado['pc_nombres']);

		$pcCitasQuery = "SELECT * FROM citas, sucursales, doctores, tratamientos WHERE citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pcCitaEliminado[IDPaciente]' ORDER BY citas.ct_fechaOrden DESC";

		$rowCountPcCitas = $con->query($pcCitasQuery)->num_rows;
		$pagConfig = array(
			'totalRows' => $rowCountPcCitas,
		    'perPage' => $numeroResultados,
			'link_func' => 'paginationPcCitas'
		);
		$pagination =  new Pagination($pagConfig);

		$pcCitasSql = $con->query($pcCitasQuery." LIMIT $numeroResultados");
?>

								<table class="tableList">
									<thead>
										<tr>
											<th class="estado">&nbsp</th>
											<th class="columnaCorta">Fecha de Cita</th>
											<th>Sucursal | Unidad</th>
											<th>Doctor</th>
											<th>Tratamiento</th>
											<th class="columnaTCita">&nbsp</th>
											<th>&nbsp</th>
										</tr>
									</thead>
									<tbody class="list">
										<?php
											while($pcCitasRow = $pcCitasSql->fetch_assoc()){
												
												$estadoEvolucion = 'iconGray';
								
												if( $pcCitasRow['ct_estado']==2){
				                                    $titleEstado = 'Cancelada';
				                                    $estadoCita = ' estadoCancelado ';
				                                    $estadoEvolucion = 'icon-cancelada'; }
				                                else
				                                if( $pcCitasRow['ct_asistencia']==2){
				                                    $titleEstado = 'Realizada';
				                                    $estadoCita = ' cita-realizada ';
				                                    $estadoEvolucion = 'icon-realizada'; }
				                                else
				                                if( $pcCitasRow['ct_asistencia']==1){
				                                    $titleEstado = 'Sin asistencia';
				                                    $estadoCita = ' cita-sinasistencia ';
				                                    $estadoEvolucion = 'icon-sinasistencia'; }
				                                else
				                                if( $pcCitasRow['ct_evolucionada']==0 && ($pcCitasRow['ct_fechaInicio'].str_replace(':','',$pcCitasRow['ct_horaCita']))<=$fechaHoySinEsp.date('Hi') ){
				                                    $titleEstado = 'Sin evolución';
				                                    $estadoCita = ' cita-sinevolucion ';
				                                    $estadoEvolucion = 'icon-sinevolucion'; }
				                                else
				                                if( $pcCitasRow['ct_estado']==1){
				                                    $titleEstado = 'Confirmada';
				                                    $estadoCita = ' cita-confirmada ';
				                                    $estadoEvolucion = 'icon-confirmada'; }
				                                else {
				                                    $titleEstado = 'Creada';
				                                    $estadoCita = ' cita-creada ';
				                                    $estadoEvolucion = 'icon-creada'; }


												if($pcCitasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
												} else { $iSC = ''; $cSC = ''; }

												if($pcCitasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
												} else { $iDC = ''; $cDC = ''; }

												if($pcCitasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
												} else { $iTR = ''; $cTR = ''; }

												if($pcCitasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
						   						else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

						   						$unidadRow = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$pcCitasRow[ct_idUnidad]'")->fetch_assoc();
										?>
											<tr>
												<td class="estado <?php echo $estadoCita ?>" title="<?= $titleEstado ?>"></td>
												<td class="columnaCorta"><?php echo $pcCitasRow['ct_anoCita'].'/'.$pcCitasRow['ct_mesCita'].'/'.$pcCitasRow['ct_diaCita'].' '.$pcCitasRow['ct_horaCita']; ?></td>
												<td class="<?php echo $cSC ?>"><?php echo $iSC.$pcCitasRow['sc_nombre'].' <b>|</b> '.$unidadRow['uo_nombre'] ?></td>
												<td class="<?php echo $cDC ?>"><?php echo $iDC.$pcCitasRow['dc_nombres']?></td>
												<td class="<?php echo $cTR ?>"><?php echo $iTR.$pcCitasRow['tr_nombre'] ?></td>
						    					<td class="columnaTCita"><?php echo $tipoCita ?></td>
												<td class="tableOption">

												<?php if($pcCitasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
				                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $pcCitasRow["IDCita"] ?>&id=<?php echo $pcCitaEliminado['IDPaciente'] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
				                                <?php } elseif($pcCitasRow['ct_estado'] < 2){ ?>
				                                    <a title="<?= $titleEstado ?>" id="<?php echo $pcCitasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
				                                <?php } ?>
				                                <a title="Información Cita" data-id="<?php echo $pcCitasRow['IDCita'] ?>" data-extra="<?= $id ?>" data-div="showResultsPcCitas" data-site="pc_citas" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
												</td>
											</tr>
										<?php } ?>
									</tbody>		
								</table>
								<?php echo $pagination->createLinks(); ?>


		<script>
			$('.contenedorAlerta').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Se ha Cancelado la cita del paciente <b><?php echo $pcCitaEliminado["pc_nombres"]." | ".$pcCitaEliminado["ct_anoCita"]."/".$pcCitaEliminado["ct_mesCita"]."/".$pcCitaEliminado["ct_diaCita"]." ".$pcCitaEliminado["ct_horaCita"] ?></b>.<a class="deshacer">Deshacer</a></div><div class="close">&times;</div></label>');

			$(document).on('click', '.deshacer', function(){
				$.ajax({
		        	url:"activar-guardar.php",
		            method:"POST",  
		            data:{id:<?php echo $id ?>,t:'citaPaciente'},  
		            success:function(data){  
						$('#showResultsPcCitas').html(data); 
					}
		    	});
			});
		</script>


<?php } else {
			$_SESSION['consultoriosExito']=1;
?>
			<script>window.location="pacientes";</script>
<?php
		}

} 

if($tabla=='citaDoctor') {

		$query = $con->query("UPDATE citas SET ct_estado='2' WHERE IDCita = '$id'");
		if($query){ include('pagination-modal-params.php');

			$dcCitaEliminado = $con->query("SELECT citas.IDCita, citas.ct_idPaciente, pacientes.IDPaciente, pacientes.pc_nombres, citas.ct_anoCita, citas.ct_mesCita, citas.ct_diaCita, citas.ct_horaCita, citas.ct_idDoctor FROM citas, pacientes WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.IDcita = '$id'")->fetch_assoc();
			$pacienteUrl = str_replace(" ","-", $dcCitaEliminado['pc_nombres']);

						$historialDoctorQuery = "SELECT * FROM citas, pacientes, sucursales, tratamientos WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idDoctor = '$dcCitaEliminado[ct_idDoctor]' ORDER BY citas.ct_fechaOrden DESC";

						$rowCountHsDoctorCitas = $con->query($historialDoctorQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountHsDoctorCitas,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationHsDoctorCitas'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $historialDoctorSql = $con->query($historialDoctorQuery." LIMIT $numeroResultados");
?>
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

			<script>
				$('.contenedorAlerta').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Se ha Cancelado la cita del paciente <b><?php echo $dcCitaEliminado["pc_nombres"]." | ".$dcCitaEliminado["ct_anoCita"]."/".$dcCitaEliminado["ct_mesCita"]."/".$dcCitaEliminado["ct_diaCita"]." ".$dcCitaEliminado["ct_horaCita"] ?></b>.<a class="deshacer">Deshacer</a></div><div class="close">&times;</div></label>');

				$(document).on('click', '.deshacer', function(){
					$.ajax({
			        	url:"activar-guardar.php",
			            method:"POST",  
			            data:{id:<?php echo $id ?>,t:'citaDoctor'},  
			            success:function(data){  
							$('#showResultsHsDoctorCitas').html(data); 
						}
			    	});
				});
			</script>

<?php

		} else {
			$_SESSION['consultoriosExito']=1;
?>
			<script>window.location="doctores";</script>
<?php
		}

} 


if($tabla!='citaDoctor' && $tabla!='citaPaciente')
{
	header("Location:$_SESSION[concultoriosAntes]");
}
?>