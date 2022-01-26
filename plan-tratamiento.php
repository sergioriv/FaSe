<?php include'config.php';

$pacienteID = $_POST['pacienteID'];
$ver = $_POST['ver'];

$pacienteRow = $con->query("SELECT pc_nombres, IDPaciente FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();
if($ver==1){

	$planID = $_POST['id'];
} else {

	$_SESSION['consultorioTmpOdontogramaID'] = $_POST['odontogramaID'];

	$planTratamientoConsecutivo = $con->query("SELECT MAX(plt_consecutivo)+1 AS consecutivo FROM plantratamientos WHERE plt_idClinica='$sessionClinica' AND plt_estado=1")->fetch_assoc();

	$plantratamiento = $con->query("INSERT INTO plantratamientos SET plt_idClinica='$sessionClinica', plt_idUsuario='$sessionIDUsuario', plt_idPaciente='$pacienteID', plt_idOdontograma='$_POST[odontogramaID]', plt_consecutivo='$planTratamientoConsecutivo[consecutivo]', plt_estado='0', plt_fechaCreacion='$fechaHoy'");

	$planID = $con->insert_id;
}

$planTratamientoSql = $con->query("SELECT * FROM plantratamientos WHERE IDPlanTratamiento='$planID'")->fetch_assoc();

$_SESSION['consultorioTmpPlanID'] = $planID;


$dientesOdontograma = $con->query("SELECT pod_dientes FROM pacienteodontograma WHERE IDOdontograma = '$_POST[odontogramaID]'")->fetch_assoc();
$dientesOdonto = array_unique(explode(",", $dientesOdontograma['pod_dientes']));
sort($dientesOdonto);

?>
<style type="text/css">
	.select2-search{ display: none !important; }
</style>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Plan de tratamiento: <?php echo $pacienteRow['pc_nombres']; ?></h4>
</div>

<?php if($ver==0){ ?>
<form class="form" id="formPlanTratamiento" method="post">
<?php } ?>
	<div class="modal-body">
		<div id="msj-plan-tratamiento"></div>
<?php if($ver==0){ ?>
		<div class="container7PartFormInput contRips">
			<select id="plan-fase" class="formulario__modal__input" data-label="Fase" required>
				<option value="" selected hidden>-- Seleccionar --</option>
				<?php $fasesSelSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica) ORDER BY IDFase");
					while($fasesSelRow = $fasesSelSql->fetch_assoc()){
						echo "<option value=".$fasesSelRow['IDFase'].">".$fasesSelRow['fs_nombre']."</option>";
					}
				?>
			</select>
			<span></span>
			<select id="plan-tratamiento" class="formulario__modal__input" data-label="Tratamiento" required>
				<option value="" selected hidden>-- Seleccionar fase --</option>
				
			</select>
			<span></span>
			<select id="plan-diente" class="formulario__modal__input" data-label="Diente">
				<?php foreach ($dientesOdonto as $key => $value) {
					echo "<option value=".$value.">".$value."</option>";
				}
				?>
			</select>
			<span></span>
			<input type="hidden" id="plan-combo" value="0">
			<input type="hidden" id="plan-tratamiento-precio" value="0">
			<a id="agregarTratamientoPlan" class="boton boton-primario">Agregar</a>
		</div>

<?php } ?>
							<div id="listFasesPlan">
								<?php $fasesSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica)");
								while($fasesRow = $fasesSql->fetch_assoc()){
									$tratamientosFase = $con->query("SELECT * FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE tr_idFase='$fasesRow[IDFase]' AND pltt_idPlan='$planID'")->num_rows;
									if($tratamientosFase>0){
								?>
									<div class="titulo tituloSecundario top">Fase <?= $fasesRow['fs_nombre'] ?></div>
					            	<table class="tableList tableListAuto tableListTop">
					            		<thead>
									        <tr>
									          <th>Tratamiento</th>
									          <th class="columnaCorta">Diente</th>
<?php if($ver==0){ ?>
									          <th>&nbsp</th>
<?php } ?>
									        </tr>
									     </thead>
					            		<tbody>
					            	<?php $tratamientosSql = $con->query("SELECT IDPlanTrataTrata, pltt_diente, pltt_combo, tr_nombre FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE pltt_idPlan = '$planID' AND tr_idFase = '$fasesRow[IDFase]' ORDER BY pltt_diente ASC, tr_nombre ASC");
					            		while($tratamientosRow = $tratamientosSql->fetch_assoc()){
					            			
					            			$comboTratamiento = '';	
					            			if($tratamientosRow['pltt_combo']>0){

					            				$comboTratamientoQuery = $con->query("SELECT tr_nombre FROM tratamientos WHERE IDTratamiento = '$tratamientosRow[pltt_combo]'")->fetch_assoc();
					            				$comboTratamiento = '<i>'.$comboTratamientoQuery['tr_nombre'].'</i> | '; 
					            			}
					            	?>
					            			<tr>
						            			<td><?php echo $comboTratamiento.$tratamientosRow['tr_nombre']?></td>
						            			<td align="center"><?php echo $tratamientosRow['pltt_diente'] ?></td>
<?php if($ver==0){ ?>
						            			<td class="tableOption">
						            				<a title="Eliminar" id="eliminarItemPlan" class="eliminar" data-id="<?php echo $tratamientosRow['IDPlanTrataTrata'] ?>"><?php echo $iconoEliminar ?></a>
						            			</td>
<?php } ?>
						            		</tr>
						            <?php } ?>
					            		</tbody>
					            	</table>				            	
					            <?php }
					        		}
					        	?>
					        </div>
		
		<p>&nbsp</p>

		<div class="container1Part">
			<?php if($ver==1){ echo $planTratamientoSql['plt_comentario']; } else { ?>
				<textarea name="comentario_plan" class="formulario__modal__input" data-label="Comentario" rows="3"></textarea>
			<?php } ?>
		</div>
		
		<div class="containerFirmas">
			<div class="content_signature">
				<?php if($ver==1){ ?>

					<?php if(!empty($planTratamientoSql['plt_firmaPaciente'])){ ?>
						<img src="<?php echo $planTratamientoSql['plt_firmaPaciente'] ?>">
					<?php } ?>

				    <div class="option_signature_pad">
						Firma Paciente
					</div>

				<?php } else { ?>

					<canvas id="signature_pad_plan_paciente" class="signature_pad" width=400 height=200></canvas>

					<div class="option_signature_pad">
						Firma Paciente
						<span id="clear_signature_plan_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
					</div>

					<input type="hidden" name="firma_plan_paciente" id="firma_plan_paciente">

				<?php } ?>
				
			</div>

			<div class="content_signature">
				<?php if($ver==1){ ?>

					<?php if(!empty($planTratamientoSql['plt_firmaUsuario'])){ ?>
						<img src="<?php echo $planTratamientoSql['plt_firmaUsuario'] ?>">
					<?php } ?>

				    <div class="option_signature_pad">
						Firma Usuario
					</div>

				<?php } else { ?>

					<canvas id="signature_pad_plan_usuario" class="signature_pad" width=400 height=200></canvas>

					<div class="option_signature_pad">
						Firma Usuario
						<span id="clear_signature_plan_usuario" title="Limpiar"><i class="fa fa-times"></i></span>
					</div>

					<input type="hidden" name="firma_plan_usuario" id="firma_plan_usuario">

				<?php } ?>
			</div>
		</div>


	</div>
	<div class="modal-footer">
<?php if($ver==0){ ?>		
			<input type="hidden" name="id" value="<?php echo $pacienteID ?>">
			<input type="hidden" id="plan-items" value="0">
			<input type="hidden" name="formulario" value="1">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<a id="guadarPlan" class="boton boton-primario">Guardar</a>
<?php } else { ?>
			<a class="boton boton-secundario" data-dismiss="modal">Cerrar</a>
<?php } ?>
	</div>

<?php if($ver==0){ ?>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
validar('#formPlanTratamiento');

// $('#plan-diente').select2({
// 	placeholder: '-- Seleccionar --',
// 	templateResult: formatState,
// 	ajax: {
// 		url: 'json-diente.php',
// 		dataType: 'json',
// 		delay: 250,
// 		processResults: function (data){
// 			return {
// 				results: data.items,
// 				  "pagination": {
// 				    "more": data.pag
// 				  }
// 			}
// 		},
// 		cache: true
// 	}
// });
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
	var signaturePad_plan_paciente = new SignaturePad(document.querySelector('#signature_pad_plan_paciente'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_plan_paciente', function(){
		signaturePad_plan_paciente.clear();
		$('#firma_plan_paciente').val(null);
	});

	$(document).on('mouseup', '#signature_pad_plan_paciente', function(){
		$('#firma_plan_paciente').val( document.querySelector('#signature_pad_plan_paciente').toDataURL() );
	});

	// FIRMA USUARIO
	var signaturePad_plan_usuario = new SignaturePad(document.querySelector('#signature_pad_plan_usuario'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_plan_usuario', function(){
		signaturePad_plan_usuario.clear();
		$('#firma_plan_usuario').val(null);
	});

	$(document).on('mouseup', '#signature_pad_plan_usuario', function(){
		$('#firma_plan_usuario').val( document.querySelector('#signature_pad_plan_usuario').toDataURL() );
	});




	$(document).on('click', '#guadarPlan', function(){
		var cantidadItems = $('#plan-items');
		var formPlanTratamiento = new FormData($("#formPlanTratamiento")[0]);
		if(cantidadItems.val()>0){
			
			   	$.ajax({
		            type: 'POST',
		            url:"plan-tratamiento-guardar.php",
		            data: formPlanTratamiento,
					contentType: false,
					processData: false, 
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#showResultsPcPlanTratamiento').html(data);  
		                //$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('hide'); 
		            }
		        })        
		        return false;
		}
	});


	$( "#plan-fase" ).change(function() {
		var valor = $(this).val();

		if(valor==1000){
			
			$( "#plan-tratamiento" ).change(function() {
				$( "#plan-tratamiento option:selected" ).each(function() {

			    	$('#plan-combo').val( $( this ).attr('data-combo') );
			    });
			});			
		} else {
			$('#plan-combo').val( '0' );
		}

		$.ajax({
			url: 'extras/planTratamientos.php',
			type: 'POST',
			data: {faseID:valor},
			success:function(data){  
				$('#plan-tratamiento').html(data);
			}
		})
		
	});

	$( "#plan-tratamiento" ).change(function() {
		$( "#plan-tratamiento option:selected" ).each(function() {

			$('#plan-tratamiento-precio').val( $( this ).attr('data-precio') );
		});
	});

</script>
<?php } ?>