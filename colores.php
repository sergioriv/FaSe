<?php include'config.php'; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Colores</h4>
</div>
<form class="form" method="post" action="colores-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<label>Color Principal</label>
			<input type="color" name="colorPrimario" value="<?php echo $clinicaRow['cl_colorPrimario'] ?>" required>
			<label>Color Secundario</label>
			<input type="color" name="colorSecundario" value="<?php echo $clinicaRow['cl_colorSecundario'] ?>" required>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>