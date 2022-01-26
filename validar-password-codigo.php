<?php include'config-lobby.php'; ?>
<?php if($_SESSION['consultoriosLobbyMsj']==2){ ?>
	<div id="msj-login">
		<input type="radio" id="alertExito">
		<label class="alerta exito" for="alertExito">			
			<div>Se ha enviado un código de verificación al correo <b><?php echo $_SESSION['consultoriosLobbyCorreo']; ?></b>.
			<br><br>
			<i>Recuerde revisar el Correo no deseado.</i></div>
		</label>	
	</div>
	<form class="form" id="formCodigo" action="validar-cod-guardar.php" method="post">
		<input type="text" name="codigo" id="codigo" placeholder="Código de verificación" maxlength="6" required>
		<button onclick="validacion()" class="btn-lobby btn-primary" id="registrarse">Siguiente</button>	
	</form>

	<script src="js/jquery-2-2-0.min.js"></script>
	<script type="text/javascript">

		document.getElementById("codigo").focus();
		$('.titulo').html('Recuperar contraseña');
		$('#formCodigo').submit(function() {
			
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
<?php } else { ?>
	<div id="msj-login">
		<input type="radio" id="alertError">
		<label class="alerta error" for="alertError">
			
			<div>Error, intentelo nuevamente.<br>Si el error persiste, informanos al correo <b>info@mantiztechnology.com</b></div>

		</label>
	</div>
	<script type="text/javascript">
		setTimeout("location.href = './'",10000);
	</script>
<?php } ?>