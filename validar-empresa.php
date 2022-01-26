<?php include'config-lobby.php';
$_SESSION['consultoriosValidar']=2;
?>
<form class="form" action="validar.php" id="login" method="post">
	<select name="clinica" id="clinica" required>
		<option selected hidden value="">Seleccionar Clinica</option>
		<?php
			$clinicasSql = $con->query("SELECT DISTINCT clinicas.IDClinica, clinicas.cl_nombre FROM clinicas, usuarios WHERE usuarios.us_idClinica = clinicas.IDClinica AND usuarios.us_correo = '$_SESSION[consultoriosLobbyCorreo]' AND usuarios.us_estado = '1'");
	       	while($clinicasRow = $clinicasSql->fetch_assoc()){
	       		echo "<option value=".$clinicasRow['IDClinica'].">".$clinicasRow['cl_nombre']."</option>";	
			}
	    ?>
	</select>
	<button class="btn-lobby btn-primary" id="iniciar">Siguiente</button>
</form>
<script type="text/javascript">
	$('#clinica').focus();

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
</script>