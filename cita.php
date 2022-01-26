<?php include'config.php'; $pacienteID = $_GET['id']; $citaID = $_GET['cita'];

$pacienteSql = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteID'");
$pacienteRow = $pacienteSql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
	<link href="css/calendar.min.css" rel="stylesheet">
	
</head>
<body>
	<div class="contenedorPrincipal">
		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="titulo tituloSecundario">Nueva Cita: <?php echo $pacienteRow['pc_nombres'] ?></div>
<p>&nbsp</p>
		
		<div class="contenedorAgenda">
			<div class="agendaCalendario">
				<form class="form">
					<div class="divForm">
					<?php if($sessionRol==1){ ?>
						<select id="sucursalForm" class="formulario__input" data-label="Sucursal" required>
							<option selected hidden value="">-- Seleccionar --</option>
							<?php
								$sucursalesSql = $con->query("SELECT * FROM sucursales WHERE sc_idClinica = '$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");
				            	while($sucursalesRow = $sucursalesSql->fetch_assoc()){

				            		$sucursalDisabled = '';
				            		$sucursalDisabledMsj = '';
				            		$countUnidades = $con->query("SELECT COUNT(*) AS cantidad FROM unidadesodontologicas WHERE uo_idSucursal = '$sucursalesRow[IDSucursal]' AND uo_estado = 1")->fetch_assoc();
				            		if($countUnidades['cantidad'] == 0){
				            			$sucursalDisabled = 'disabled';
				            			$sucursalDisabledMsj = ' <i>(sin unidades)</i>';
				            		}
				            		echo "<option value=".$sucursalesRow['IDSucursal']." $sucursalDisabled>".$sucursalesRow['sc_nombre'].$sucursalDisabledMsj."</option>";
								}
				            ?>
			            </select>
			        <?php } else if($sessionRol==2){ ?>
			        	<input type="hidden" id="sucursalForm" value="<?php echo $sessionUsuario ?>" required>
			        <?php } ?>
			        	<select id="unidadForm" class="formulario__input" data-label="Unidad" required>
			        		<option selected hidden value="">-- Seleccionar Sucursal --</option>
			        	</select>
			            <select id="doctorForm" class="formulario__input" data-label="Doctor" required>
							<option selected hidden value="">-- Seleccionar --</option>
							<?php
								$doctoresSql = $con->query("SELECT * FROM doctores WHERE dc_idClinica = '$sessionClinica' AND dc_estado='1' ORDER BY dc_nombres");
				            	while($doctoresRow = $doctoresSql->fetch_assoc()){
				            		echo "<option value=".$doctoresRow['IDDoctor'].">".$doctoresRow['dc_nombres']."</option>";	
								}
				            ?>
			            </select>

			            <select id="tratamientoForm" class="formulario__input" data-label="Tratamiento" required>
							<option selected hidden value="">-- Seleccionar --</option>
							<optgroup label="Tratamientos activos">
							<?php
								$tratamientosActivosSql = $con->query("SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pacienteID' AND citas.ct_inicial = '1' AND citas.ct_terminado = '1' AND tratamientos.tr_estado='1' ORDER BY citas.ct_fechaOrden DESC");
								$tratamientosActivosNum = $tratamientosActivosSql->num_rows;
								if($tratamientosActivosNum>0){
									while($tratamientosActivosRow = $tratamientosActivosSql->fetch_assoc()){
										echo "<option value=".$tratamientosActivosRow['IDTratamiento'].">".$tratamientosActivosRow['tr_nombre']."</option>";		
									}
								} else {
									echo "<option disabled >Ninguno</option>";
								}
							?>
							</optgroup>



							<optgroup label="Tratamientos de presupuestos">
							<?php
								$tratamientosConvenio = $con->query("SELECT IDPresupuestoTrata, pp_idConvenio, cnv_nombre, cnv_descuento, tr_nombre, ppt_idTratamiento, ppt_precio FROM presupuestos AS pp
								INNER JOIN convenios AS cnv ON pp.pp_idConvenio = cnv.IDConvenio
								INNER JOIN presupuestotratamientos AS ppt ON ppt.ppt_idPresupuesto = pp.IDPresupuesto
								INNER JOIN tratamientos AS tr ON ppt.ppt_idTratamiento = tr.IDTratamiento
								WHERE pp_idPaciente = '$pacienteID'
									AND pp_estado=1 
									AND pp_aprobado=1 
									AND tr_estado=1
									AND ppt_activo=1 
									ORDER BY cnv_nombre ASC, tr_nombre ASC
								");
								$tratamientosConvenioNum = $tratamientosConvenio->num_rows;
								$countTratamientosConvenio = 0;
								if($tratamientosConvenioNum>0){

					            	while($rowTratamientosConvenio = $tratamientosConvenio->fetch_assoc()){

					            		$precioTratamientoConvenio = $rowTratamientosConvenio['ppt_precio'] - ( ( $rowTratamientosConvenio['ppt_precio'] * $rowTratamientosConvenio['cnv_descuento'] ) /100 );

					            		$precioTratamientoConvenioView = '$'.number_format($precioTratamientoConvenio, 2, ",", ".");

					            		if($rowTratamientosConvenio['pp_idConvenio'] > 1000){
					            			$tratamientoConvenioView = $rowTratamientosConvenio['cnv_nombre'].' - '.$rowTratamientosConvenio['tr_nombre'].' - '.$precioTratamientoConvenioView;
					            		} else {
					            			$tratamientoConvenioView = $rowTratamientosConvenio['tr_nombre'].' - '.$precioTratamientoConvenioView;
					            		}

					            		

					            		$TratamientoConveniosActivo = $con->query("SELECT COUNT(*) AS activos FROM citas WHERE ct_idPaciente='$pacienteID' 
					            				AND ct_idTratamiento='$rowTratamientosConvenio[ppt_idTratamiento]' 
					            				
					            				AND ct_inicial=1 
					            				AND ct_terminado=1")->fetch_assoc();

										if($TratamientoConveniosActivo['activos']==0){
											echo "<option value=".$rowTratamientosConvenio['ppt_idTratamiento']." data-valor=".$precioTratamientoConvenio." data-presupuesto='1' data-tratamiento=".$rowTratamientosConvenio['IDPresupuestoTrata'].">".$tratamientoConvenioView."</option>";
											$countTratamientosConvenio++;
										}
									}

									if($countTratamientosConvenio==0){
										echo "<option disabled >Activos - en proceso</option>";
									}
					            } else {
									echo "<option disabled >Ninguno</option>";
								}

							?>
							</optgroup>



<!--
							<optgroup label="Tratamientos de presupuestos">
							<?php $tratamientosPresupuestoSql = $con->query("SELECT DISTINCT ppt_idTratamiento, tr_nombre FROM presupuestotratamientos AS ppt 
									INNER JOIN presupuestos AS pp ON ppt.ppt_idPresupuesto = pp.IDPresupuesto
									INNER JOIN tratamientos AS tr ON ppt.ppt_idTratamiento = tr.IDTratamiento
									WHERE pp_idPaciente = '$pacienteID'
									AND pp_estado=1 
									AND pp_aprobado=1 
									AND tr_estado=1
									AND ppt_activo=1");
								$tratamientosPresupuestoNum = $tratamientosPresupuestoSql->num_rows;
								$countPresupuestoActivo = 0;
								if($tratamientosPresupuestoNum>0){
									while($tratamientosPresupuestosRow = $tratamientosPresupuestoSql->fetch_assoc()){

										$comparacionActivo = $con->query("SELECT COUNT(*) AS activos FROM citas WHERE ct_idPaciente='$pacienteID' AND ct_idTratamiento='$tratamientosPresupuestosRow[ppt_idTratamiento]' AND ct_inicial=1 AND ct_terminado=1")->fetch_assoc();

										if($comparacionActivo['activos']==0){
											echo "<option value=".$tratamientosPresupuestosRow['ppt_idTratamiento'].">".$tratamientosPresupuestosRow['tr_nombre']."</option>";
											$countPresupuestoActivo++;
										}
									}

									if($countPresupuestoActivo==0){
										echo "<option disabled >Activos</option>";
									}
								} else {
									echo "<option disabled >Ninguno</option>";
								}


							?>
							</optgroup>
	-->							
							<optgroup label="Combos">		
							<?php
								$combosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica = '$sessionClinica' AND tr_estado='1' AND tr_combo='1' ORDER BY tr_nombre");
				            	while($combosRow = $combosSql->fetch_assoc()){

				            			echo "<option value=".$combosRow['IDTratamiento']." data-combo='1'>".$combosRow['tr_nombre']."</option>";	
				            						            		
								}
				            ?>
				            </optgroup>
							<optgroup label="Tratamientos libres">		
							<?php
								$tratamientosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica = '$sessionClinica' AND tr_estado='1' AND tr_combo='0' ORDER BY tr_nombre");
				            	while($tratamientosRow = $tratamientosSql->fetch_assoc()){

				            		$tratamientosActivos = $con->query("SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pacienteID' AND citas.ct_inicial = '1' AND citas.ct_terminado = '1' AND tratamientos.IDTratamiento = '$tratamientosRow[IDTratamiento]'");
				            		if($tratamientosActivos->num_rows == 0) {
				            			echo "<option value=".$tratamientosRow['IDTratamiento']." data-valor=".$tratamientosRow['tr_precio'].">".$tratamientosRow['tr_nombre'].' - '.'$'.number_format($tratamientosRow['tr_precio'], 2, ",", ".")."</option>";	
				            		}				            		
								}
				            ?>
				            </optgroup>
			            </select>

						<div id="content-combo-tratamientoForm">
				            <select name="combo-tratamientoForm" id="combo-tratamientoForm" class="formulario__input" data-label="Tratamientos de Combos">
				            	<option selected hidden value="">-- Seleccionar --</option>           	
				            </select>
				        </div>


			            <input type="hidden" name="tipo-tratamiento" id="tipo-tratamiento" value="0" readonly disabled>
			            <input type="hidden" name="tratamiento-ID" id="tratamiento-ID" value="0" readonly disabled>
			            <input type="hidden" name="tratamiento-valor" id="tratamiento-valor" value="0" readonly disabled>
			            <input type="hidden" name="tratamiento-presupuesto" id="tratamiento-presupuesto" value="0" readonly disabled>
			        </div>
				</form>
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

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<script type="text/javascript" src="js/label.js"></script>
	<script src="js/calendar.min.js"></script>
	<script>

<?php if($pacienteID && !empty($pacienteRow['pc_etiqueta'])){ ?>
$( document ).ready(function() {
		$.ajax({
		   	url:"paciente-etiqueta.php",
		    method:"POST",
		    data:{id:<?= $pacienteID ?>},  
		    success:function(data){  
				$('#consultoriosDetails').html(data);  
				$('#consultoriosModal').modal('show');  
			}
		});
});
<?php } ?>




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

	
		var diaSeleccionado = '';
		var sucursalForm = 	$('#sucursalForm');
		var unidadForm = 	$('#unidadForm');
		var doctorForm = 	$('#doctorForm');
		var tratamientoForm = 	$('#tratamientoForm');
		var tipo_tratamiento = $('#tipo-tratamiento');
		var tratamientosCombo = $('#combo-tratamientoForm');
		var tratamientoID = $('#tratamiento-ID');
		var tratamientoPrecio = $('#tratamiento-valor');
		var tratamientoPresupuesto = $('#tratamiento-presupuesto');
		var tratamientoValor = 0;

		sucursalForm.change(function() { unidadForm.val(''); crearCita(); });
		unidadForm.change(function() { crearCita(); });
		doctorForm.change(function() { crearCita(); });
//		tratamientoForm.change(function() { crearCita(); });
//		tratamientosCombo.change(function() { crearCita(); });

		$(document).on('click', '.diaSelected', function(){

			var diasActivos = document.querySelectorAll(".diaSelectedActive");
			for (var i = diasActivos.length - 1; i >= 0; i--) {
				diasActivos[i].classList.remove("diaSelectedActive");
			}

			diaSeleccionado = $(this);
         	crearCita();
		})

		function crearCita(){

			if(sucursalForm.val() == ''){ sucursalForm.addClass('validar'); }
				else { sucursalForm.removeClass('validar'); }
			if(unidadForm.val() == ''){ unidadForm.addClass('validar'); }
				else { unidadForm.removeClass('validar'); }
			if(doctorForm.val() == ''){ doctorForm.addClass('validar'); }
				else { doctorForm.removeClass('validar'); }
			if(tratamientoForm.val() == ''){ tratamientoForm.addClass('validar'); }
				else {
					tratamientoValor = tratamientoForm.val();
					tratamientoForm.removeClass('validar');
				}
			if(tipo_tratamiento.val() == 2){
				if(tratamientosCombo.val() == ''){ tratamientosCombo.addClass('validar'); }
					else {
						tratamientoValor = tratamientosCombo.val();
						tratamientosCombo.removeClass('validar');
					}
			} else { tratamientosCombo.addClass('validar'); }

			var consultoriosId = '';
			if(diaSeleccionado!=""){
				consultoriosId = diaSeleccionado.attr("id");
			}
		    if(consultoriosId != '' && sucursalForm.val() != '' && doctorForm.val() != '' && tratamientoValor != 0)
		    {

		    	diaSeleccionado.addClass("diaSelectedActive");

		    	$.ajax({
		        	url:"cita-horario.php",
		            method:"POST",  
		            data:{
		            	id:consultoriosId,
		            	sucursal:sucursalForm.val(),
		            	unidad:unidadForm.val(),
		            	doctor:doctorForm.val(),
		            	tratamiento:tratamientoID.val(),
		            	tipoTratamiento:tipo_tratamiento.val(),
		            	tratamientoPrecio:tratamientoPrecio.val(),
		            	tratamientoPresupuesto:tratamientoPresupuesto.val(),
		            	paciente:<?php echo $pacienteID; 
		            	if($citaID!=""){ echo ", cita:$citaID"; } ?>
		            },  
		            cache: false,
					success:function(data){  
						$('#horasCalendario').html(data);
					}
		    	});  
			}

		}


	$('#content-combo-tratamientoForm').hide();
	var tipo_tratamiento = $('#tipo-tratamiento');
	var tratamiento_ID = $('#tratamiento-ID');
	var tratamiento_valor = $('#tratamiento-valor');
	var tratamiento_presupuesto = $('#tratamiento-presupuesto');

		tipo_tratamiento.val(0);
		tratamiento_ID.val(0);
		tratamiento_valor.val(0);

	$( "#tratamientoForm" ).change(function() {
		$('#tratamientoForm option:selected').each(function() {

			if( $( this ).attr('data-combo') == 1 ){
				$('#content-combo-tratamientoForm').show();
				$( "#combo-tratamientoForm" ).attr('required','required');
				var combo = $( this ).val();
				tipo_tratamiento.val(2);
				tratamiento_ID.val(0);
				tratamiento_valor.val(0);
				tratamiento_presupuesto.val(0);

				$.ajax({
					url: 'extras/tratamientosCombos.php',
					type: 'POST',
					data: {combo:combo},
					success:function(data){  
						$('#combo-tratamientoForm').html(data);
					}
				});

				$( "#combo-tratamientoForm" ).change(function() {
					$('#combo-tratamientoForm option:selected').each(function() {
						tratamiento_ID.val( $( this ).val() );
						tratamiento_valor.val( $( this ).attr('data-valor') );
					});

					 crearCita();
				});

			} else {
				$('#content-combo-tratamientoForm').hide();				
				$( "#combo-tratamientoForm" ).removeAttr('required');
				tipo_tratamiento.val( $( this ).attr('data-presupuesto') );
				tratamiento_ID.val( $( this ).val() );
				tratamiento_valor.val( $( this ).attr('data-valor') );
				tratamiento_presupuesto.val( $( this ).attr('data-tratamiento') );
			}
			
		})

		crearCita();
	});


		
	});	

	
<?php if($sessionRol==2){ ?>

		$.ajax({
			url: 'extras/unidades.php',
			type: 'POST',
			data: {sucursalID:<?= $sessionUsuario ?>},
			success:function(data){  
				$('#unidadForm').html(data);
			}
		});

<?php } else { ?>

	$( "#sucursalForm" ).change(function() {
		var valor = $(this).val();

		$.ajax({
			url: 'extras/unidades.php',
			type: 'POST',
			data: {sucursalID:valor},
			success:function(data){  
				$('#unidadForm').html(data);
			}
		});
	});
	
<?php } ?>


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

	</script>

	
</body>
</html>