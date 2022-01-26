<?php include'config.php'; $id = $_POST['id'];
$especialidadSql = $con->query("SELECT * FROM especialidades WHERE IDEspecialidad = '$id'");
$especialidadRow = $especialidadSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Especialidad: '.$especialidadRow['esp_nombre']; } else {echo'Nueva Especialidad';} ?></h4>
</div>
<form class="form" id="formEspecialidad" method="post" action="especialidad-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<input type="text" name="nombre" value="<?php echo $especialidadRow['esp_nombre'] ?>" class="formulario__modal__input" data-label="Nombre" required>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script>validar('#formEspecialidad');</script>