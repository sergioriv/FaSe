<?php include'config.php'; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Cambiar Contraseña</h4>
</div>
<form class="form" method="post" id="formCambioPassword" action="password-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<div class="container1Part">
				<input type="password" name="passwordActual" id="passwordActual" autocomplete="off" class="formulario__modal__input" data-label="Contraseña Actual">
			</div>
			<div class="container1Part">
				<input type="password" name="newPassword" id="newPassword" autocomplete="off" class="formulario__modal__input" data-label="Nueva Contraseña">
			</div>
			<div class="container1Part">
				<input type="password" name="ConfirmPassword" id="ConfirmPassword" autocomplete="off" class="formulario__modal__input" data-label="Confirmar Contraseña">
			</div>
			<div id="validarUsuario"></div>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
validar('#formCambioPassword');
	$('#formCambioPassword').submit(function() {

  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#validarUsuario').html(data);
	            }
	        })        
	        return false;
    	
    });
</script>