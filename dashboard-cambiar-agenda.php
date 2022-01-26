<?php include'config.php'; 

$fecha_inicio = $_POST['fecha_inicio']

?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Cambiar agenda de día</h4>
</div>
<form class="form" id="formCambiarAgenda" method="post" action="dashboard-cambiar-agenda-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<div id="msj-cambiar-agenda"></div>
			<div class="container1Part">
				<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" min="<?= date('Y-m-d') ?>" name="fecha_fin" data-label="Nueva fecha" class="formulario__modal__input">
			</div>
		</div>
	</div>

	<div class="modal-footer">  
			
			<input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>

</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
	$('#formCambiarAgenda').submit(function() {

		$.ajax({
		    type: 'POST',
		    url: $(this).attr('action'),
		    data: $(this).serialize(),
		    // Mostramos un mensaje con la respuesta de PHP
		    success: function(data) {
		        $('#msj-cambiar-agenda').html(data);
		    }
		})
		return false;
	});
</script>