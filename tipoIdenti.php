<?php include'config.php'; $id = $_POST['id'];
$tipoIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$id'");
$tipoIdentiRow = $tipoIdentiSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Tipo de Identificación: '.$tipoIdentiRow['ti_nombre']; }else{echo'Nuevo Tipo de Identificación';} ?></h4>
</div>
<form class="form" method="post" action="tipoIdenti-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $tipoIdentiRow['ti_nombre'] ?>" required>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>