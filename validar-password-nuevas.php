<?php include'config-lobby.php'; ?>

<form class="form" id="formNewPassword" action="validar-password-guardar.php" method="post">
	<input type="password" name="newPassword" id="newPassword" placeholder="Nueva Contraseña" autocomplete="off" required>
	<input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="Confirmar Contraseña" autocomplete="off" required>
	<button onclick="validacion()" class="btn-lobby btn-primary" id="password-nuevas">Siguiente</button>	
</form>

<script src="js/jquery-2-2-0.min.js"></script>
<script type="text/javascript">
	$('#newPassword').focus();

		$('#formNewPassword').submit(function() {
		

  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#msj-login').html(data);
	            }
	        })        
	        return false;
	    
    	});

</script>