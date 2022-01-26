<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

if($sessionRol==2){
	$id = $sessionUsuario;
} else {
	$id = $_POST['id'];	
}

$sucursalSql = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$id'");
$sucursalRow = $sucursalSql->fetch_assoc();

$ciudadSC = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$sucursalRow[sc_idCiudad]'")->fetch_assoc();

?>
<form class="form" id="formSucursal" method="post" action="sucursal-guardar.php">
	<div class="modal-header modal-header-form">
	  <div class="titulo tituloSecundario"><?php if($id){ echo 'Sucursal: '.$sucursalRow['sc_nombre'].' <a href="excel-citas-sucursal.php?id='. $id .'"><i class="fa fa-download"></i>Historial Citas</a>'; }else{echo'Nueva Sucursal';} ?></div>
	  <button class="boton boton-primario">Guardar</button>
	</div>

	<div class="contenedorTabs">
		<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Información</label>
		<?php if($id){ ?>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Abonos</label>
			<input id="tab-3" type="radio" name="tab-group" />
			<label for="tab-3" class="labelTab">Unidades</label>
		<?php } ?>

		<div class="contenidoTab">
			<div class="divForm" id="content-1">
				<input type="text" name="nombre" value="<?php echo $sucursalRow['sc_nombre'] ?>" class="formulario__input" data-label="Nombre">
				<input type="text" name="telefono" value="<?php echo $sucursalRow['sc_telefonoFijo'] ?>" class="formulario__input" data-label="Teléfono Fijo">
				<input type="email" name="correo" id="correoS" value="<?php echo $sucursalRow['sc_correo'] ?>" class="formulario__input" data-label="Correo Electrónico">
				<select name="ciudad" id="ciudad" class="formulario__input" data-label="Ciudad">
						<?php
							if($sucursalRow['sc_idCiudad']!=0){
				            	echo "<option value=".$ciudadSC['IDCiudad']." selected>".$ciudadSC['cd_nombre']."</option>";
				            }
				        ?>
				</select>
				<input type="text" name="direccion" value="<?php echo $sucursalRow['sc_direccion'] ?>" class="formulario__input" data-label="Dirección">
				<div class="container3PartForm">
					<select name="horarioDe" id="horarioDe" class="formulario__input" data-label="Atención de">
						<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
						<?php
							$horaDeSql = $con->query("SELECT * FROM horas");
			            	while($horaDeRow = $horaDeSql->fetch_assoc()){
			            		$horaDeSelected = '';
			            		if($horaDeRow['hr_hora']==$sucursalRow['sc_atencionDe']){ $horaDeSelected = "selected"; }
			            		echo "<option value=".$horaDeRow['hr_hora']." ".$horaDeSelected.">".$horaDeRow['hr_hora']."</option>";	
							}
			            ?>
		            </select>
		            <span></span>
		            <select name="horarioHasta" id="horarioHasta" class="formulario__input" data-label="Atención hasta">
						<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
						<?php
							$horaHastaSql = $con->query("SELECT * FROM horas");
			            	while($horaHastaRow = $horaHastaSql->fetch_assoc()){
			            		$horaHastaSelected = '';
			            		if($horaHastaRow['hr_hora']==$sucursalRow['sc_atencionHasta']){ $horaHastaSelected = "selected"; }
			            		echo "<option value=".$horaHastaRow['hr_hora']." ".$horaHastaSelected.">".$horaHastaRow['hr_hora']."</option>";	
							}
			            ?>
		            </select>
		        </div>
	            <div class="contenedorCheckbox SliderSwitch pointer">
					<label for="switch">Enviar notificaciones
					<input id="switch" type="checkbox" name="enviarAlertas" value="1" <?php if($sucursalRow['sc_enviarCorreo']==1){echo"checked";} ?>>
					<div class="SliderSwitch__container">
						<div class="SliderSwitch__toggle"></div>
					</div>
					</label>
				</div>

			</div>



			<div class="divForm listAbonos" id="content-2">
				<?php $abonosSucuralQuery = "SELECT * FROM abonos, usuarios, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idSucursal = '$id' ORDER BY abonos.IDAbono DESC";

					$rowCountScAbonos = $con->query($abonosSucuralQuery)->num_rows;
					$pagConfig = array(
						'totalRows' => $rowCountScAbonos,
					    'perPage' => $numeroResultados,
						'link_func' => 'paginationScAbonos'
					);
				    $pagination =  new Pagination($pagConfig);
				    $abonosSucuralSql = $con->query($abonosSucuralQuery." LIMIT $numeroResultados");

				?>
					<div class="containerPart">
						<span></span>
						<div class="titulo tituloSecundario">
							<?php if($rowCountScAbonos > 0){ ?>
						   		<a href="sucursal-abonos-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Historial abonos</a>
						   	<?php } ?>
						</div>
					</div>
					<div id="showResultsScAbonos">
						<table class="tableList">
							<thead>
								<tr>
						    		<th class="estado">&nbsp</th>
									<th>#</th>
						    		<th class="columnaCorta">Fecha</th>
						    		<th>Usuario</th>
						    		<th>Paciente</th>
						    		<th>Forma Pago</th>
						    		<th align="right">Valor Abono</th>
						    		<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									while($abonosSucuralRow = $abonosSucuralSql->fetch_assoc()){

										$nombreUsuarioAbono = '';
						    			$IDusuarioAbono = $abonosSucuralRow['us_id'];
						    			if($abonosSucuralRow['us_idRol']==1){
						   					$usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
						   					$nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
						    			} elseif($abonosSucuralRow['us_idRol']==2){
						   					$usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
						   					$nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
						    			} elseif($abonosSucuralRow['us_idRol']==3){
						   					$usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
						   					$nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
						    			}

						    			if($abonosSucuralRow['ab_estado']==1){
						    				$estadoAbono = 'estadoNeutro';
						    			} else {
						    				$estadoAbono = 'estadoCancelado';
						    			}

						    			if($abonosSucuralRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
										} else { $iUS = ''; $cUS = ''; }

						    			if($abonosSucuralRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
										} else { $iPC = ''; $cPC = ''; }

										$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosSucuralRow[ab_idFormaPago]'")->fetch_assoc();

										if($abonosSucuralRow['ab_idFormaPago']==2){
											$bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosSucuralRow[ab_idBanco]'")->fetch_assoc();
											$abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosSucuralRow['ab_cheque'];
										} else {
											$abonoFormaPago = $formaPago['fp_nombre'];
										}
								?>
								<tr>
						    		<td class="estado <?php echo $estadoAbono ?>"></td>
									<td align="right"><?php echo $abonosSucuralRow['ab_consecutivo'] ?></td>
						    		<td align="center"><?php echo $abonosSucuralRow['ab_fechaCreacion'] ?></td>
						    		<td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
						    		<td class="<?php echo $cPC ?>"><?php echo $iPC.$abonosSucuralRow['pc_nombres'] ?></td>
					    			<td align="center"><?php echo $abonoFormaPago ?></td>
						    		<td align="right" class="columnaCorta"><?php echo '$'.number_format($abonosSucuralRow['ab_abono'], 0, ".", ","); ?></td>
						    		<td class="tableOption">
						    			<a href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosSucuralRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
									   	<?php if($abonosSucuralRow['ab_estado']==1){ ?>
						    				<a class="consultorioAbonoEditar" id="<?php echo $abonosSucuralRow['IDAbono'] ?>" pc="<?php echo $abonosSucuralRow['IDPaciente'] ?>"><?php echo $iconoEditar ?></a>
											<a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosSucuralRow['IDAbono'] ?>" pc="<?php echo $abonosSucuralRow['IDPaciente'] ?>"><?php echo $iconoEliminar ?></a>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
			</div>


			<div class="divForm" id="content-3">
				<div id="msj-unidades" class="contenedorAlerta"></div>
				<?php $unidadesSucursalQuery = "SELECT * FROM unidadesodontologicas WHERE uo_idSucursal = '$id'  AND uo_estado = 1";

					$rowCountScUnidades = $con->query($unidadesSucursalQuery)->num_rows;
					$pagConfig = array(
						'totalRows' => $rowCountScUnidades,
					    'perPage' => $numeroResultados,
						'link_func' => 'paginationScUnidades'
					);
				    $pagination =  new Pagination($pagConfig);
				    $unidadesSucuralSql = $con->query($unidadesSucursalQuery." LIMIT $numeroResultados");
				?>
					<div class="titulo tituloSecundario"><a id="nuevaUnidad"><?php echo $iconoNuevo ?>Nueva unidad</a></div>

					<div id="showResultsScUnidades">
						<table class="tableList">
			        		<thead>
			        			<tr>
			        				<th>Nombre</th>
									<th>&nbsp</th>
			        			</tr>
			        		</thead>
			            	<tbody>
			        	<?php while($unidadesRow = $unidadesSucuralSql->fetch_assoc()){	?>
				        		<tr>				        			
				        			<td><?php echo $unidadesRow['uo_nombre'] ?></td>
				        			<td class="tableOption">
				        				<a id="<?php echo $unidadesRow['IDUnidadOdontologica'] ?>" class="consultorioHorario"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></a>
								    	<a id="<?php echo $unidadesRow['IDUnidadOdontologica'] ?>" class="consultorioEliminarUnidad eliminar"><?php echo $iconoEliminar ?></a>
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
	   
	<div class="modal-footer">  
		
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<!--<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> -->
		<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label.js"></script>
<script type="text/javascript">
validar('#formSucursal');

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



<?php if($id){ ?>
		function paginationScAbonos(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/scAbonosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsScAbonos').html(html);
		        }
		    });
		}
		function paginationScUnidades(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/scUnidadesData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#showResultsScUnidades').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioAbonoEditar', function(){
			var consultoriosId = $(this).attr("id");
			var consultoriosPc = $(this).attr("pc");
			if(consultoriosId != '')
			{
			   	$.ajax({
			       	url:"abono.php",  
			        method:"POST", 
			        data:{abonoID:consultoriosId,tp:'sc',pacienteID:consultoriosPc,sucursalID:<?php echo $id ?>},
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
		            data:{id:consultoriosId,pc:consultoriosPc,tp:'sc',sucursalID:<?php echo $id ?>},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
		$(document).on('click', '#nuevaUnidad', function(){
		    if(<?php echo $id ?> > 0)
		    {  
		    	$.ajax({
		        	url:"sucursal-unidad.php",  
		            method:"POST",  
		            data:{sucursalID:<?php echo $id ?>},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
		$(document).on('click', '.consultorioEliminarUnidad', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"sucursal-unidad-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId,sucursalID:<?php echo $id ?>},  
		            success:function(data){  
						$('#showResultsScUnidades').html(data);
					}
		    	});  
			}            
		});
		$(document).on('click', '.consultorioHorario', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {
		    	$.ajax({
		        	url:"sucursal-unidad-horario.php",  
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