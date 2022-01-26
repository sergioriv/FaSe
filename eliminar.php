<div class="modal-body">¿Está seguro de eliminar la imágen?</div>
   
<div class="modal-footer">  
	<form method="post" action="eliminar-guardar.php" id="eliminarFoto" class="form">
		<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
		<input type="hidden" name="t" value="<?php echo $_POST['t'] ?>">
		<input type="hidden" name="pc" value="<?php echo $_POST['pc'] ?>">
		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<button class="boton boton-primario">Eliminar</button>
	</form> 
</div>
<script type="text/javascript">
	$('#eliminarFoto').submit(function() {

		
  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#image_gallery').html(data);
	                $("#consultoriosModal").modal('hide');
	            }
	        })        
	        return false;
    	
    }); 
</script>