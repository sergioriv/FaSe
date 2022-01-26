<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$id = $_POST['id'];

$pacienteRow = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$id'")->fetch_assoc();
$pacienteUrl = str_replace(" ","-", $pacienteRow['pc_nombres']);

$pacienteEPS=$con->query("SELECT * FROM eps WHERE IDEps = '$pacienteRow[pc_idEps]'")->fetch_assoc();
$pacienteCiudad=$con->query("SELECT * FROM ciudades WHERE IDCiudad = '$pacienteRow[pc_idCiudad]'")->fetch_assoc();
$pacienteDep=$con->query("SELECT * FROM departamentos WHERE IDDepartamento = '$pacienteRow[pc_idDep]'")->fetch_assoc();
$pacienteOcu=$con->query("SELECT * FROM ocupaciones WHERE IDOcupacion = '$pacienteRow[pc_idOcupacion]'")->fetch_assoc();
$pacienteRef=$con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteRow[pc_idReferido]'")->fetch_assoc();

$tiposIdenti = $con->query("SELECT * FROM tiposidentificacion WHERE ti_estado='1' ORDER BY ti_nombre");
$afiliacion = $con->query("SELECT * FROM afiliacion ORDER BY af_nombre");
$departamentos = $con->query("SELECT * FROM departamentos ORDER BY dp_nombre");
$sexos = $con->query("SELECT * FROM sexos ORDER BY sx_nombre");
$edoCivil = $con->query("SELECT * FROM estadosciviles ORDER BY ec_nombre");
$escolaridad = $con->query("SELECT * FROM escolaridad ORDER BY es_nombre");
$zona = $con->query("SELECT * FROM zonaresidencial ORDER BY zr_nombre");
$regimen = $con->query("SELECT * FROM regimenes ORDER BY rg_nombre");
$etnias = $con->query("SELECT * FROM etnias ORDER BY et_nombre");
?>

<form class="form" id="formPaciente" method="post" action="paciente-guardar.php" enctype="multipart/form-data">

	<div class="modal-header modal-header-form">

		<div class="titulo tituloSecundario"><?php if($id){ 
			echo 'Paciente: '.$pacienteRow['pc_nombres']; ?>
				<a href="paciente-historia-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Historia clinica</a>
				<!-- <a href="paciente-historia-clinica-excel.php?id=<?php echo $id  ?>"><i class="fa fa-download"></i>Historia clinica</a> -->
				<?php if($sessionRol!=3){ ?>
					<a title="Nueva Cita" onClick="location.href='cita?id=<?php echo $id ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>Nueva cita</a>
				<?php } ?>
			<?php } else { echo 'Nuevo Paciente';} ?>
		</div>
		<button class="boton boton-primario">Guardar</button>
	</div>
				
				<div class="contenedorTabs">
				        <input id="tab-1" type="radio" name="tab-group" checked />
				        <label for="tab-1" class="labelTab">Información</label>
		<?php if($id){ ?>
				        <input id="tab-2" type="radio" name="tab-group" />
				        <label for="tab-2" class="labelTab">Antecedentes</label>
				        <input id="tab-3" type="radio" name="tab-group" />
				        <label for="tab-3" class="labelTab">Estomatológico</label>
				        <input id="tab-4" type="radio" name="tab-group" />
				        <label for="tab-4" class="labelTab">Tratamientos</label>
				        <input id="tab-5" type="radio" name="tab-group" />
				        <label for="tab-5" class="labelTab">Citas</label>
				    <?php if($sessionRol!=3){ ?>
				        <input id="tab-6" type="radio" name="tab-group" />
				        <label for="tab-6" class="labelTab">Estado cuenta</label>
				    <?php } ?>
				        <input id="tab-7" type="radio" name="tab-group" />
				        <label for="tab-7" class="labelTab">Registro fotográfico</label>
				        <input id="tab-8" type="radio" name="tab-group" />
				        <label for="tab-8" class="labelTab">Odontograma</label>
				        <input id="tab-9" type="radio" name="tab-group" />
				        <label for="tab-9" class="labelTab">Planes de tratamientos</label>
				        <input id="tab-10" type="radio" name="tab-group" />
				        <label for="tab-10" class="labelTab">Presupuestos</label>
				        <input id="tab-11" type="radio" name="tab-group" />
				        <label for="tab-11" class="labelTab">Mis referidos</label>

		<?php } ?>	
			        <!--Contenido a mostrar/ocultar-->
			        <div class="contenidoTab">
				        <div class="divForm" id="content-1">

							<div class="contentAvatar">
								<div class="avatar">
									<div title="Click para cambiar imagen" id="msjPhoto" class="cargaImg user" onclick="$('#filePhoto').click()">
										<?php
											if( file_exists( $pacienteRow['pc_foto'] ) ){ echo "<img src='$ruta$pacienteRow[pc_foto]'/>"; }
										?>				
									</div>
						        	<input type="file" accept="image/png, .jpeg, .jpg, .bmp" name="filePhoto" id="filePhoto">
								</div>
								<div>
						        	<div class="container3PartForm">
							        	<input type="text" name="apellido1" id="apellido1" data-label="Primer Apellido" value="<?php echo $pacienteRow['pc_apellido1'] ?>" class="formulario__input">
							        	<span></span>
							        	<input type="text" name="apellido2" id="apellido2" data-label="Segundo Apellido" value="<?php echo $pacienteRow['pc_apellido2'] ?>" class="formulario__input">
							        </div>
							        <div class="container1Part">
						        		<input type="text" name="nombre" id="nombre" data-label="Nombres" value="<?php echo $pacienteRow['pc_nombre'] ?>" class="formulario__input">
						        	</div>
						        	<div class="container3PartForm">
										<select name="tipoIdentificacion" data-label="Tipo de Identificación" class="formulario__input">
											<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
											<?php										
								            	while($tiposIdentiRow = $tiposIdenti->fetch_assoc()){
								            		$tiposIdentiSelect = '';
								            		if($tiposIdentiRow['IDTipoIdentificacion']==$pacienteRow['pc_idIdentificacion']){ $tiposIdentiSelect = "selected"; }
								            		echo "<option value=".$tiposIdentiRow['IDTipoIdentificacion']." ".$tiposIdentiSelect.">".$tiposIdentiRow['ti_label']."</option>";	
												}
								            ?>
							            </select>
							            <span></span>
										<input type="text" name="identificacion" data-label="Número de Identificación" value="<?php echo $pacienteRow['pc_identificacion'] ?>" class="formulario__input">
									</div>
									<div class="container3PartForm">
										<select name="sexo" class="formulario__input" data-label="Sexo">
											<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
											<?php
												
								            	while($sexosRow = $sexos->fetch_assoc()){
								            		$sexoSelect = '';
								            		if($sexosRow['IDSexo']==$pacienteRow['pc_idSexo']){ $sexoSelect = "selected"; }
								            		echo "<option value=".$sexosRow['IDSexo']." ".$sexoSelect.">".$sexosRow['sx_nombre']."</option>";	
												}
								            ?>
							            </select>
							            <span></span>
							            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" max="<?php echo date('Y-m-d') ?>" name="nacimiento" data-label="Fecha de Nacimiento" value="<?php echo $pacienteRow['pc_fechaNacimiento'] ?>" class="formulario__input">
									</div>
							    </div>
					    	</div>

									<div class="container3PartForm">
							           <select name="estadocivil" class="formulario__input" data-label="Estado Civil">
											<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
											<?php
								            	while($edoCivilRow = $edoCivil->fetch_assoc()){
								            		$edoCivilSelect = '';
								            		if($edoCivilRow['IDEstadoCivil']==$pacienteRow['pc_idEstadoCivil']){ $edoCivilSelect = "selected"; }
								            		echo "<option value=".$edoCivilRow['IDEstadoCivil']." ".$edoCivilSelect.">".$edoCivilRow['ec_nombre']."</option>";
												}
								            ?>
							            </select>
							            <span></span>
							            <select name="escolaridad" class="formulario__input" data-label="Escolaridad">
											<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
											<?php
								            	while($escolaridadRow = $escolaridad->fetch_assoc()){
								            		$escolaridadSelect = '';
								            		if($escolaridadRow['IDEscolaridad']==$pacienteRow['pc_idEscolaridad']){ $escolaridadSelect = "selected"; }
								            		echo "<option value=".$escolaridadRow['IDEscolaridad']." ".$escolaridadSelect.">".$escolaridadRow['es_nombre']."</option>";
												}
								            ?>
							            </select>
							        </div>

					        <div class="container3PartForm">
					        	<select name="afiliacion" class="formulario__input" data-label="Afiliación">
									<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php
						            	while($afiliacionRow = $afiliacion->fetch_assoc()){
						            		$afiliacionSelect = '';
						            		if($afiliacionRow['IDAfiliacion']==$pacienteRow['pc_idAfiliacion']){ $afiliacionSelect = "selected"; }
						            		echo "<option value=".$afiliacionRow['IDAfiliacion']." ".$afiliacionSelect.">".$afiliacionRow['af_nombre']."</option>";
										}
						            ?>
					            </select>
					            <span></span>
					            <select name="ciudad" id="ciudad" class="formulario__input" data-label="Ciudad">
								<?php
									if($pacienteRow['pc_idCiudad']!=0){
						            	echo "<option value=".$pacienteCiudad['IDCiudad']." selected>".$pacienteCiudad['cd_nombre']."</option>";
						            }
						        ?>
					            </select>
					        </div>

					    <?php if($sessionRol!=3){ ?>
					        <div class="container3PartForm">
					        	<select name="zona" class="formulario__input" data-label="Zona Residencial">
									<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php
						            	while($zonaRow = $zona->fetch_assoc()){
						            		$zonaSelect = '';
						            		if($zonaRow['IDZonaRes']==$pacienteRow['pc_idZona']){ $zonaSelect = "selected"; }
						            		echo "<option value=".$zonaRow['IDZonaRes']." ".$zonaSelect.">".$zonaRow['zr_nombre']."</option>";
										}
						            ?>
					            </select>
					            <span></span>
					            <input type="text" name="direccion" value="<?php echo $pacienteRow['pc_direccion'] ?>" class="formulario__input" data-label="Dirección">
					        </div>
					        <div class="container3PartForm">
								<input type="text" name="telefono" value="<?php echo $pacienteRow['pc_telefonoFijo'] ?>" class="formulario__input" data-label="Teléfono Fijo">
								<span></span>
								<input type="text" name="celular" value="<?php echo $pacienteRow['pc_telefonoCelular'] ?>" class="formulario__input" data-label="Teléfono Celular">
							</div>
							<div class="container3PartForm">
								<input type="email" name="correo" value="<?php echo $pacienteRow['pc_correo'] ?>" class="formulario__input" data-label="Correo Electrónico">
								<span></span>
								<input type="text" name="responsable" value="<?php echo $pacienteRow['pc_responsable'] ?>" class="formulario__input" data-label="Persona Responsable y/o Acompañante">
							</div>
						<?php } ?>
							<div class="container3PartForm">
								<select name="eps" id="eps" class="formulario__input" data-label="EPS">
								<?php
									if($pacienteRow['pc_idEps']!=0){
						            	echo "<option value=".$pacienteEPS['IDEps']." selected>".$pacienteEPS['eps_nombre']."</option>";
									}
						         ?>
					            </select>
					            <span></span>
					            <select name="regimen" class="formulario__input" data-label="Régimen">
									<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php
						            	while($regimenRow = $regimen->fetch_assoc()){
						            		$regimenSelect = '';
						            		if($regimenRow['IDRegimen']==$pacienteRow['pc_idRegimen']){ $regimenSelect = "selected"; }
						            		echo "<option value=".$regimenRow['IDRegimen']." ".$regimenSelect.">".$regimenRow['rg_nombre']."</option>";
										}
						            ?>
					            </select>
							</div>
							<div class="container3PartForm">
								<select name="etnia" class="formulario__input" data-label="Etnia">
									<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
									<?php
						            	while($etniasRow = $etnias->fetch_assoc()){
						            		$etniasSelect = '';
						            		if($etniasRow['IDEtnia']==$pacienteRow['pc_idEtnia']){ $etniasSelect = "selected"; }
						            		echo "<option value=".$etniasRow['IDEtnia']." ".$etniasSelect.">".$etniasRow['et_nombre']."</option>";
										}
						            ?>
					            </select>
					            <span></span>
					            <select name="ocupacion" id="ocupacion" class="formulario__input" data-label="Ocupación">
								<?php
									if($pacienteRow['pc_idOcupacion']!=0){
						            	echo "<option value=".$pacienteOcu['IDOcupacion']." selected>".$pacienteOcu['ocu_codigo'].' | '.$pacienteOcu['ocu_nombre']."</option>";
									}
						         ?>
					            </select>
							</div>
						<?php if($sessionRol!=3){ ?>						
							<div class="container3PartForm">
								<select name="referencia" id="referencia" class="formulario__input" data-label="Referencia de origen">
									<option value="" selected hidden>-- Seleccionar --</option>
									<?php $referencias = $con->query("SELECT * FROM referencias");
										while($referenciasRow = $referencias->fetch_assoc()){
											$referenciaSelect = '';
						            		if($referenciasRow['IDReferencia']==$pacienteRow['pc_idReferencia']){ $referenciaSelect = "selected"; }
						            		echo "<option value=".$referenciasRow['IDReferencia']." ".$referenciaSelect.">".$referenciasRow['ref_nombre']."</option>";
										}
									?>
								</select>
								<span></span>
								<div id="contReferido">
									<select name="referido" id="referido" class="formulario__input" data-label="Referente">
										<option value="" selected hidden>-- Seleccionar Referencia de origen --</option>
									</select>
								</div>
							</div>
						<?php } ?>
							<div class="container1Part">
								<textarea name="etiqueta" rows="4" class="formulario__input" data-label="Etiqueta de advertencia"><?php echo $pacienteRow['pc_etiqueta'] ?></textarea>
							</div>

							<p>&nbsp</p>

							<div class="containerFirmas">
								<div class="content_signature">

								<?php if (!$pacienteRow['pc_firma']){ ?>
									<div id="firma_paciente_image" class="ocultar">
								<?php } else { ?>
									<div id="firma_paciente_image" class="">
								<?php } ?>
										<img src="<?php echo $pacienteRow['pc_firma'] ?>">
									</div>
								
								<?php if (!$pacienteRow['pc_firma']){ ?>
									<canvas id="signature_pad_concent_paciente" class="signature_pad" width=400 height=200></canvas>
								<?php } else { ?>
									<canvas id="signature_pad_concent_paciente" class="signature_pad ocultar" width=400 height=200></canvas>
								<?php } ?>

									<div class="option_signature_pad">
										Firma
										<span id="clear_signature_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
									</div>

									<div class="option_signature_botton boton" onclick="$('#firma_file_paciente').click()">Cargar imágen</div>

								</div>
							</div>

							<input type="hidden" name="firma_paciente" id="firma_paciente">
							<input type="file" accept="image/png, .jpeg, .jpg, .bmp" style="display:none" id="firma_file_paciente">
						</div>

		<?php if($id){ ?>

						<div class="divForm" id="content-2">
							<div class="contenedorRadio">
								<input type="radio" id="antFamiliares" name="areaRip" value="1">
								<label for="antFamiliares" class="labelRadio">Familiares</label>
								<input type="radio" id="antPatologicos" name="areaRip" value="2">
								<label for="antPatologicos" class="labelRadio">Patológicos</label>
								<input type="radio" id="antNoPatologicos" name="areaRip" value="3">
								<label for="antNoPatologicos" class="labelRadio">No Patológicos</label>
							</div>
							<div class="container3PartInput contRips" id="noPatologicos">
								<select name="inputNoPatologico" id="inputNoPatologico" class="formulario__input top" data-label="No Patológicos">
									<option hidden selected value="">-- Seleccionar --</option>
									<?php $noPatologicos = $con->query("SELECT * FROM nopatologicos WHERE np_estado='1'");
									while($noPatologicosRow = $noPatologicos->fetch_assoc()){
										echo "<option value=".$noPatologicosRow['IDNoPatologico'].">".$noPatologicosRow['np_nombre']."</option>";
									}

									?>
								</select>
								<span></span>
								<a class="boton boton-primario guardarNoPatologico">Agregar</a>
							</div>
							<div class="container3PartInput contRips" id="antecedentesCie10">
								<select name="inputrips" id="inputrips" class="formulario__input" data-label="CIE-10"></select>
								<span></span>
					            <a class="boton boton-primario guardarRips">Agregar</a>
							</div>
				            <textarea id="comentarioRip" rows="2" class="formulario__input top" data-label="Comentario"></textarea>
							<p>&nbsp</p>
							
				            <p>&nbsp</p>
				            <div id="listRips">
				            	<div class="titulo tituloSecundario">Familiares</div>
				            	<table class="tableList">
				            		<thead>
								        <tr>
								          <th class="columnaCorta">CIE-10</th>
								          <th>Comentario</th>
								          <th class="columnaCorta">Fecha asignación</th>
								          <th>&nbsp</th>
								        </tr>
								     </thead>
				            		<tbody>
				            	<?php $pacienteRipsSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$id' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='1' ORDER BY pacientesrips.IDPacRips DESC");
				            		while($pacienteRipsRow = $pacienteRipsSql->fetch_assoc()){
				            	?>
				            			<tr>
					            			<!--<td class="columnaIcon"></td>-->
					            			<td><?php echo $pacienteRipsRow['rip_codigo']?></td>
					            			<td><?php echo $pacienteRipsRow['prip_comentario']?></td>
					            			<td><?php echo $pacienteRipsRow['prip_fechaCreacion'] ?></td>
					            			<td class="tableOption">
					            				<a title="Eliminar" class="eliminarRips eliminar" id="<?php echo $pacienteRipsRow[IDPacRips] ?>" es="1" pc="<?php echo $id ?>" tipo="0"><?php echo $iconoEliminar ?></a>
					            			</td>
					            		</tr>
					            <?php } ?>
				            		</tbody>
				            	</table>
				            	<p style="border-bottom: 1px solid var(--colorGray)">&nbsp</p>
				            	<p>&nbsp</p>
				            	<div class="titulo tituloSecundario">Patológicos</div>
				            	<table class="tableList">
				            		<thead>
								        <tr>
								          <th class="columnaCorta">CIE-10</th>
								          <th>Comentario</th>
								          <th class="columnaCorta">Fecha asignación</th>
								          <th>&nbsp</th>
								        </tr>
								     </thead>
				            		<tbody>
				            	<?php $pacienteRipsSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$id' AND pacientesrips.prip_estado='1' AND pacientesrips.prip_area='2' ORDER BY pacientesrips.IDPacRips DESC");
				            		while($pacienteRipsRow = $pacienteRipsSql->fetch_assoc()){
				            	?>
				            			<tr>
					            			<!--<td class="columnaIcon"></td>-->
					            			<td><?php echo $pacienteRipsRow['rip_codigo']?></td>
					            			<td><?php echo $pacienteRipsRow['prip_comentario']?></td>
					            			<td><?php echo $pacienteRipsRow['prip_fechaCreacion'] ?></td>
					            			<td class="tableOption">
					            				<a title="Eliminar" class="eliminarRips eliminar" id="<?php echo $pacienteRipsRow[IDPacRips] ?>" es="1" pc="<?php echo $id ?>" tipo="0"><?php echo $iconoEliminar ?></a>
					            			</td>
					            		</tr>
					            <?php } ?>
				            		</tbody>
				            	</table>
				            	<p style="border-bottom: 1px solid var(--colorGray)">&nbsp</p>
				            	<p>&nbsp</p>
				            	<div class="titulo tituloSecundario">No Patológicos</div>
				            	<table class="tableList">
				            		<thead>
								        <tr>
								          <th>Nombre</th>
								          <th>Comentario</th>
								          <th class="columnaCorta">Fecha asignación</th>
								          <th>&nbsp</th>
								        </tr>
								     </thead>
				            		<tbody>
				            	<?php $pacienteNoPatSql = $con->query("SELECT * FROM pacientenopatologicos, nopatologicos WHERE pacientenopatologicos.pnp_idNoPatologico = nopatologicos.IDNoPatologico AND pacientenopatologicos.pnp_idPaciente = '$id' AND pacientenopatologicos.pnp_estado='1' ORDER BY pacientenopatologicos.IDpacNoPatologico DESC");
                        			while($pacienteNoPatRow = $pacienteNoPatSql->fetch_assoc()){
				            	?>
				            			<tr>
					            			<!--<td class="columnaIcon"></td>-->
					            			<td><?php echo $pacienteNoPatRow['np_nombre']?></td>
				                            <td><?php echo $pacienteNoPatRow['pnp_comentario']?></td>
				                            <td><?php echo $pacienteNoPatRow['pnp_fechaCreacion'] ?></td>
					            			<td class="tableOption">
					            				<a title="Eliminar" class="eliminarNoPat eliminar" id="<?php echo $pacienteNoPatRow[IDpacNoPatologico] ?>" es="1" pc="<?php echo $id ?>" tipo="0"><?php echo $iconoEliminar ?></a>
					            			</td>
					            		</tr>
					            <?php } ?>
				            		</tbody>
				            	</table>
				            </div>

						</div>

						<div class="divForm" id="content-3">
							<?php $evolPac = $con->query("SELECT * FROM evolucionpaciente WHERE ev_idPaciente = '$id'")->fetch_assoc(); ?>

							<div class="container3PartColumns">
								<table class="tableSeleccion">
									<thead>
										<tr>
											<th class="labelSeleccion"></th>
											<th class="titleSeleccion">Bueno</th>
											<th class="titleSeleccion">Regular</th>
											<th class="titleSeleccion">Malo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Higiene Oral</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom1" name="higieneOral" value="3"
													<?php if($evolPac['ev_higieneOral']==3){echo"checked";} ?>>
												<label for="estom1" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom2" name="higieneOral" value="2"
													<?php if($evolPac['ev_higieneOral']==2){echo"checked";} ?>>
												<label for="estom2" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom3" name="higieneOral" value="1"
													<?php if($evolPac['ev_higieneOral']==1){echo"checked";} ?>>
												<label for="estom3" class="labelRadio"></label>
											</td>
										</tr>
										<tr>
											<td class="tituloSecundario" colspan="4"></td>
										</tr>
									</tbody>
									<thead>
										<tr>
											<th class="labelSeleccion"></th>
											<th class="titleSeleccion">Si</th>
											<th class="titleSeleccion">No</th>
											<th class="titleSeleccion">&nbsp</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Seda Dental</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom4" name="sedaDental" value="2"
													<?php if($evolPac['ev_seda']==2){echo"checked";} ?>>
												<label for="estom4" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">												
												<input type="radio" id="estom5" name="sedaDental" value="1"
													<?php if($evolPac['ev_seda']==1){echo"checked";} ?>>
												<label for="estom5" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Cepillo Dental</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom6" name="cepillo" value="2"
													<?php if($evolPac['ev_cepillo']==2){echo"checked";} ?>>
												<label for="estom6" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">												
												<input type="radio" id="estom7" name="cepillo" value="1"
													<?php if($evolPac['ev_cepillo']==1){echo"checked";} ?>>
												<label for="estom7" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Enjuagues Bucales</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom8" name="enjuagues" value="2"
													<?php if($evolPac['ev_enjuagues']==2){echo"checked";} ?>>
												<label for="estom8" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom9" name="enjuagues" value="1"
													<?php if($evolPac['ev_enjuagues']==1){echo"checked";} ?>>
												<label for="estom9" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td colspan="4">
												<input type="number" name="cantVeces" value="<?php echo $evolPac['ev_cantVeces'] ?>" class="formulario__input top" data-label="Cuántas veces al día">
											</td>
										</tr>


										<tr>
											<td class="tituloSecundario" colspan="4">Examen Dental</td>
										</tr>
										<tr>
											<td>Supernumerarios</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom42" name="superNumerarios" value="2"
													<?php if($evolPac['ev_superNumerarios']==2){echo"checked";} ?>>
												<label for="estom42" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom43" name="superNumerarios" value="1"
													<?php if($evolPac['ev_superNumerarios']==1){echo"checked";} ?>>
												<label for="estom43" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Abrasion</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom44" name="abrasion" value="2"
													<?php if($evolPac['ev_abrasion']==2){echo"checked";} ?>>
												<label for="estom44" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom45" name="abrasion" value="1"
													<?php if($evolPac['ev_abrasion']==1){echo"checked";} ?>>
												<label for="estom45" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Manchas - Canbio de Color</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom46" name="manchas" value="2"
													<?php if($evolPac['ev_manchas']==2){echo"checked";} ?>>
												<label for="estom46" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom47" name="manchas" value="1"
													<?php if($evolPac['ev_manchas']==1){echo"checked";} ?>>
												<label for="estom47" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Patología Pulpar - Abcesos</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom48" name="patologiaPulpar" value="2"
													<?php if($evolPac['ev_patologiaPulpar']==2){echo"checked";} ?>>
												<label for="estom48" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom49" name="patologiaPulpar" value="1"
													<?php if($evolPac['ev_patologiaPulpar']==1){echo"checked";} ?>>
												<label for="estom49" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Maloclusiones</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom50" name="maloclusiones" value="2"
													<?php if($evolPac['ev_maloclusiones']==2){echo"checked";} ?>>
												<label for="estom50" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom51" name="maloclusiones" value="1"
													<?php if($evolPac['ev_maloclusiones']==1){echo"checked";} ?>>
												<label for="estom51" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Incluidos</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom52" name="incluidos" value="2"
													<?php if($evolPac['ev_incluidos']==2){echo"checked";} ?>>
												<label for="estom52" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom53" name="incluidos" value="1"
													<?php if($evolPac['ev_incluidos']==1){echo"checked";} ?>>
												<label for="estom53" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Trauma</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom54" name="trauma" value="2"
													<?php if($evolPac['ev_trauma']==2){echo"checked";} ?>>
												<label for="estom54" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom55" name="trauma" value="1"
													<?php if($evolPac['ev_trauma']==1){echo"checked";} ?>>
												<label for="estom55" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Habitos</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom56" name="habitos" value="2"
													<?php if($evolPac['ev_habitos']==2){echo"checked";} ?>>
												<label for="estom56" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom57" name="habitos" value="1"
													<?php if($evolPac['ev_habitos']==1){echo"checked";} ?>>
												<label for="estom57" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>

									</tbody>
								</table>

								<table class="tableSeleccion">
									<tbody>	
										<tr>
											<td class="tituloSecundario" colspan="4">Examen Periodontal</td>
										</tr>
										<tr>
											<td>Bolsas - Movilidad</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom58" name="bolsas" value="2"
													<?php if($evolPac['ev_bolsas']==2){echo"checked";} ?>>
												<label for="estom58" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom59" name="bolsas" value="1"
													<?php if($evolPac['ev_bolsas']==1){echo"checked";} ?>>
												<label for="estom59" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Placa Blanda</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom60" name="placaBlanda" value="2"
													<?php if($evolPac['ev_placaBlanda']==2){echo"checked";} ?>>
												<label for="estom60" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom61" name="placaBlanda" value="1"
													<?php if($evolPac['ev_placaBlanda']==1){echo"checked";} ?>>
												<label for="estom61" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Calculos</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom62" name="calculos" value="2"
													<?php if($evolPac['ev_calculos']==2){echo"checked";} ?>>
												<label for="estom62" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom63" name="calculos" value="1"
													<?php if($evolPac['ev_calculos']==1){echo"checked";} ?>>
												<label for="estom63" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td colspan="4">
												<textarea name="observacionesEvolucion" rows="3" class="formulario__input top" data-label="Observaciones"><?php echo $evolPac['ev_observaciones'] ?></textarea>
											</td>
										</tr>
									</tbody>
								</table>

								<table class="tableSeleccion">
									<thead>
										<tr>
											<th class="labelSeleccion"></th>
											<th class="titleSeleccion">Normal</th>
											<th class="titleSeleccion">Anormal</th>
											<th class="titleSeleccion">&nbsp</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="4" class="tituloSecundario">Tejidos Blandos</td>
										</tr>
										<tr>
											<td>A.T.M</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom10" name="atm" value="2"
													<?php if($evolPac['ev_atm']==2){echo"checked";} ?>>
												<label for="estom10" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom11" name="atm" value="1"
													<?php if($evolPac['ev_atm']==1){echo"checked";} ?>>
												<label for="estom11" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Labios</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom12" name="labios" value="2"
													<?php if($evolPac['ev_labios']==2){echo"checked";} ?>>
												<label for="estom12" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom13" name="labios" value="1"
													<?php if($evolPac['ev_labios']==1){echo"checked";} ?>>
												<label for="estom13" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Lengua</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom14" name="lengua" value="2"
													<?php if($evolPac['ev_lengua']==2){echo"checked";} ?>>
												<label for="estom14" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom15" name="lengua" value="1"
													<?php if($evolPac['ev_lengua']==1){echo"checked";} ?>>
												<label for="estom15" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Paladar</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom16" name="paladar" value="2"
													<?php if($evolPac['ev_paladar']==2){echo"checked";} ?>>
												<label for="estom16" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom17" name="paladar" value="1"
													<?php if($evolPac['ev_paladar']==1){echo"checked";} ?>>
												<label for="estom17" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Piso de Boca</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom18" name="pisoBoca" value="2"
													<?php if($evolPac['ev_pisoBoca']==2){echo"checked";} ?>>
												<label for="estom18" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom19" name="pisoBoca" value="1"
													<?php if($evolPac['ev_pisoBoca']==1){echo"checked";} ?>>
												<label for="estom19" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Carrillos</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom20" name="carrillos" value="2"
													<?php if($evolPac['ev_carrillos']==2){echo"checked";} ?>>
												<label for="estom20" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom21" name="carrillos" value="1"
													<?php if($evolPac['ev_carrillos']==1){echo"checked";} ?>>
												<label for="estom21" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Glandulas Salivares</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom22" name="glandulasSalivares" value="2"
													<?php if($evolPac['ev_glandulasSalivares']==2){echo"checked";} ?>>
												<label for="estom22" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom23" name="glandulasSalivares" value="1"
													<?php if($evolPac['ev_glandulasSalivares']==1){echo"checked";} ?>>
												<label for="estom23" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Maxilares</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom24" name="maxilares" value="2"
													<?php if($evolPac['ev_maxilares']==2){echo"checked";} ?>>
												<label for="estom24" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom25" name="maxilares" value="1"
													<?php if($evolPac['ev_maxilares']==1){echo"checked";} ?>>
												<label for="estom25" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Senos Maxilares</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom26" name="senosMaxilares" value="2"
													<?php if($evolPac['ev_senosMaxilares']==2){echo"checked";} ?>>
												<label for="estom26" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom27" name="senosMaxilares" value="1"
													<?php if($evolPac['ev_senosMaxilares']==1){echo"checked";} ?>>
												<label for="estom27" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Musculos Masticadores</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom28" name="muscMasticadores" value="2"
													<?php if($evolPac['ev_muscMasticadores']==2){echo"checked";} ?>>
												<label for="estom28" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom29" name="muscMasticadores" value="1"
													<?php if($evolPac['ev_muscMasticadores']==1){echo"checked";} ?>>
												<label for="estom29" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Ganglios</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom30" name="ganglios" value="2"
													<?php if($evolPac['ev_ganglios']==2){echo"checked";} ?>>
												<label for="estom30" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom31" name="ganglios" value="1"
													<?php if($evolPac['ev_ganglios']==1){echo"checked";} ?>>
												<label for="estom31" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Oclusion</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom32" name="oclusion" value="2"
													<?php if($evolPac['ev_oclusion']==2){echo"checked";} ?>>
												<label for="estom32" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom33" name="oclusion" value="1"
													<?php if($evolPac['ev_oclusion']==1){echo"checked";} ?>>
												<label for="estom33" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Frenillos</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom34" name="frenillos" value="2"
													<?php if($evolPac['ev_frenillos']==2){echo"checked";} ?>>
												<label for="estom34" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom35" name="frenillos" value="1"
													<?php if($evolPac['ev_frenillos']==1){echo"checked";} ?>>
												<label for="estom35" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Mucosas</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom36" name="mucosas" value="2"
													<?php if($evolPac['ev_mucosas']==2){echo"checked";} ?>>
												<label for="estom36" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom37" name="mucosas" value="1"
													<?php if($evolPac['ev_mucosas']==1){echo"checked";} ?>>
												<label for="estom37" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Encías</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom38" name="encias" value="2"
													<?php if($evolPac['ev_encias']==2){echo"checked";} ?>>
												<label for="estom38" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom39" name="encias" value="1"
													<?php if($evolPac['ev_encias']==1){echo"checked";} ?>>
												<label for="estom39" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
										<tr>
											<td>Amigdalas</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom40" name="amigdalas" value="2"
													<?php if($evolPac['ev_amigdalas']==2){echo"checked";} ?>>
												<label for="estom40" class="labelRadio"></label>
											</td>
											<td class="contenedorRadio">
												<input type="radio" id="estom41" name="amigdalas" value="1"
													<?php if($evolPac['ev_amigdalas']==1){echo"checked";} ?>>
												<label for="estom41" class="labelRadio"></label>
											</td>
											<td>&nbsp</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="divForm" id="content-4">

							<?php $pcTratamientosQuery = "SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$id' AND citas.ct_inicial = '1' ORDER BY citas.ct_fechaOrden DESC";

							$rowCountPcTratamientos = $con->query($pcTratamientosQuery)->num_rows;
							$pagConfig = array(
								'totalRows' => $rowCountPcTratamientos,
							    'perPage' => $numeroResultados,
								'link_func' => 'paginationPcTratamientos'
							);
						    $pagination =  new Pagination($pagConfig);

						    $pcTratamientosSql = $con->query($pcTratamientosQuery." LIMIT $numeroResultados");

							?>
							<div id="showResultsPcTratamientos">
								<table class="tableList">
									<thead>
										<tr>
											<th>Tratamiento</th>
											<th class="columnaCorta">Fecha de inicio</th>
											<th>Progreso</th>
											<th>Estado</th>
										</tr>
									</thead>
					            	<tbody>
					            <?php 
					            	while($pcTratamientosRow = $pcTratamientosSql->fetch_assoc()){
						        
						            	if($pcTratamientosRow['ct_terminado']==3){
						            		$estado = 'Terminado '.$pcTratamientosRow['ct_terminadoFecha'];
						            		$porcentajeTratamiento = 100;
						            	} else { 
						            		$estado = 'Activo';

						            		$porcentajeSql = $con->query("SELECT SUM(ct_trataPorcentaje) AS porcentaje FROM citas 
					            			WHERE ct_idPaciente = '$id' AND ct_idTratamiento = '$pcTratamientosRow[IDTratamiento]' AND IDCita >= '$pcTratamientosRow[IDCita]'")->fetch_assoc();
					            			$porcentajeTratamiento = $porcentajeSql['porcentaje'];
						            	}

										$fechaInicioTratamiento = $pcTratamientosRow['ct_anoCita'].'/'.$pcTratamientosRow['ct_mesCita'].'/'.$pcTratamientosRow['ct_diaCita'].' '.$pcTratamientosRow['ct_horaCita'];

										if($pcTratamientosRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
										} else { $iTR = ''; $cTR = ''; }
					            ?>
					            		<tr>
						           			<td class="<?php echo $cTR ?>"><?php echo $iTR.$pcTratamientosRow['tr_nombre'] ?></td>
						           			<td><?php echo $fechaInicioTratamiento ?></td>
											<td class="centro"><?php echo $porcentajeTratamiento.' %' ?></td>
											<td class="centro"><?php echo $estado ?></td>
						           		</tr>
						        <?php } ?>
					            	</tbody>
					            </table>
					            <?php echo $pagination->createLinks(); ?>
					        </div>
						</div>



						<div class="divForm" id="content-5">
							
							<div id="msj-evolucion" class="contenedorAlerta"></div>
							<div class="container9PartForm">
								<select id="pcCitasSucursal" class="formulario__input" data-label="Sucursal" onchange="paginationPcCitas();">
									<option selected value="">-- Seleccionar --</option>
									<?php
										$pcCitasSucursales = $con->query("SELECT * FROM sucursales WHERE sc_idClinica = '$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");
										while($pcCitasSucursalesRow = $pcCitasSucursales->fetch_assoc()){
											echo "<option value=".$pcCitasSucursalesRow['IDSucursal'].">".$pcCitasSucursalesRow['sc_nombre']."</option>";	
										}
									?>
								</select>
								<span></span>
								<select id="pcCitasDoctor" class="formulario__input" data-label="Doctor" onchange="paginationPcCitas();">
									<option selected value="">-- Seleccionar --</option>
									<?php
										$pcCitasDoctores = $con->query("SELECT * FROM doctores WHERE dc_idClinica = '$sessionClinica' AND dc_estado='1' ORDER BY dc_nombres");
										while($pcCitasDoctoresRow = $pcCitasDoctores->fetch_assoc()){
											echo "<option value=".$pcCitasDoctoresRow['IDDoctor'].">".$pcCitasDoctoresRow['dc_nombres']."</option>";	
										}
									?>
								</select>
								<span></span>
								<select id="pcCitasTratamiento" class="formulario__input" data-label="Tratamiento" onchange="paginationPcCitas();">
									<option selected value="">-- Seleccionar --</option>
									<?php
										$pcCitasTratamientos = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' ORDER BY tr_nombre ASC");
										while($pcCitasTratamientosRow = $pcCitasTratamientos->fetch_assoc()){
											echo "<option value=".$pcCitasTratamientosRow['IDTratamiento'].">".$pcCitasTratamientosRow['tr_nombre']."</option>";
										}
									?>
								</select>
								<span></span>
								<input type="date" id="pcCitasRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationPcCitas();">
								<span></span>
								<input type="date" id="pcCitasRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationPcCitas();">
							</div>
							<?php $pcCitasQuery = "SELECT * FROM citas, sucursales, doctores, tratamientos WHERE citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$id' ORDER BY citas.ct_fechaOrden DESC";

							$rowCountPcCitas = $con->query($pcCitasQuery)->num_rows;
							$pagConfig = array(
								'totalRows' => $rowCountPcCitas,
							    'perPage' => $numeroResultados,
								'link_func' => 'paginationPcCitas'
							);
						    $pagination =  new Pagination($pagConfig);

						    $pcCitasSql = $con->query($pcCitasQuery." LIMIT $numeroResultados");

						    if($rowCountPcCitas > 0){
							?>
								<div class="containerPart titulo tituloSecundario"><span></span><a href="excel-citas-paciente.php?table=p&id=<?php echo $id ?>"><i class="fa fa-download"></i>Historial Citas</a></div>
							<?php } ?>

							<div id="showResultsPcCitas">
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
				                                    <a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $pcCitasRow["IDCita"] ?>&id=<?php echo $id ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
				                                <?php } elseif($pcCitasRow['ct_estado'] < 2) { ?>
				                                    <a title="<?= $titleEstado ?>" id="<?php echo $pcCitasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
				                                <?php } ?>
				                                <a title="Información Cita" data-id="<?php echo $pcCitasRow['IDCita'] ?>" data-extra="<?= $id ?>" data-div="showResultsPcCitas" data-site="pc_citas" class="consultorioCita"><i class="fa fa-file-text-o"></i></a>
												</td>
											</tr>
										<?php } ?>
									</tbody>		
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>
						</div>



						<div class="divForm listAbonos" id="content-6">
							<?php
							$deuda = 0;
							$deudaSql = $con->query("SELECT SUM(ct_costo) AS vt FROM citas WHERE ct_idPaciente = '$id' AND ct_inicial='1' AND ct_estado IN(0,1)")->fetch_assoc();
							$abonosSql = $con->query("SELECT SUM(ab_abono) AS ab FROM abonos WHERE ab_idPaciente = '$id' AND ab_idSucursal>0 AND ab_estado='1'")->fetch_assoc();
							$deuda = $deudaSql['vt'] - $abonosSql['ab'];

							$abonosPacienteNum = $con->query("SELECT * FROM abonos WHERE ab_idPaciente='$id' AND ab_idSucursal>0")->num_rows;
							?>
							<div class="titulo tituloSecundario tituloCenter">Estado de cuenta:<span id="estadoCuenta"><?php echo '$'.number_format($deuda, 0, ".", ","); ?></span></div>
							<div class="containerPart">
						    	<?php if($deuda>0){ ?>	
						    		<div class="titulo tituloSecundario"><a class="consultorioAbono"><?php echo $iconoNuevo ?>Nuevo Abono</a></div>
							    <?php } else { echo "<span>&nbsp</span>";}
							    	if($abonosPacienteNum > 0){ ?>
								    <div class="titulo tituloSecundario">
								    	<a href="paciente-abonos-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Historial abonos</a>
								    </div>
								<?php } ?>
						    </div>
						    <?php $abonosPacienteQuery = "SELECT * FROM abonos, usuarios, sucursales WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idPaciente='$id' ORDER BY abonos.IDAbono DESC";

								$rowCountPcAbonos = $con->query($abonosPacienteQuery)->num_rows;

								//Initialize Pagination class and create object
								    $pagConfig = array(
										'totalRows' => $rowCountPcAbonos,
									    'perPage' => $numeroResultados,
										'link_func' => 'paginationPcAbonos'
									);
								    $pagination =  new Pagination($pagConfig);

								$abonosPacienteSql = $con->query($abonosPacienteQuery." LIMIT $numeroResultados");

						    ?>
						    <div id="showResultsPcAbonos">
					    		<table class="tableList">
					    			<thead>
					    				<tr>
					    					<th class="estado">&nbsp</th>
					    					<th>#</th>
					    					<th class="columnaCorta">Fecha</th>
					    					<th>Usuario</th>
					    					<th>Sucursal</th>
					    					<th>Comentario</th>
					    					<th>Forma Pago</th>
					    					<th align="right">Valor</th>
					    					<th>&nbsp</th>
					    				</tr>
					    			</thead>
					    			<tbody>
					    				<?php
					    					while($abonosPacienteRow = $abonosPacienteSql->fetch_assoc()){
					    						$nombreUsuarioAbono = '';
					    						$IDusuarioAbono = $abonosPacienteRow['us_id'];
					    						if($abonosPacienteRow['us_idRol']==1){
					   								$usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
					   								$nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
					    						} elseif($abonosPacienteRow['us_idRol']==2){
					   								$usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
					   								$nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
					    						} elseif($abonosPacienteRow['us_idRol']==3){
					   								$usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
					   								$nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
					    						}

					    						if($abonosPacienteRow['ab_estado']==1){
					    							$estadoAbono = 'estadoNeutro';
					    						} else {
					    							$estadoAbono = 'estadoCancelado';
					    						}

					    						if($abonosPacienteRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
												} else { $iUS = ''; $cUS = ''; }

												if($abonosPacienteRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
												} else { $iSC = ''; $cSC = ''; }

												$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosPacienteRow[ab_idFormaPago]'")->fetch_assoc();

												if($abonosPacienteRow['ab_idFormaPago']==2){
													$bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosPacienteRow[ab_idBanco]'")->fetch_assoc();
													$abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosPacienteRow['ab_cheque'];
												} else {
													$abonoFormaPago = $formaPago['fp_nombre'];
												}
					    				?>
					    				<tr>
					    					<td class="estado <?php echo $estadoAbono ?>"></td>
					    					<td align="right"><?php echo $abonosPacienteRow['ab_consecutivo'] ?></td>
					    					<td align="center"><?php echo $abonosPacienteRow['ab_fechaCreacion'] ?></td>
					    					<td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
					    					<td class="<?php echo $cSC ?>"><?php echo $iSC.$abonosPacienteRow['sc_nombre'] ?></td>
					    					<td><?php echo $abonosPacienteRow['ab_comentario'] ?></td>
					    					<td align="center"><?php echo $abonoFormaPago ?></td>
					    					<td align="right"><?php echo '$'.number_format($abonosPacienteRow['ab_abono'], 0, ".", ","); ?></td>
					    					<td class="tableOption">
					    						<a title="Descargar PDF" href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosPacienteRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
					    						<?php if($abonosPacienteRow['ab_estado']==1){ ?>
					    							<a title="Editar" class="consultorioAbonoEditar" id="<?php echo $abonosPacienteRow['IDAbono'] ?>"><?php echo $iconoEditar ?></a>
										    		<a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosPacienteRow['IDAbono'] ?>" pc="<?php echo $id ?>"><?php echo $iconoEliminar ?></a>
										    	<?php } ?>
										    </td>
					    				</tr>
					    				<?php } ?>
					    			</tbody>
					    		</table>
					    		<?php echo $pagination->createLinks(); ?>
					    	</div>
						</div>


						<div class="divForm" id="content-7">
							<?php 
								$fotosPaciente = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id'");
								$numFotosPaciente = $fotosPaciente->num_rows;
							?>

							<div class="titulo tituloSecundario tituloRegistros">
					            <a data-toggle="modal" data-target="#src_img_upload" id="<?php echo $id ?>" class="consultorioNuevaFoto"><?php echo $iconoNuevo ?>Subir Imágen</a>
					        </div>

					        <div id="image_gallery">
					        <!-- Slider -->
					        <div class="contenedor-galeria">
								<div class="" id="slider-thumbs">
					                <!-- Bottom switcher of slider -->
					                <div class="hide-bullets">
					                <?php $imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id' ORDER BY IDPacFoto DESC");
					                	while($imagenesRow = $imagenesSql->fetch_assoc()){
					                ?>
					                    <div class="miniatura">                        
					                        <img src="<?php echo $imagenesRow['pf_foto'] ?>">
					                        <div class="optionImage">
					                          	<div title="Ver" class="ver-carousel-selector" data-img="<?php echo $imagenesRow['IDPacFoto'] ?>"><i class="fa fa-eye"></i></div>
					                          	<div title="Eliminar" id="<?php echo $imagenesRow['IDPacFoto'] ?>" t="pacientefotos" pc="<?php echo $id ?>" class="consultorioEliminarFoto eliminar"><?php echo $iconoEliminar ?></div>
					                        </div>
					                    </div>
					                <?php } ?>
					                </div>
                                </div>
					            <div id="registro-fotografico" class="carousel slide carousel-fade" data-ride="carousel">
									<a class="close-fullscreen" onclick="closeFullscreen();" data-dismiss="modal">&times;</a>
									<!-- Indicators -->
								<!--    <ol class="carousel-indicators">
										<?php $imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id' ORDER BY IDPacFoto DESC");
										$i=0;
											while($imagenesRow = $imagenesSql->fetch_assoc()){
										?>
											<li data-target="#registro-fotografico" data-slide-to="<?php echo $i ?>"></li>
										<?php $i++; } ?>
									</ol>-->

									<!-- Wrapper for slides -->
									<div class="carousel-inner">
										<?php $imagenesSql = $con->query("SELECT * FROM pacientefotos WHERE pf_idPaciente = '$id' ORDER BY IDPacFoto DESC");
											while($imagenesRow = $imagenesSql->fetch_assoc()){
										?>
											<div class="item" id="<?php echo 'carousel-selector-'.$imagenesRow['IDPacFoto'] ?>">
												<img src="<?php echo $imagenesRow['pf_foto'] ?>" alt="Error al cargar">
												<div class="carousel-caption">
													<h3><?php echo $imagenesRow['pf_titulo'] ?></h3>
													<p><?php echo $imagenesRow['pf_descripcion'] ?></p>
												</div>
											</div>
										<?php } ?>

									</div>

									<!-- Left and right controls -->
									<a class="left carousel-control" href="#registro-fotografico" data-slide="prev">
										<i class="fa fa-angle-left"></i>
									</a>
									<a class="right carousel-control" href="#registro-fotografico" data-slide="next">
										<i class="fa fa-angle-right"></i>
									</a>
								</div>
					        </div>
					        <!--/Slider-->
					        </div>
						</div>

						<div class="divForm" id="content-8">
							
							<div id="msj-odontograma" class="contenedorAlerta"></div>
							<?php $pcOdontogramasQuery = "SELECT * FROM pacienteodontograma WHERE pod_idPaciente = '$id' AND pod_estado = 1 ORDER BY IDOdontograma DESC";

								$rowCountPcOdontogramas = $con->query($pcOdontogramasQuery)->num_rows;

								//Initialize Pagination class and create object
								    $pagConfig = array(
										'totalRows' => $rowCountPcOdontogramas,
									    'perPage' => $numeroResultados,
										'link_func' => 'paginationPcOdontogramas'
									);
								    $pagination =  new Pagination($pagConfig);

								$pcOdontogramasSql = $con->query($pcOdontogramasQuery." LIMIT $numeroResultados");
							?>
							<div class="titulo tituloSecundario"><a class="consultorioNuevoOdontograma"><?php echo $iconoNuevo ?>Nuevo Odontograma</a></div>
							<div id="showResultsPcOdontograma">
								<table class="tableList">
									<thead>
										<tr>
											<th class="columnaCorta">Fecha</th>
											<th>Consecutivo</th>
											<th>Nota</th>
											<th>&nbsp</th>
										</tr>
									</thead>
									<tbody>
										<?php while($pcOdontogramaRow = $pcOdontogramasSql->fetch_assoc()){ ?>
										<tr>
											<td><?= $pcOdontogramaRow['pod_fecha'] ?></td>
											<td align="center"><?= $pcOdontogramaRow['pod_consecutivo'] ?></td>
											<td align="center"><?= $pcOdontogramaRow['pod_nota'] ?></td>
											<td class="tableOption">
												<a title="Ver odontograma" class="verOdontograma" data-id="<?= $pcOdontogramaRow['IDOdontograma'] ?>"><i class="fa fa-table" aria-hidden="true"></i></a>
												<a title="Crear plan de tratamiento" class="nuevoPlan" data-odontograma="<?= $pcOdontogramaRow['IDOdontograma'] ?>"><i class="fa fa-clipboard"></i></a>
												<a title="Descargar PDF" href="odontograma-paciente-pdf.php?q=<?= encrypt( 'id='.$pcOdontogramaRow['IDOdontograma'] ) ?>"><i class="fa fa-download"></i></a>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>
							
						</div>




						<div class="divForm" id="content-9">
							<div id="msj-plan-tratamiento"></div>
							<?php $pcPlanesQuery = "SELECT * FROM plantratamientos
									INNER JOIN pacienteodontograma ON plantratamientos.plt_idOdontograma = pacienteodontograma.IDOdontograma
									WHERE plt_idClinica='$sessionClinica' AND plt_idPaciente='$id' AND plt_estado='1' ORDER BY IDPlanTratamiento DESC";

								$rowCountPcPlanes = $con->query($pcPlanesQuery)->num_rows;

								//Initialize Pagination class and create object
								    $pagConfig = array(
										'totalRows' => $rowCountPcPlanes,
									    'perPage' => $numeroResultados,
										'link_func' => 'paginationPcPlanes'
									);
								    $pagination =  new Pagination($pagConfig);

								$pcPlanesSql = $con->query($pcPlanesQuery." LIMIT $numeroResultados");
							?>
							<div id="showResultsPcPlanTratamiento">
								<table class="tableList">
									<thead>
										<tr>
											<th class="columnaCorta">Fecha</th>
											<th>Odontograma</th>
											<th>Consecutivo</th>
											<th>Creador</th>
											<th>Comentario</th>
											<th>&nbsp</th>
										</tr>
									</thead>
									<tbody>
										<?php while($pcPlanesRow = $pcPlanesSql->fetch_assoc()){

											$creadorPlanSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$pcPlanesRow[plt_idUsuario]'")->fetch_assoc();

											$IDusuarioPlan = $creadorPlanSql['us_id'];
											$nombreUsuarioPlan = '';
					    						if($creadorPlanSql['us_idRol']==1){
					   								$usuarioPlan = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioPlan'")->fetch_assoc();
					   								$nombreUsuarioPlan = $usuarioPlan['cl_nombre'];

					    						} elseif($creadorPlanSql['us_idRol']==2){
					   								$usuarioPlan = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioPlan'")->fetch_assoc();
					   								$nombreUsuarioPlan = $usuarioPlan['sc_nombre'];

					    						} elseif($creadorPlanSql['us_idRol']==3){
					   								$usuarioPlan = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioPlan'")->fetch_assoc();
					   								$nombreUsuarioPlan = $usuarioPlan['dc_nombres'];
					    						}

					    						$planEncodeGenerar = encrypt( 'id='.$pcPlanesRow['IDPlanTratamiento'] );
					                	?>
										<tr>
											<td class="columnaCorta"><?php echo $pcPlanesRow['plt_fechaCreacion']; ?></td>
											<td align="center"><?php echo $pcPlanesRow['pod_consecutivo'] ?></td>
											<td align="center"><?php echo $pcPlanesRow['plt_consecutivo'] ?></td>
											<td><?php echo $nombreUsuarioPlan ?></td>
											<td><?php echo $pcPlanesRow['plt_comentario'] ?></td>
											<td class="tableOption">
												<a title="Ver plan de tratamiento" class="pacienteVerPlan" data-id="<?php echo $pcPlanesRow['IDPlanTratamiento'] ?>">ver</a>	
												<a title="Crear presupuesto" class="nuevoPresupuesto" data-id="<?php echo $pcPlanesRow['IDPlanTratamiento'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
												<a title="Descargar PDF" href="plan-tratamiento-generar?q=<?= $planEncodeGenerar ?>"><i class="fa fa-download"></i></a>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
	                		</div>
						</div>


						<div class="divForm" id="content-10">
							<?php $presupuestosQuery = "SELECT * FROM presupuestos
									INNER JOIN plantratamientos ON presupuestos.pp_idPlan = plantratamientos.IDPlanTratamiento
									WHERE pp_idClinica='$sessionClinica' AND pp_idPaciente='$id' AND pp_estado='1' ORDER BY IDPresupuesto DESC";

								$rowCountPcPresupuestos = $con->query($presupuestosQuery)->num_rows;

								//Initialize Pagination class and create object
								    $pagConfig = array(
										'totalRows' => $rowCountPcPresupuestos,
									    'perPage' => $numeroResultados,
										'link_func' => 'paginationPcPresupuestos'
									);
								    $pagination =  new Pagination($pagConfig);

								$presupuestosSql = $con->query($presupuestosQuery." LIMIT $numeroResultados");
							?>
							<div id="showResultsPcPresupuestos">
								<table class="tableList">
									<thead>
										<tr>
											<th class="estado"></th>
											<th class="columnaCorta">Fecha</th>
											<th>Creador</th>
											<th>Convenio</th>
                                            <th>No. Plan T/to.</th>
											<th>No. presupuesto</th>
											<th>Valor total</th>
											<th>&nbsp</th>
										</tr>
									</thead>
									<tbody>
										<?php while($presupuestosRow = $presupuestosSql->fetch_assoc()){
											$presupuestoAprobado = 'estadoNeutro';
											$checkAprobado = '<i class="fa fa-check-square-o"></i>';
											if($presupuestosRow['pp_aprobado']==1){
												$presupuestoAprobado = 'estadoAprobado';
												$checkAprobado = '';
											}

											$convenioPresupuesto = $con->query("SELECT cnv_nombre, cnv_descuento FROM convenios WHERE IDConvenio = '$presupuestosRow[pp_idConvenio]'")->fetch_assoc();

											$creadorPresupuestoSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$presupuestosRow[pp_idUsuario]'")->fetch_assoc();

											$IDusuarioPresupuesto = $creadorPresupuestoSql['us_id'];
											$nombreUsuarioPresupuesto = '';
					    						if($creadorPresupuestoSql['us_idRol']==1){
					   								$usuarioPresupuesto = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioPresupuesto'")->fetch_assoc();
					   								$nombreUsuarioPresupuesto = $usuarioPresupuesto['cl_nombre'];

					    						} elseif($creadorPresupuestoSql['us_idRol']==2){
					   								$usuarioPresupuesto = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioPresupuesto'")->fetch_assoc();
					   								$nombreUsuarioPresupuesto = $usuarioPresupuesto['sc_nombre'];

					    						} elseif($creadorPresupuestoSql['us_idRol']==3){
					   								$usuarioPresupuesto = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioPresupuesto'")->fetch_assoc();
					   								$nombreUsuarioPresupuesto = $usuarioPresupuesto['dc_nombres'];
					    						}

					    						$presupuestoEncodeGenerar = encrypt( 'id='.$presupuestosRow['IDPresupuesto'] );
										?>
										<tr>
											<td class="estado <?php echo $presupuestoAprobado ?>">&nbsp</td>
											<td><?php echo $presupuestosRow['pp_fechaCreacion'] ?></td>
											<td><?php echo $nombreUsuarioPresupuesto ?></td>
											<td><?php echo $convenioPresupuesto['cnv_nombre'].' '.$convenioPresupuesto['cnv_descuento'].' %' ?></td>
											<td align="center"><?php echo $presupuestosRow['plt_consecutivo'] ?></td>
											<td align="center"><?php echo $presupuestosRow['pp_consecutivo'] ?></td>
											<td align="right"><?php echo '$'.number_format($presupuestosRow['pp_valorTotal'], 0, ".", ","); ?></td>
											<td class="tableOption">
												<a title="Aprobar" class="aprobarPresupuesto" id="<?php echo $presupuestosRow['IDPresupuesto'] ?>"><?php echo $checkAprobado ?></a>
												<a title="Descargar PDF" href="presupuesto-generar?q=<?= $presupuestoEncodeGenerar ?>"><i class="fa fa-download"></i></a>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>
						</div>


						<div class="divForm" id="content-11">
							<?php $pcReferidosQuery = "SELECT * FROM citas AS ct
									INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
									INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
									WHERE pc.pc_idReferido = 'P-$id' AND ct.ct_inicial = '1' ORDER BY ct.ct_fechaOrden DESC ";

								$rowCountPcReferidos = $con->query($pcReferidosQuery)->num_rows;

								//Initialize Pagination class and create object
								    $pagConfig = array(
										'totalRows' => $rowCountPcReferidos,
									    'perPage' => $numeroResultados,
										'link_func' => 'paginationPcReferidos'
									);
								    $pagination =  new Pagination($pagConfig);

								$pcReferidosSql = $con->query($pcReferidosQuery." LIMIT $numeroResultados");

								if($rowCountPcReferidos>0){
							?>
									<div class="container9PartForm">
										<input type="date" id="pcReferidoRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationPcReferidos();">
										<span></span>
										<input type="date" id="pcReferidoRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationPcReferidos();">
									</div>
								<?php } ?>
							
							<div id="showResultsPcReferidos">
								<table class="tableList">
									<thead>
										<tr>
											<th>Paciente</th>
											<th>Tratamiento</th>
											<th>Valor</th>
										</tr>
									</thead>
									<tbody>
										<?php while($pcReferidosRow = $pcReferidosSql->fetch_assoc()){ ?>
										<tr>
											<td><?php echo $pcReferidosRow['pc_nombres'] ?></td>
											<td><?php echo $pcReferidosRow['tr_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($pcReferidosRow['ct_costo'], 0, ".", ",")  ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>
							
						</div>

		<?php } ?>

					</div>
			    </div> 
				
				<div class="modal-footer">
					<input type="hidden" name="id" value="<?php echo $id ?>">
					<!--<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>-->
					<button class="boton boton-primario">Guardar</button>
				</div>
				
</form>

<script type="text/javascript" src="js/label.js"></script>
<script type="text/javascript" src="js/cargaImg.js" async="async"></script>
<script type="text/javascript">
<?php if($id && !empty($pacienteRow['pc_etiqueta'])){ ?>
$( document ).ready(function() {
		$.ajax({
		   	url:"paciente-etiqueta.php",
		    method:"POST",  
		    data:{id:<?= $id ?>},  
		    success:function(data){  
				$('#consultoriosDetails').html(data);  
				$('#consultoriosModal').modal('show');  
			}
		});
});
<?php } ?>


validar('#formPaciente');

$('#departamentos').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-departamentos.php',
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
$('#ocupacion').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-ocupaciones.php',
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
$('#eps').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-eps.php',
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
		cache: false
	}
});
/*
$('#referido').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-pacientes.php',
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
*/
$('#inputrips').select2({
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


	var signaturePad_info = new SignaturePad(document.querySelector('#signature_pad_concent_paciente'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	var imageLoaderPaciente = document.getElementById('firma_file_paciente');
	imageLoaderPaciente.addEventListener('change', handleImagePaciente, false);
	function handleImagePaciente(e) {
		var reader = new FileReader();
		reader.onload = function (event) {
			$('#firma_paciente_image').html( '<img src="'+event.target.result+'"/>' );
			$('#firma_paciente').val(event.target.result);
		}
		reader.readAsDataURL(e.target.files[0]);
		$('#firma_paciente_image').removeClass('ocultar');
		$('#signature_pad_concent_paciente').addClass('ocultar');
	};

	$(document).on('click', '#clear_signature_paciente', function(){
		signaturePad_info.clear();
		$('#firma_paciente').val(null);
		$('#firma_paciente_image').addClass('ocultar');
    	$('#signature_pad_concent_paciente').removeClass('ocultar');
	});

	$(document).on('mouseup', '#signature_pad_concent_paciente', function(){
		$('#firma_paciente').val( document.querySelector('#signature_pad_concent_paciente').toDataURL() );
	});


	
<?php if($sessionRol!=3){ ?>
	$( "#referencia" ).change(function() {

		changeReferido(	$('#referencia').val(), $('#referido').val() ); 

	});

	function changeReferido(ref, id){
		
			$.ajax({
		        type: 'POST',
		        url: 'extras/referido.php',
		        data:{ref:ref, id:id},
		        success: function (html) {
		            $('#referido').html(html);
		        }
		    });		

	}
<?php } ?>
<?php if($id){ ?>

	<?php if($sessionRol!=3 && $pacienteRow['pc_idReferencia'] != 0 ){ ?>

		changeReferido("<?= $pacienteRow['pc_idReferencia'] ?>", "<?= $pacienteRow['pc_idReferido'] ?>");

	<?php } ?>
<?php if($sessionRol!=3){ ?>
		function paginationPcAbonos(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcAbonosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsPcAbonos').html(html);
		        }
		    });
		}
<?php } ?>
		function paginationPcPresupuestos(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcPresupuestosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsPcPresupuestos').html(html);
		        }
		    });
		}

		function paginationPcTratamientos(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcTratamientosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsPcTratamientos').html(html);
		        }
		    });
		}

		function paginationPcCitas(page_num) {
			page_num = page_num?page_num:0;
			var pcCitasSucursal = $('#pcCitasSucursal').val();
			var pcCitasDoctor = $('#pcCitasDoctor').val();
			var pcCitasTratamiento = $('#pcCitasTratamiento').val();
			var pcCitasRangoDe = $('#pcCitasRangoDe').val();
			var pcCitasRangoHasta = $('#pcCitasRangoHasta').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcCitasData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>+'&pcCitasSucursal='+pcCitasSucursal+'&pcCitasDoctor='+pcCitasDoctor+'&pcCitasTratamiento='+pcCitasTratamiento+'&pcCitasRangoDe='+pcCitasRangoDe+'&pcCitasRangoHasta='+pcCitasRangoHasta,
		        success: function (html) {
		            $('#showResultsPcCitas').html(html);
		        }
		    });
		}

		function paginationPcOdontogramas(page_num) {
			page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcOdontogramaData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsPcOdontograma').html(html);
		        }
		    });
		}

		function paginationPcPlanes(page_num) {
			page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcPlanTratamientoData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsPcPlanTratamiento').html(html);
		        }
		    });
		}
<?php if($sessionRol!=3){ ?>
		function paginationPcReferidos(page_num) {
			page_num = page_num?page_num:0;
			var pcReferidoRangoDe = $('#pcReferidoRangoDe').val();
			var pcReferidoRangoHasta = $('#pcReferidoRangoHasta').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/pcReferidosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>+'&pcReferidoRangoDe='+pcReferidoRangoDe+'&pcReferidoRangoHasta='+pcReferidoRangoHasta,
		        success: function (html) {
		            $('#showResultsPcReferidos').html(html);
		        }
		    });
		}
<?php } ?>
		$(document).on('click', '.guardarRips', function(){  
			var valRips = $('#inputrips').val();
			var valAnt = $('input:radio[name=areaRip]:checked').val();
			var comentarioRip = $('#comentarioRip').val();
		    if(valRips != 0 && valAnt)
		    {   
		    	$.ajax({
		        	url:"paciente-rips-guardar.php",
			        method:"POST",
		            data:{valRips:valRips,valAnt:valAnt,comentarioRip:comentarioRip,pacienteID:<?php echo $id ?>}, 
			        success:function(data){  
						$('#listRips').html(data);
					}
			    });  
			}         
		});

		$(document).on('click', '.guardarNoPatologico', function(){  
			var valNoPat = $('#inputNoPatologico').val();
			var comentarioRip = $('#comentarioRip').val();
		    if(valNoPat != 0)
		    {   
		    	$.ajax({
		        	url:"paciente-nopatologico-guardar.php",
			        method:"POST",
		            data:{valNoPat:valNoPat,comentarioRip:comentarioRip,pacienteID:<?php echo $id ?>}, 
			        success:function(data){  
						$('#listRips').html(data);
					}
			    });  
			}         
		});

    	$(document).on('click', '.eliminarRips', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosEs = $(this).attr("es");
			var consultoriosPc = $(this).attr("pc");
			var consultoriosTipo = $(this).attr("tipo");
		    if(consultoriosId != '' && consultoriosPc != '')
		    {  
		    	$.ajax({
		        	url:"paciente-rips-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId,es:consultoriosEs,pc:consultoriosPc,tipo:consultoriosTipo},  
		            success:function(data){  
						$('#listRips').html(data); 
					}
		    	});  
			}            
		});
		$(document).on('click', '.eliminarNoPat', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosEs = $(this).attr("es");
			var consultoriosPc = $(this).attr("pc");
			var consultoriosTipo = $(this).attr("tipo");
		    if(consultoriosId != '' && consultoriosPc != '')
		    {  
		    	$.ajax({
		        	url:"paciente-nopatologico-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId,es:consultoriosEs,pc:consultoriosPc,tipo:consultoriosTipo},  
		            success:function(data){  
						$('#listRips').html(data); 
					}
		    	});  
			}            
		});
		$(document).on('click', '.consultoriosEvolucion', function(){  
			var consultoriosId = $(this).attr("id");
			$(this).removeClass('iconGray');
			var consultoriosEv = 'paciente';
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
<?php if($sessionRol!=3){ ?>
		$(document).on('click', '.consultorioAbono', function(){
		   	$.ajax({
		       	url:"abono.php",  
		        method:"POST", 
		        data:{tp:'pc',pacienteID:<?php echo $id ?>},
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show'); 
				}
		    });
		});
		$(document).on('click', '.consultorioAbonoEditar', function(){
			var consultoriosId = $(this).attr("id");
			if(consultoriosId != '')
			{
			   	$.ajax({
			       	url:"abono.php",  
			        method:"POST", 
			        data:{abonoID:consultoriosId,tp:'pc',pacienteID:<?php echo $id ?>},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
			    });
			}
		});
		$(document).on('click', '.anularAbono', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosPc = $(this).attr("pc");
		    if(consultoriosId != '' && consultoriosPc != '')
		    {  
		    	$.ajax({
		        	url:"paciente-abono-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId,pc:consultoriosPc,tp:'pc'},
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
<?php } ?>
		$(document).on('click', '.consultorioNuevaFoto', function(){  
            var consultoriosId = $(this).attr("id");
            if(consultoriosId != '')
            {  
                $.ajax({
                    url:"foto.php",  
                    method:"POST",  
                    data:{id:consultoriosId},  
                    success:function(data){  
                        $('#consultoriosDetails').html(data);  
                        $('#consultoriosModal').modal('show');  
                    }
                });  
            }            
        });
        $(document).on('click', '.consultorioEliminarFoto', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosT = $(this).attr("t");
			var consultoriosPc = $(this).attr("pc");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId,t:consultoriosT,pc:consultoriosPc},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

        $(document).on('click', '.consultorioNuevoOdontograma', function(){  
			var consultoriosIDPaciente = <?php echo $id ?>;
		    if(consultoriosIDPaciente > 0)
		    {  
		    	$.ajax({
		        	url:"extras/odontograma-nuevo.php",  
		            method:"POST",  
		            data:{pacienteID:consultoriosIDPaciente},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

        $(document).on('click', '.verOdontograma', function(){  
			var consultorioOdontograma = $(this).attr('data-id');
		    if(consultorioOdontograma > 0)
		    {  
		    	$.ajax({
		        	url:"extras/odontograma-nuevo.php",  
		            method:"POST",
		            data:{odontogramaID:consultorioOdontograma,pacienteID:<?php echo $id ?>},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

		$(document).on('click', '.nuevoPlan', function(){
			var consultorioOdontograma = $(this).attr("data-odontograma"); 
		    if(consultorioOdontograma != '')
		    {   
		    	$.ajax({
		        	url:"plan-tratamiento.php",
			        method:"POST",
		            data:{odontogramaID:consultorioOdontograma,pacienteID:<?php echo $id ?>},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');
					}
			    });  
			}         
		});

		$(document).on('click', '#agregarTratamientoPlan', function(){  
			var consultoriosPlanFase = $("#plan-fase").val();
			var consultoriosPlanCombo = $("#plan-combo").val();
			var consultoriosPlanTratamiento = $("#plan-tratamiento").val();
			var consultoriosPlanTratamientoPrecio = $("#plan-tratamiento-precio").val();
			var consultoriosPlanDiente = $("#plan-diente").val();
		    if(consultoriosPlanFase!='' 
		    	&& consultoriosPlanTratamiento!=""
		    	&& consultoriosPlanDiente!="")
		    {
		    	$.ajax({
		        	url:"plan-tratamiento-agregar.php",
			        method:"POST",
		            data:{planFase:consultoriosPlanFase,
		            		planCombo:consultoriosPlanCombo,
		            		planTratamiento:consultoriosPlanTratamiento,
		            		planTratamientoPrecio:consultoriosPlanTratamientoPrecio,
		            		planDiente:consultoriosPlanDiente}, 
			        success:function(data){  
						$('#listFasesPlan').html(data); 
					}
			    });  
			}         
		});

		$(document).on('click', '#eliminarItemPlan', function(){
			var consultorioId = $(this).attr("data-id");
			if(consultorioId > 0){
				$.ajax({
		        	url:"plan-tratamiento-item-eliminar.php",
			        method:"POST",
		            data:{id:consultorioId}, 
			        success:function(data){  
						$('#listFasesPlan').html(data); 
					}
			    });
			}
		});

		$(document).on('click', '.pacienteVerPlan', function(){
			var consultorioId = $(this).attr("data-id"); 
		    if(consultorioId != '')
		    {   
		    	$.ajax({
		        	url:"plan-tratamiento.php",
			        method:"POST",
		            data:{id:consultorioId,ver:1,pacienteID:<?php echo $id ?>},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');
					}
			    });  
			}         
		});

		$(document).on('click', '.nuevoPresupuesto', function(){
			var consultorioPlanId = $(this).attr("data-id"); 
		    if(consultorioPlanId != '')
		    {   
		    	$.ajax({
		        	url:"presupuesto-convenio.php",
			        method:"POST",
		            data:{planID:consultorioPlanId,pacienteID:<?php echo $id ?>},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');
					}
			    });  
			}         
		});	

		$(document).on('click', '.aprobarPresupuesto', function(){
			var consultorioPresupuesto = $(this).attr("id");
			$( this ).html('');
			$( this ).closest("tr").find('.estado').addClass('estadoAprobado');

		    if(consultorioPresupuesto != '')
		    {   
		    	$.ajax({
		        	url:"presupuesto-aprobacion.php",
			        method:"POST",
		            data:{presupuestoID:consultorioPresupuesto},
			        //success:function(data){  
					//	$('.contenedorPrincipal').html(data);  
						//$('#consultoriosModal').modal('show');  
					//}
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
/*
		$(document).on('click', '#descargarPresupuesto', function(){
			var consultoriosPresupuesto = $(this).attr("data-presupuesto");
		    if(consultoriosPresupuesto > 0 )
		    {
		    	$.ajax({
		        	url:"presupuesto-generar.php",
			        method:"GET",
		            data:{id:consultoriosPresupuesto}, 
			        success:function(data){  
						$('#msj-presupuesto').html(data);
					}
			    });  
			}   
		});
/*
		$(document).on('click', '.tab-paciente-antecedentes', function(){
		   	$.ajax({
		       	url:"extras/paciente-antecedentes.php",  
		        method:"POST", 
		        success:function(data){  
					$('#antecedentesCie10').html(data); 
				}
		    });
		});
*/
	$("#antFamiliares").click();
    $("#noPatologicos").hide();

	$("#antFamiliares").click(function() {
        $("#antecedentesCie10").show();
        $("#noPatologicos").hide();
    });
 
    $("#antPatologicos").click(function() {
		$("#antecedentesCie10").show();
        $("#noPatologicos").hide();
    });
 
    $("#antNoPatologicos").click(function() {
        $("#antecedentesCie10").hide();
        $("#noPatologicos").show();
    });

/*
	  jQuery(document).ready(function($) {
 
		        $('#myCarousel').carousel({
		                interval: 10000
		        });
		 
		        //Handles the carousel thumbnails
		        $('[id^=carousel-selector-]').click(function () {
		        var id_selector = $(this).attr("id");
		        try {
		            var id = /-(\d+)$/.exec(id_selector)[1];
		            console.log(id_selector, id);
		            jQuery('#myCarousel').carousel(parseInt(id));
		        } catch (e) {
		            console.log('Regex failed!', e);
		        }
		    });
		        // When the carousel slides, auto update the text
		        $('#myCarousel').on('slid.bs.carousel', function (e) {
		                 var id = $('.item.active').data('slide-number');
		                $('#carousel-text').html($('#slide-content-'+id).html());
		        });
		});
*/
	var carousel = document.getElementById("registro-fotografico");
	$(carousel).carousel({
		interval: 0
	})
	

    $(document).on('click', '.ver-carousel-selector', function(){  
        var idRegFoto = $(this).attr("data-img");
        $('.active').removeClass('active');
        $('#carousel-selector-'+idRegFoto).addClass('active');
        openFullscreen();
            
    });

/* View in fullscreen */
function openFullscreen() {
  if (carousel.requestFullscreen) {
    carousel.requestFullscreen();
  } else if (carousel.mozRequestFullScreen) { /* Firefox */
    carousel.mozRequestFullScreen();
  } else if (carousel.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
    carousel.webkitRequestFullscreen();
  } else if (carousel.msRequestFullscreen) { /* IE/Edge */
    carousel.msRequestFullscreen();
  }
}

/* Close fullscreen */
function closeFullscreen() {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) { /* Firefox */
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE/Edge */
    document.msExitFullscreen();
  }
}
/* Odontograma 
cargarOdontograma();

$("#fechaOdontograma").change( function() {
	cargarOdontograma();
} );

function cargarOdontograma(){
	var valFecha = $("#fechaOdontograma").val();
	$.ajax({
			type: "POST",
			url: "odontograma-esquema.php",
			data: {valFecha:valFecha,pacienteID:<?php echo $id ?>},
			cache: false,
			success: function(datos){
				$('#esquemaOdontograma').html(datos);
			}
		});
};
*/

<?php } ?>
</script>
</body>
</html>