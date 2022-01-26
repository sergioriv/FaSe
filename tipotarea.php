<?php include'config.php';

$id = $_POST['id'];
$tipoTareaRow = $con->query("SELECT * FROM tipotarea WHERE IDTipoTarea = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Tipo de tarea: '.$tipoTareaRow['tpt_nombre']; 
								} else { echo'Nuevo tipo'; } ?></h4>
</div>

<form class="form" id="formTipoTarea" method="post" action="tipotarea-guardar.php">

	<div class="modal-body">
		<div class="divForm">

			<input type="text" name="nombre" value="<?php echo $tipoTareaRow['tpt_nombre'] ?>" class="formulario__modal__input" data-label="Nombre">
							
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
validar('#formTipoTarea');
</script>