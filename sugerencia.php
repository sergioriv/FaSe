<?php include'config.php'; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Enviar Sugerencia</h4>
</div>
<form class="form" method="post" action="sugerencia-enviar.php" id="sugerencia">
	<div class="modal-body">
		<div id="msj-sugerencia"></div>
		<div class="divForm">
			<input type="text" name="tema" class="formulario__modal__input" data-label="Tema">
			<textarea rows="5" name="descripcion" class="formulario__modal__input" data-label="Descripción"></textarea>
		</div>
	</div>
	   
	<div class="modal-footer">  

			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Enviar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
	$('#sugerencia').submit(function() {

		
  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                setTimeout("location.href = '<?php echo $_SESSION[concultoriosAntes] ?>'");
	            }
	        })        
	        return false;
    	
    }); 
</script>