<?php include'config.php';
$horarioRow = $con->query("SELECT * FROM doctores, doctoreshorarios WHERE doctoreshorarios.dch_idDoctor = doctores.IDDoctor AND doctoreshorarios.IDDocHorario = '$_POST[id]'")->fetch_assoc();
?>
<div class="modal-body">¿Está seguro de eliminar el Horario personalizado de fecha <b><?php echo $horarioRow['dch_fecha']?></b> con horario <b><?php echo $horarioRow['dch_atencionDe'].' / '.$horarioRow['dch_atencionHasta'] ?></b> del Doctor <b><?php echo $horarioRow['dc_nombres'] ?></b>?</div>
   
<div class="modal-footer">  
	<form method="post" action="doctor-horario-eliminar-guardar.php" id="formEliminarHorario" class="form">
		<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
		<input type="hidden" name="doctorID" value="<?php echo $horarioRow['IDDoctor'] ?>">
		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<button class="boton boton-primario">Eliminar</button>
	</form> 
</div>

	<script type="text/javascript">
		$('#formEliminarHorario').submit(function() {
			
	  			// Enviamos el formulario usando AJAX
		        $.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#showHorariosPersonalizados').html(data);
			            $("#consultoriosModal").modal('hide');
		            }
		        })        
		        return false;
	    	
	    }); 
	</script>