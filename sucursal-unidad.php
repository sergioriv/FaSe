<?php include'config.php'; $id = $_POST['id']; $sucursalID = $_POST['sucursalID'];
$sucursal = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal = $sucursalID")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Sucursal <?php echo $sucursal['sc_nombre'] ?> | Nueva unidad</h4>
</div>
<form class="form" id="formUnidad" method="post" action="sucursal-unidad-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<input type="text" name="nombre" class="formulario__modal__input" data-label="Nombre" required>
		</div>
	</div>
	   
	<div class="modal-footer">
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<input type="hidden" name="sucursalID" value="<?php echo $sucursalID ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
	$('#formUnidad').submit(function() { 
		    	$.ajax({
		    		type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(), 
		            success:function(data){  
						$('#showResultsScUnidades').html(data);
						$('#consultoriosModal').modal('hide'); 
					}
		    	})
		    	return false;
		});
</script>