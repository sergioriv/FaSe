<?php include'config.php';

$id = $_POST['id'];
$faseRow = $con->query("SELECT * FROM fases WHERE IDFase = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Fase: '.$faseRow['fs_nombre']; 
								} else { echo'Nueva Fase'; } ?></h4>
</div>

<form class="form" id="formFase" method="post" action="fase-guardar.php">

	<div class="modal-body">
		<div class="divForm">

			<input type="text" name="nombre" value="<?php echo $faseRow['fs_nombre'] ?>" class="formulario__modal__input" data-label="Nombre">
							
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
validar('#formFase');
</script>