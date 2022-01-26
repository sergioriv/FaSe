<?php include'config.php'; $id = $_POST['id'];
$ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$id'");
$ciudadRow = $ciudadSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Ciudad: '.$ciudadRow['cd_nombre']; } else {echo'Nueva Ciudad';} ?></h4>
</div>
<form class="form" method="post" action="ciudad-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $ciudadRow['cd_nombre'] ?>" required>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>