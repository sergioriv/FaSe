<?php include'config-lobby.php'; ?>
<form class="form" action="iniciar-sesion.php" id="login" method="post">
	<input type="password" value="" name="password" id="loginPassword" placeholder="Contraseña" required>
	<button class="btn-lobby btn-primary" id="iniciar">Siguiente</button>
	<a id="olvido" style="cursor: pointer; font-size: 13px;">¿Olvidó su contraseña?</a>
</form>
<!--<a id="olvido">¿olvidó su contraseña?</a>-->
<script type="text/javascript">
	$('#loginPassword').focus();

	$('#login').submit(function() {
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

    $(document).on('click', '#olvido', function(){   
		$('#lobbyForm').load('validar-codigo-enviar.php');
	});

</script>