<?php include'config.php';

$planID = $_POST['planID'];
$pacienteID = $_POST['pacienteID'];
?>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Seleccionar convenio</h4>
</div>
<form class="form" method="post" action="presupuesto.php" id="formConvenio">
	<div class="modal-body">
		<div class="divForm">
			<div class="container1PartForm" id="convenioSel">
				<select name="convenio" id="convenio" class="formulario__modal__input" data-label="Convenio" required>
					<?php
						$convenios = $con->query("SELECT * FROM convenios WHERE cnv_idClinica IN('$sessionClinica',0) AND cnv_estado=1 ORDER BY cnv_idClinica ASC, cnv_nombre ASC");
		            	while($conveniosRow = $convenios->fetch_assoc()){
		            		$selected = '';
		            		if ($conveniosRow['IDConvenio']==1000) { $selected = 'selected'; }

		            		echo "<option value=".$conveniosRow['IDConvenio']." ".$selected.">".$conveniosRow['cnv_nombre'].' - '.$conveniosRow['cnv_descuento']."%</option>";		
						}
		            ?>
		            <option value="nuevo">Crear nuevo*</option>
	            </select>
			</div>
			<div class="container3PartForm" id="convenioNuevo">
				<input type="text" name="convenio-nombre" id="convenio-nombre" class="formulario__modal__input" data-label="Nombre">
				<span></span>
				<input type="number" name="convenio-valor" id="convenio-valor" class="formulario__modal__input" data-label="Procentaje" min="0" max="100">
			</div>
		</div>
	</div>
	   
	<div class="modal-footer">
			
			<input type="hidden" name="convenioValorNuevo" id="convenioValorNuevo" value="0">
			<input type="hidden" name="planID" value="<?php echo $planID ?>">
			<input type="hidden" name="pacienteID" value="<?php echo $pacienteID ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Siguiente</button>

	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
validar('#formConvenio');

$('#convenioNuevo').hide();

	$( "#convenio" ).change(function() {
		var valor = $(this).val();

		if(valor=='nuevo'){
			$('#convenioSel').hide();
			$('#convenioNuevo').show();
			$('#convenioValorNuevo').val(1);
			$('#convenio-nombre').attr('required','required').focus();
			$('#convenio-valor').attr('required','required');
		} else {			
			$('#convenioSel').show();
			$('#convenioNuevo').hide();
			$('#convenioValorNuevo').val(0);
			$('#convenio-nombre').removeAttr('required');
			$('#convenio-valor').removeAttr('required');
		}
	});

	$('#formConvenio').submit(function() {
		var convenio = $('#convenio');

		var nuevoConvenio = $('#convenioValorNuevo');
		var convenioNombre = $('#convenio-nombre');
		var convenioValor = $('#convenio-valor');
/*		
		$( "#convenio option:selected" ).each(function() {
	      convenioDescuento.val( $( this ).attr('data-valor') );
	    });
*/
		if(nuevoConvenio.val() == 1){

			if (convenioNombre.val() =="" || convenioValor.val() =="" || ( convenioValor.val() < 0 || convenioValor.val() > 100 ) ){
				return false;
			}
		}
			if(convenio.val()!=""){

			   	$.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#consultoriosDetails').html(data);
		                //$("#consultoriosModal").modal('hide');
		            }
		        })
		        return false;
			}
		
		
	});

</script>