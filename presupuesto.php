<?php include'config.php';

$pacienteID = $_POST['pacienteID'];
$planID = $_POST['planID'];

$convenioNuevo = $_POST['convenioValorNuevo'];
if($convenioNuevo==1){

	$convenioNuevoNombre = $_POST['convenio-nombre'];
	$convenioNuevoValor = $_POST['convenio-valor'];
	$nuevoConvenio = $con->query("INSERT INTO convenios SET cnv_idClinica='$sessionClinica', cnv_idUsuario='$sessionIDUsuario', cnv_nombre='$convenioNuevoNombre', cnv_descuento='$convenioNuevoValor', cnv_estado='1', cnv_fechaCreacion='$fechaHoy'");
	$convenioID = $con->insert_id;
} else {
	
	$convenioID = $_POST['convenio'];
}

$_SESSION['consultorioTmpPlanID'] = $planID;
//$_SESSION['consultorioTmpConvenioID'] = $convenioID;

$pacienteRow = $con->query("SELECT pc_nombres, IDPaciente FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();
$presupuesto = $con->query("INSERT INTO presupuestos SET pp_idClinica='$sessionClinica', pp_idUsuario='$sessionIDUsuario', pp_idPaciente='$pacienteID', pp_idPlan='$planID', pp_idConvenio='$convenioID', pp_estado='0', pp_fechaCreacion='$fechaHoy'");
$presupuestoID = $con->insert_id;

$descuentoConvenio = $con->query("SELECT cnv_descuento, cnv_nombre FROM convenios WHERE cnv_idClinica IN('$sessionClinica',0) AND IDConvenio='$convenioID'")->fetch_assoc();

$sumaTotal = 0;
$subTotalPresupuesto = 0;
$totalPresupuesto = 0;
?>
<style type="text/css">
	.select2-search{ display: none !important; }
</style>

<form class="form" id="formPresupesto" method="post" action="#">
	<div class="modal-header">  
	  <a class="close" data-dismiss="modal">&times;</a>  
	  <h4 class="modal-title">Presupuesto: <?php echo $pacienteRow['pc_nombres']; ?></h4>
	</div>
	<div class="modal-body">
		<div class="divForm">
			<div id="msj-presupuesto"></div>
<?php
						$fasesSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica)");
							while($fasesRow = $fasesSql->fetch_assoc()){
								$tratamientosFase = $con->query("SELECT * FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE tr_idFase='$fasesRow[IDFase]' AND pltt_idPlan='$planID'")->num_rows;
								if($tratamientosFase>0){
							?>
								<div class="titulo tituloSecundario top">Fase <?= $fasesRow['fs_nombre'] ?></div>
				            	<table class="tableList tableListAuto tableListTop">
				            		<thead>
								        <tr>
								          <th class="contenedorCheckboxMin">&nbsp</th>
								          <th>Tratamiento</th>
								          <th class="columnaCorta">Diente</th>
								          <th class="columnaCorta">Precio</th>
								        </tr>
								     </thead>
				            		<tbody>
				            	<?php $tratamientosSql = $con->query("SELECT IDPlanTrataTrata, pltt_diente, pltt_combo, pltt_precio, IDTratamiento, tr_nombre, tr_estado FROM plantratatratamientos AS pltt 
				            		INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento 
				            		WHERE pltt_idPlan = '$planID' AND tr_idFase = '$fasesRow[IDFase]' 
				            		ORDER BY pltt_diente ASC, tr_nombre ASC");
				            		while($tratamientosRow = $tratamientosSql->fetch_assoc()){
				            			$cantidadItems++;

				            			$comboTratamiento = '';	
					            			if($tratamientosRow['pltt_combo']>0){

					            				$comboTratamientoQuery = $con->query("SELECT tr_nombre FROM tratamientos WHERE IDTratamiento = '$tratamientosRow[pltt_combo]'")->fetch_assoc();
					            				$comboTratamiento = '<i>'.$comboTratamientoQuery['tr_nombre'].' |</i> '; 
					            			}

				            			if($tratamientosRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
										} else { $iTR = ''; $cTR = ''; }
				            	?>
				            			<tr class="<?php echo $cTR ?>">
				            				<?php if($tratamientosRow['tr_estado']==0){ ?>
											<td>&nbsp</td>
				            				<?php } else { ?>
				            				<td class="contenedorCheckbox">
												<input type="checkbox" class="checkTratamiento" id="tratamiento_<?php echo $tratamientosRow['IDPlanTrataTrata'] ?>" name="tratamiento_<?php echo $tratamientosRow['IDPlanTrataTrata'] ?>" value="1" data-precio="<?php echo $tratamientosRow['pltt_precio'] ?>">
												<label for="tratamiento_<?php echo $tratamientosRow['IDPlanTrataTrata'] ?>" class="labelChek"></label>
											</td>
											<?php } ?>
					            			<td><?php echo $iTR.$comboTratamiento.$tratamientosRow['tr_nombre']; ?></td>
					            			<td align="center"><?php echo $tratamientosRow['pltt_diente'] ?></td>
					            			<td align="right"><?php echo '$'.number_format($tratamientosRow['pltt_precio'], 0, ".", ","); ?></td>
					            		</tr>
					            <?php } ?>
				            		</tbody>
				            	</table>				            	
				            <?php }
				        		}
				        	?>
						
							<table class="tableListAuto" style="text-align: right;">
								<tr>
									<th>Subtotal presupuesto:</th>
									<td class="columnaCorta" id="subTotalPresupuesto">0.00</td>
								</tr>
								<tr>
									<th>Convenio <?php echo $descuentoConvenio['cnv_nombre'] ?>:</th>
									<td id="convenioPresupuesto">0 %</td>
								</tr>
								<tr>
									<th>Total presupuesto:</th>
									<td id="totalPresupuesto">0.00</td>
								</tr>
							</table>


			<div class="containerFirmas">
				<div class="content_signature">
					<canvas id="signature_pad_presupuesto_paciente" class="signature_pad" width=400 height=200></canvas>

					<div class="option_signature_pad">
						Firma Paciente
						<span id="clear_signature_presupuesto_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
					</div>

					<input type="hidden" name="firma_presupuesto_paciente" id="firma_presupuesto_paciente">
				</div>

				<div class="content_signature">
					<canvas id="signature_pad_presupuesto_usuario" class="signature_pad" width=400 height=200></canvas>

					<div class="option_signature_pad">
						Firma Usuario
						<span id="clear_signature_presupuesto_usuario" title="Limpiar"><i class="fa fa-times"></i></span>
					</div>

					<input type="hidden" name="firma_presupuesto_usuario" id="firma_presupuesto_usuario">
				</div>
			</div>
				     
		</div>
	</div>

	<div class="modal-footer">  
		
			<input type="hidden" id="Pre-cantidad" value="1">
			<input type="hidden" name="id" value="<?php echo $pacienteID ?>">
			<input type="hidden" name="presupuestoID" value="<?php echo $presupuestoID ?>">
			<input type="hidden" name="pre-total" id="pre-total" value="0">
			<input type="hidden" id="pre-items" value="0">
			<input type="hidden" name="formulario" value="1">
			<!--<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>-->
			<a id="generarpdf" class="boton boton-primario">Guardar y generar PDF</a>
		
	</div>
</form>

<script type="text/javascript" src="js/label.js"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
validar('#formPresupesto');

$('#Pre-diente').select2({
	placeholder: '-- Seleccionar --',
	templateResult: formatState,
	ajax: {
		url: 'json-diente.php',
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
function formatState (state) {
  if (!state.id) { return state.text; }
  if(state.class === null){
  	var markup = $(
	    '<div class="selectMedicamento"><span>' + state.text + '</span>' + state.convenciones + '</div>'
	  );
  } else {
  	var markup = $(
	    '<div class="selectMedicamento"><span>' + state.text + '</span><i>' + state.class + '</i></div>'
	  	);
  }
  
  return markup;
};



	// FIRMA PACIENTE
	var signaturePad_presupuesto_paciente = new SignaturePad(document.querySelector('#signature_pad_presupuesto_paciente'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_presupuesto_paciente', function(){
		signaturePad_presupuesto_paciente.clear();
		$('#firma_presupuesto_paciente').val(null);
	});

	$(document).on('mouseup', '#signature_pad_presupuesto_paciente', function(){
		$('#firma_presupuesto_paciente').val( document.querySelector('#signature_pad_presupuesto_paciente').toDataURL() );
	});

	// FIRMA USUARIO
	var signaturePad_presupuesto_usuario = new SignaturePad(document.querySelector('#signature_pad_presupuesto_usuario'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_presupuesto_usuario', function(){
		signaturePad_presupuesto_usuario.clear();
		$('#firma_presupuesto_usuario').val(null);
	});

	$(document).on('mouseup', '#signature_pad_presupuesto_usuario', function(){
		$('#firma_presupuesto_usuario').val( document.querySelector('#signature_pad_presupuesto_usuario').toDataURL() );
	});

/*
		$(document).on('click', '#agregarTratamiento', function(){  
			var consultoriosPreFase = $("#Pre-fase").val();
			var consultoriosPreTratamiento = $("#Pre-tratamiento").val();
			//var consultoriosPreCantidad = $("#Pre-cantidad").val();
			//var consultoriosPreDescuento = $("#Pre-descuento").val();
			var consultoriosPreDiente = $("#Pre-diente").val();
		    if(consultoriosPreFase!='' 
		    	&& consultoriosPreTratamiento!="")
		    {   
		    	$.ajax({
		        	url:"presupuestoagregar.php",
			        method:"POST",
		            data:{preFase:consultoriosPreFase,
		            		preTratamiento:consultoriosPreTratamiento,
		            	//	preCantidad:consultoriosPreCantidad,
		            	//	preDescuento:consultoriosPreDescuento,
		            		preDiente:consultoriosPreDiente,
		            		presupuestoID:<?php echo $presupuestoID ?>}, 
			        success:function(data){  
						$('#listFases').html(data); 
					}
			    });  
			}         
		});
*/
		$(document).on('click', '#eliminarItemPresupuesto', function(){
			var consultorioId = $(this).attr("data-id");
			var consultorioPresupuesto = <?= $presupuestoID ?>;
			if(consultorioPresupuesto > 0){
				$.ajax({
		        	url:"presupuesto-item-eliminar.php",
			        method:"POST",
		            data:{id:consultorioId,presupuesto:consultorioPresupuesto}, 
			        success:function(data){  
						$('#listFases').html(data); 
					}
			    });
			}
		});


		$(document).on('click', '#generarpdf', function(){
		var cantidadItems = $('#pre-items');
		var formPresupesto = new FormData($("#formPresupesto")[0]);
		if(cantidadItems.val()>0){
			
			   	$.ajax({
		            type: 'POST',
		            url:"presupuesto-guardar.php",
		            data: formPresupesto,
					contentType: false,
					processData: false, 
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#msj-presupuesto').html(data);  
		                //$('#consultoriosDetails').html(data);  
						//$('#consultoriosModal').modal('show'); 
		            }
		        })        
		        return false;
		}
		});

	var subtotalPresupuestoJS = 0.00;
	var descuentoConvenio = <?= $descuentoConvenio['cnv_descuento'] ?>;
	var totalPresupuestoJS = 0.00;

	$(document).on('click', '.checkTratamiento', function(){

		subtotalPresupuestoJS = 0.00;
		
		var tratamientosCheck = document.querySelectorAll(".checkTratamiento:checked");

		$('#pre-items').val( tratamientosCheck.length );

		if( tratamientosCheck.length > 0 ){

			for (var i = tratamientosCheck.length - 1; i >= 0; i--) {

					subtotalPresupuestoJS += parseFloat(tratamientosCheck[i].getAttribute("data-precio"));
				
			}
		} else { subtotalPresupuestoJS = 0.00; }

		$('#subTotalPresupuesto').html( subtotalPresupuestoJS ).number( true, 0 );

		totalPresupuestoJS = subtotalPresupuestoJS - ( ( subtotalPresupuestoJS * descuentoConvenio ) / 100 );

		$('#totalPresupuesto').html( totalPresupuestoJS ).number( true, 0 );
		$('#pre-total').val( totalPresupuestoJS );
		
	});

	$('#convenioPresupuesto').html( descuentoConvenio + '%' );

	


</script>