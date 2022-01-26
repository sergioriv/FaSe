<?php include'config.php'; $id = $_POST['id'];
$comboRow = $con->query("SELECT * FROM combos WHERE IDCombo = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Agregar tratamiento: <?php echo $comboRow['cb_nombre']; ?></h4>
</div>
<form class="form" method="post" action="combo-tratamiento-guardar.php" id="formComboTratamiento">
	<div class="modal-body">
		<div class="divForm">
			<select name="tratamiento" class="formulario__modal__input" data-label="Tratamiento" required>
				<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
				<?php
					$tratamientosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica = '$sessionClinica' AND tr_estado='1' ORDER BY tr_nombre");
				    while($tratamientosRow = $tratamientosSql->fetch_assoc()){
				        	echo "<option value=".$tratamientosRow['IDTratamiento'].">".$tratamientosRow['tr_nombre'].' - '.'$'.number_format($tratamientosRow['tr_precio'], 2, ",", ".")."</option>";	
				    }
	            ?>
            </select>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
		$('#formComboTratamiento').submit(function() {	
			$.ajax({
			    type: 'POST',
			    url: $(this).attr('action'),
			    data: $(this).serialize(),
				// Mostramos un mensaje con la respuesta de PHP
			    success: function(data) {
			        $('#showResultsComboTratamientos').html(data);
			        $("#consultoriosModal").modal('hide');
			    }
			})        
			return false;
		});
</script>
