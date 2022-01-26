<?php include'config.php'; $id = $_POST['id'];
$epsSql = $con->query("SELECT * FROM eps WHERE IDEps = '$id'");
$epsRow = $epsSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">EPS<?php if($id){ echo ': '.$epsRow['eps_nombre']; } ?></h4>
</div>
<form class="form" method="post" action="eps-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $epsRow['eps_nombre'] ?>" required>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>