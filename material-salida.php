<?php include'config.php'; $id = $_POST['id'];
$entradaRow = $con->query("SELECT me_cantidad, me_numeroLote FROM materialesentrada AS me INNER JOIN materiales AS mt ON me.me_idMaterial = mt.IDMaterial WHERE IDMatEntrada = '$id'")->fetch_assoc();

	$cantidadActual = 0;
	$cantidadSalidasSql = $con->query("SELECT SUM(ms_cantidad) AS cantSalida FROM materialessalida WHERE ms_idMatEntrada = '$id' ")->fetch_assoc();
	
	$cantidadActual = $entradaRow['me_cantidad'] - $cantidadSalidasSql['cantSalida'];
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Salida inventario | # lote: <?= $entradaRow['me_numeroLote'] ?></h4>
</div>
<form class="form" method="post" id="formMaterialSalida" action="material-salida-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<input type="number" name="cantidad" min="1" max="<?php echo $cantidadActual ?>" class="formulario__modal__input" data-label="Cantidad" required>		
			<textarea name="detalles" rows="3" class="formulario__modal__input" data-label="Descripción"></textarea>
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
	$('#formMaterialSalida').submit(function() {	
			$.ajax({
			    type: 'POST',
			    url: $(this).attr('action'),
			    data: $(this).serialize(),
				// Mostramos un mensaje con la respuesta de PHP
			    success: function(data) {
			        $('#showResultsSalidas').html(data);
			        $('#consultoriosModal').modal('hide'); 
			    }
			})        
			return false;
		});
</script>