<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$id = $_POST['id'];

$citaSql = $con->query("SELECT * FROM citas, pacientes, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.IDCita = '$id'");
$citaRow = $citaSql->fetch_assoc();

$ripRow = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip]'")->fetch_assoc();
$rip1Row = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip1]'")->fetch_assoc();
$rip2Row = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip2]'")->fetch_assoc();
$rip3Row = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip3]'")->fetch_assoc();
$externa = $con->query("SELECT * FROM causaexterna");
$finalidad = $con->query("SELECT * FROM finalidadconsulta");
?>
	<script type="text/javascript">
		$('#tab-100').click();
		$('#ch100').click(function() {
			$('#tab-100').each(function(){
				$(this).click();
			})
		});
	</script>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Evolución cita: <?php echo $citaRow['ct_anoCita'].'/'.$citaRow['ct_mesCita'].'/'.$citaRow['ct_diaCita'].' '.$citaRow['ct_horaCita'].' | '.$citaRow['pc_nombres'] ?></h4>
</div>
<form class="form" method="post" id="evolucionForm" action="cita-evolucionar-guardar.php">
	<div class="modal-body">
		<div class="divForm">

			<?php if($citaRow['ct_asistencia']>0){
				if($citaRow['ct_asistencia']==2){
					echo "<input type='checkbox' id='tab-100' disabled checked><input type='hidden' name='asistencia' value='1'>";
				} else {
					echo "<input type='checkbox' id='tab-100' disabled><input type='hidden' name='asistencia' value='0'>";
				}
			} else { echo "<input type='checkbox' id='tab-100'>"; } ?>


			<div class="containerPart titulo tituloSecundario">
				<div class="contenedorCheckbox SliderSwitch 
				<?php if($citaRow['ct_asistencia']==0){ echo 'pointer'; } ?>">
					<label>Asistencia del paciente
						
						<?php if($citaRow['ct_asistencia']>0){
									if($citaRow['ct_asistencia']==2){
										echo "<input type='checkbox' checked disabled>";
									} else {
										echo "<input type='checkbox' disabled>";
									}
								} else { echo "<input type='checkbox' name='asistencia' value='1' id='ch100' checked>"; } ?>


						
						<div class="SliderSwitch__container">
							<div class="SliderSwitch__toggle"></div>
						</div>
					</label>
				</div>

				<div id="descargaEvolucion">
					<a href="cita-evolucion-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Descargar Evolución</a>
					<a href="cita-medicamentos-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Descargar Medicamentos</a>
				</div>
			</div>


			<div class="formEvolucion">

			<div class="divForm" id="content-100">
				<div class="contenedorTabs">
				    <input id="tab-101" type="radio" name="tab-group" checked />
					<label for="tab-101" class="labelTab">Descripción</label>
				    <input id="tab-102" type="radio" name="tab-group" />
					<label for="tab-102" class="labelTab">Diagnosticos</label>
				    <input id="tab-103" type="radio" name="tab-group" />
					<label for="tab-103" class="labelTab">Medicamentos</label>
				    <input id="tab-104" type="radio" name="tab-group" />
					<label for="tab-104" class="labelTab">Tarea</label>


					<div class="contenidoTab">

				        <div class="divForm" id="content-101">
				        	<div class="container1Part">
				        		<textarea name="descripcion" id="descripcion" rows="8" class="formulario__modal__input top" data-label="Descripción"><?php echo $citaRow['ct_descripcion'] ?></textarea>
				        	</div>
							<div class="container3PartForm">
								<select name="finalidad" class="formulario__modal__input" data-label="Finalidad de Consulta">
									<option <?php if($citaRow['ct_idFinalidad']==0){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php										
									  	while($finalidadRow = $finalidad->fetch_assoc()){
									   		$finalidadSelect = '';
									   		if($finalidadRow['IDFinalidadConsulta']==$citaRow['ct_idFinalidad']){ $finalidadSelect = "selected"; }
									  		echo "<option value='".$finalidadRow['IDFinalidadConsulta']."' ".$finalidadSelect.">".$finalidadRow['fc_nombre']."</option>";	
										}
									?>
								</select>
								<span></span>
								<select name="externa" class="formulario__modal__input" data-label="Causa Externa">
									<option <?php if($citaRow['ct_idCausaExterna']==0){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php										
									   	while($externaRow = $externa->fetch_assoc()){
									  		$externaSelect = '';
									   		if($externaRow['IDCausaExterna']==$citaRow['ct_idCausaExterna']){ $externaSelect = "selected"; }
									   		echo "<option value='".$externaRow['IDCausaExterna']."' ".$externaSelect.">".$externaRow['ce_nombre']."</option>";	
										}
									?>
								</select>
							</div>
<!--
							<div class="contenedorCheckbox SliderSwitch 
						    	<?php if($citaRow['ct_terminado']<3){ echo ' pointer'; } ?>">
								<label>¿Dar por terminado el tratamiento: &nbsp <strong><?php echo $citaRow['tr_nombre'] ?></strong>?

									<?php if($citaRow['ct_terminado']==3){
										echo "<input id='switch' type='checkbox' value='1' checked disabled>";
									} else { echo "<input id='switch' type='checkbox' name='tratamiento' value='1'>"; } ?>
									
									<div class="SliderSwitch__container">
										<div class="SliderSwitch__toggle"></div>
									</div>
								</label>
							</div>
-->
							<div id="progress_title">Progreso del tratamiento: <b><?= $citaRow['tr_nombre'] ?></b></div>
							<?php
								$progress_tratamiento_inicial = $con->query("SELECT IDCita FROM citas WHERE ct_idTratamiento = '$citaRow[ct_idTratamiento]' AND ct_idPaciente = '$citaRow[ct_idPaciente]' AND ct_inicial = '1' AND IDCita <= '$id' ORDER BY IDCita DESC")->fetch_assoc();

								$progress_tratamiento_suma = $con->query("SELECT SUM(ct_trataPorcentaje) AS porcentaje FROM citas 
									WHERE ct_idTratamiento = '$citaRow[ct_idTratamiento]' AND ct_idPaciente = '$citaRow[ct_idPaciente]' AND IDCita BETWEEN '$progress_tratamiento_inicial[IDCita]' AND '$id' ORDER BY IDCita DESC")->fetch_assoc();

								$trataProcentaje_inicial = $progress_tratamiento_suma['porcentaje'];

							?>

							<div class="content_around <?php if($citaRow[ct_evolucionada]==1){ echo 'disabled'; } ?>">
								<?php for ($i=100; $i >= 10 ; $i = $i-10) { 
									$progress_selected = '';
									if($trataProcentaje_inicial >= $i){ $progress_selected = ' selected active'; }
								?>
																	
											<label class="item-progress<?= $progress_selected ?>">% <?= $i ?>
												<input type="radio" name="trata_progress" value="<?= $i ?>" id="trata_progress_<?= $i ?>">
											</label>									
							        
						        <?php } ?>
						        <input type="hidden" id="progress_porcentaje_inicial" value="<?= $trataProcentaje_inicial ?>">
						        <input type="hidden" name="progress_tratamiento" id="progress_tratamiento" value="<?= $citaRow['ct_trataPorcentaje'] ?>">
					        </div>


							<div class="containerFirmas">
								<div class="content_signature">
									<?php if($citaRow['ct_evolucionada']==1){ ?>

										<?php if(!empty($citaRow['ct_evoFirmaPaciente'])){ ?>
											<img src="<?php echo $citaRow['ct_evoFirmaPaciente'] ?>">
										<?php } ?>

									    <div class="option_signature_pad">
											Firma Paciente
										</div>

									<?php } else { ?>

										<canvas id="signature_pad_evo_paciente" class="signature_pad" width=400 height=200></canvas>

										<div class="option_signature_pad">
											Firma Paciente
											<span id="clear_signature_evo_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
										</div>

										<input type="hidden" name="firma_evo_paciente" id="firma_evo_paciente">

									<?php } ?>
									
								</div>

								<div class="content_signature">
									<?php if($citaRow['ct_evolucionada']==1){ ?>

										<?php if(!empty($citaRow['ct_evoFirmaUsuario'])){ ?>
											<img src="<?php echo $citaRow['ct_evoFirmaUsuario'] ?>">
										<?php } ?>

									    <div class="option_signature_pad">
											Firma Usuario
										</div>

									<?php } else { ?>

										<canvas id="signature_pad_evo_usuario" class="signature_pad" width=400 height=200></canvas>

										<div class="option_signature_pad">
											Firma Usuario
											<span id="clear_signature_evo_usuario" title="Limpiar"><i class="fa fa-times"></i></span>
										</div>

										<input type="hidden" name="firma_evo_usuario" id="firma_evo_usuario">

									<?php } ?>
								</div>
							</div>
				        </div>


				        <div class="divForm" id="content-102">
				        			<div class="container3PartForm contRips">
										<select name="rips" id="rips" class="formulario__modal__input" data-label="CIE 10 DX Ppal.">
											<?php
												if($citaRow['ct_idRip']!=0){
															echo "<option value=".$ripRow['IDRips']." selected>".$ripRow['rip_codigo'].' | '.$ripRow['rip_nombre']."</option>";
												}
												?>
										</select>
										<span></span>
										<select name="rips1" id="rips1" class="formulario__modal__input" data-label="CIE 10 DX Rel. 1">
											<?php
												if($citaRow['ct_idRip1']!=0){
															echo "<option value=".$rip1Row['IDRips']." selected>".$rip1Row['rip_codigo'].' | '.$rip1Row['rip_nombre']."</option>";
												}
												?>
										</select>
									</div>

									<div class="container3PartForm contRips">
										<select name="rips2" id="rips2" class="formulario__modal__input" data-label="CIE 10 DX Rel. 2">
											<?php
												if($citaRow['ct_idRip2']!=0){
															echo "<option value=".$rip2Row['IDRips']." selected>".$rip2Row['rip_codigo'].' | '.$rip2Row['rip_nombre']."</option>";
												}
												?>
										</select>
										<span></span>
										<select name="rips3" id="rips3" class="formulario__modal__input" data-label="CIE 10 DX Rel. 3">
											<?php
												if($citaRow['ct_idRip3']!=0){
															echo "<option value=".$rip3Row['IDRips']." selected>".$rip3Row['rip_codigo'].' | '.$rip3Row['rip_nombre']."</option>";
												}
												?>
										</select>
									</div>
				        </div>


				        <div class="divForm" id="content-103">
				        	<input type="hidden" id="medicamentoCitaID" value="<?php echo $id ?>">
				        	<div class="contRips container5Part">
								<input type="number" id="cantidadMedicamento" min="0" class="formulario__modal__input top" value="0" data-label="Cantidad">
								<span></span>
								<select id="vadecum"  class="formulario__modal__input" data-label="Medicamento"></select>
								<span></span>
								<a class="boton boton-primario guardarMedicamento">Agregar</a>
							</div>
							<?php $citaMedicamentosQuery = "SELECT * FROM citamedicamentos, vadecum WHERE citamedicamentos.cm_idVadecum = vadecum.IDVadecum AND citamedicamentos.cm_idCita='$id' ORDER BY citamedicamentos.IDCitaMedicamento ASC";

								$rowCountCitaMedicamentos = $con->query($citaMedicamentosQuery)->num_rows;
								$pagConfig = array(
									'totalRows' => $rowCountCitaMedicamentos,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationCitaMedicamentos'
								);
							    $pagination =  new Pagination($pagConfig);

								$citaMedicamentosSql = $con->query($citaMedicamentosQuery." LIMIT $numeroResultados");

							?>
							<div id="listMedicamentos">
								<table class="tableList tableSinheight tablePadding">
			                        <thead>
			                          <tr>
			                            <th class="columnaCorta">Fecha asignación</th>
                            			<th>Cant.</th>
			                            <th>Medicamento</th>
			                            <th>&nbsp</th>
			                          </tr>
			                        </thead>
			                        <tbody>
			                        	<?php while($citaMedicamentosRow = $citaMedicamentosSql->fetch_assoc()){
			                        	?>
			                        	<tr>
			                        		<td><?php echo $citaMedicamentosRow['cm_fechaCreacion'] ?></td>   
			                        		<td><?php echo $citaMedicamentosRow['cm_cantidad'] ?></td>   
			                        		<td class="selectMedicamento"><?php echo '<span>'.$citaMedicamentosRow['vd_medicamento']
			                        				.'</span><i>'.$citaMedicamentosRow['vd_presentacion'].'</i>' ?></td>
			                        		<td class="tableOption">
				                              <a title="Eliminar" class="eliminarMedic eliminar" id="<?php echo $citaMedicamentosRow['IDCitaMedicamento'] ?>" ct="<?php echo $id ?>"><?php echo $iconoEliminar ?></a>
				                            </td>
			                        	</tr>
			                        <?php } ?>
			                        </tbody>
			                    </table>
			                    <?php echo $pagination->createLinks(); ?>
							</div>
				        </div>

				        <div class="divForm" id="content-104">

				        	<?php $tareaCitaSql = $con->query("SELECT * FROM tareas WHERE tar_idCita = '$id'")->fetch_assoc(); ?>

				        	<input type="hidden" name="tareaID" value="<?php echo $tareaCitaSql['IDTarea'] ?>">

							<div class="titulo tituloSecundario"><a id="consultorioCrearTarea"><?php echo $iconoNuevo ?>Crear tarea</a></div>

							<div id="tareaEvolucion">
					        	<div class="container3PartForm">
					        		<div>
					        			<select name="tipoTarea" id="tipoTarea" class="formulario__modal__input" data-label="Tipo tarea">
					        				<option value="" selected hidden>-- Seleccionar --</option>
					        				<?php $tipoTarea = $con->query("SELECT * FROM tipotarea WHERE tpt_idClinica IN(0,$sessionClinica)");
					        					while($tipoTareaRow = $tipoTarea->fetch_assoc()){
					        						$tareaTipoSelect = '';
					        						if($tipoTareaRow['IDTipoTarea']==$tareaCitaSql['tar_idTipo']){ $tareaTipoSelect = "selected"; }
					        						echo "<option value=".$tipoTareaRow['IDTipoTarea']." ".$tareaTipoSelect.">".$tipoTareaRow['tpt_nombre']."</option>";
					        					}
					        				?>
					        			</select>
					        			<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" min="<?php echo date('Y-m-d') ?>" name="fechaAlerta" id="fechaAlerta" data-label="Fecha para Realizar" value="<?php echo $tareaCitaSql['tar_fecha'] ?>" class="formulario__modal__input">
					        			<select name="responsableAlerta" id="responsableAlerta" class="formulario__modal__input" data-label="Responsable">

					        				<?php if($tareaCitaSql['tar_responsable']==0){	?>
					        					<option hidden selected value="">-- Seleccionar --</option>
					        				<?php } ?>

					        				<optgroup label="Sucursales"></optgroup>
					        				<?php $responsablesSCSql = $con->query("
					        					SELECT IDUsuario, sc_nombre FROM sucursales
					        					INNER JOIN usuarios ON usuarios.us_id = sucursales.IDSucursal WHERE sc_idClinica='$sessionClinica' AND sc_estado=1 AND us_idRol=2");
					        					while ($responsablesSCRow = $responsablesSCSql->fetch_assoc()) {
					        						$responsableSelected = '';
					        						if($responsablesSCRow['IDUsuario']==$tareaCitaSql['tar_responsable']){ $responsableSelected = 'selected'; }
					        					 	echo '<option '.$responsableSelected.' value="'.$responsablesSCRow['IDUsuario'].'">'.$responsablesSCRow['sc_nombre'].'</option>';
					        					} ?>

					        				<optgroup label="Doctores"></optgroup>
					        				<?php $responsablesDCSql = $con->query("
					        					SELECT IDUsuario, dc_nombres FROM doctores
					        					INNER JOIN usuarios ON usuarios.us_id = doctores.IDDoctor WHERE dc_idClinica='$sessionClinica' AND dc_estado=1 AND us_idRol=3");
					        					while ($responsablesDCRow = $responsablesDCSql->fetch_assoc()) {
					        					 	$responsableSelected = '';
					        						if($responsablesDCRow['IDUsuario']==$tareaCitaSql['tar_responsable']){ $responsableSelected = 'selected'; }
					        					 	echo '<option '.$responsableSelected.' value="'.$responsablesDCRow['IDUsuario'].'">'.$responsablesDCRow['dc_nombres'].'</option>';
					        					} ?>

					        				<optgroup label="Inventario"></optgroup>
					        				<?php $responsablesUISql = $con->query("
					        					SELECT IDUsuario, ui_nombres FROM usuariosinventario
					        					INNER JOIN usuarios ON usuarios.us_id = usuariosinventario.IDUserInventario
					        					INNER JOIN sucursales ON usuariosinventario.ui_idSucursal = sucursales.IDSucursal
					        					WHERE ui_idClinica='$sessionClinica' AND sc_estado=1 AND ui_estado=1 AND us_idRol=4");
					        					while ($responsablesUIRow = $responsablesUISql->fetch_assoc()) {
					        					 	$responsableSelected = '';
					        						if($responsablesUIRow['IDUsuario']==$tareaCitaSql['tar_responsable']){ $responsableSelected = 'selected'; }
					        					 	echo '<option '.$responsableSelected.' value="'.$responsablesUIRow['IDUsuario'].'">'.$responsablesUIRow['ui_nombres'].'</option>';
					        					} ?>

					        				<optgroup label="Citas"></optgroup>
					        				<?php $responsablesUCSql = $con->query("
					        					SELECT IDUsuario, uc_nombres FROM usuarioscitas
					        					INNER JOIN usuarios ON usuarios.us_id = usuarioscitas.IDUserCitas
					        					INNER JOIN sucursales ON usuarioscitas.uc_idSucursal = sucursales.IDSucursal
					        					WHERE uc_idClinica='$sessionClinica' AND sc_estado=1 AND uc_estado=1 AND us_idRol=5");
					        					while ($responsablesUCRow = $responsablesUCSql->fetch_assoc()) {
					        					 	$responsableSelected = '';
					        						if($responsablesUCRow['IDUsuario']==$tareaCitaSql['tar_responsable']){ $responsableSelected = 'selected'; }
					        					 	echo '<option '.$responsableSelected.' value="'.$responsablesUCRow['IDUsuario'].'">'.$responsablesUCRow['uc_nombres'].'</option>';
					        					} ?>
					        			</select>

					        		</div>
					        		<span></span>
					        		<textarea name="notaAlerta" id="notaAlerta" rows="7" class="formulario__modal__input top" data-label="Nota"><?php echo $tareaCitaSql['tar_nota'] ?></textarea>
					        	</div>
					        </div>
				        </div>
				    </div>
				</div>
				
				
			</div>
			</div>
		</div>
	</div>
	
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<input type="hidden" name="pacienteID" value="<?php echo $citaRow['IDPaciente'] ?>">
			<input type="hidden" name="tratamientoID" value="<?php echo $citaRow['IDTratamiento'] ?>">
			<input type="hidden" name="ev" value="<?php echo $_POST['ev'] ?>">
			
<?php if($sessionRol!=3 && $citaRow['ct_evolucionada']==0){ ?>
			<div class="contenedorCheckbox SliderSwitch pointer">
				<label>¿Programar próxima cita?
				<input type="checkbox" id="proximaCita" name="proximaCita" value="1">
				<div class="SliderSwitch__container">
					<div class="SliderSwitch__toggle"></div>
				</div>
				</label>
			</div>
<?php } ?>
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">


$('#rips').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-rips.php',
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
$('#rips1').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-rips.php',
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
$('#rips2').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-rips.php',
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
$('#rips3').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-rips.php',
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
$('#vadecum').select2({
	placeholder: '-- Seleccionar --',
	templateResult: formatState,
	ajax: {
		url: 'json-vadecum.php',
		dataType: 'json',
		delay: 250,
		processResults: function (data){
			return {
				results: data.items,
				  "pagination": {
				    "more": data.pag
				  }
			}
		},
		cache: true
	}
});


<?php if($citaRow['ct_evolucionada']==0){ ?>
	$('#descargaEvolucion').hide();
<?php } else { ?>
	$('#descargaEvolucion').show();
<?php }?>


function formatState (state) {
  if (!state.id) { return state.text; }
  var markup = $(
    '<div class="selectMedicamento"><span>' + state.text + '</span><i>' + state.descrip + '</i></div>'
  );
  return markup;
};


		function paginationCitaMedicamentos(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/citaMedicamentosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#listMedicamentos').html(html);
		        }
		    });
		}

	$('[id^=trata_progress_]').click(function() {
		$('.item-progress').removeClass('active');
		$(this).parent().addClass('active');

		var progress_porcentaje_inicial = $('#progress_porcentaje_inicial').val();
		var progress_tratamiento = ( $(this).val() - progress_porcentaje_inicial );
		
		$('#progress_tratamiento').val(progress_tratamiento);
	});


<?php if($citaRow['ct_evolucionada']==0){ ?>

	// FIRMA PACIENTE
	var signaturePad_evo_paciente = new SignaturePad(document.querySelector('#signature_pad_evo_paciente'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_evo_paciente', function(){
		signaturePad_evo_paciente.clear();
		$('#firma_evo_paciente').val(null);
	});

	$(document).on('mouseup', '#signature_pad_evo_paciente', function(){
		$('#firma_evo_paciente').val( document.querySelector('#signature_pad_evo_paciente').toDataURL() );
	});

	// FIRMA USUARIO
	var signaturePad_evo_usuario = new SignaturePad(document.querySelector('#signature_pad_evo_usuario'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_evo_usuario', function(){
		signaturePad_evo_usuario.clear();
		$('#firma_evo_usuario').val(null);
	});

	$(document).on('mouseup', '#signature_pad_evo_usuario', function(){
		$('#firma_evo_usuario').val( document.querySelector('#signature_pad_evo_usuario').toDataURL() );
	});

<?php } ?>

/* MEDICAMENTOS */ /*
		$(document).on('click', '.tab-evolucion-medicamentos', function(){
		   	$.ajax({
		       	url:"extras/evolucion-medicamentos.php",  
		        method:"POST", 
		        success:function(data){  
					$('#evolucion-medicamentos').html(data); 
				}
		    });
		});
/* FIN MEDICAMENTOS */

//if (validar('#evolucionForm')) {
	$('#evolucionForm').submit(function() {

		var asistencia = $('#tab-100');
		var progress = $('#progress_tratamiento').val();

		if( asistencia.prop("checked") == true && progress == 0 ){

			$('#progress_title').addClass('validar');
			return false;
		} else {
			
			$('#progress_title').removeClass('validar');
		}

  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#msj-evolucion').html(data);
	            }
	        })
		    
		    <?php if($_POST['ev']!=1){ ?>
		        return false;
		    <?php } ?>
    }); 
//}

		$(document).on('click', '.eliminarMedic', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosCt = $(this).attr("ct");
		    if(consultoriosId > 0 && consultoriosCt > 0)
		    {  
		    	$.ajax({
		        	url:"cita-medicamento-eliminar.php",
		            method:"POST",  
		            data:{id:consultoriosId,ct:consultoriosCt},  
		            success:function(data){  
						$('#listMedicamentos').html(data); 
					}
		    	});
			}            
		});
<?php if(!$tareaCitaSql['IDTarea']){ ?>
		$('#tareaEvolucion').hide();
		$('#consultorioCrearTarea').show();
		$(document).on('click', '#consultorioCrearTarea', function(){
			$('#tareaEvolucion').show();
				$('#notaAlerta').attr('required', 'required');
				$('#fechaAlerta').attr('required', 'required');
				$('#responsableAlerta').attr('required', 'required');
				$('#tipoTarea').attr('required', 'required');
			$('#consultorioCrearTarea').hide();

		});
<?php } else { ?>
		$('#tareaEvolucion').show();
		$('#consultorioCrearTarea').hide();
<?php } ?>
</script>