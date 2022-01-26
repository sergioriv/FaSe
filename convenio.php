<?php include'config.php'; $id = $_POST['id'];
$convenioRow = $con->query("SELECT * FROM convenios WHERE IDConvenio = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Convenio: '.$convenioRow['cnv_nombre']; }else{echo'Nuevo convenio';} ?></h4>
</div>
<form class="form" id="formConvenio" method="post" action="convenio-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">
				<input type="text" name="nombre" value="<?php echo $convenioRow['cnv_nombre'] ?>" class="formulario__modal__input" data-label="Nombre">
				<span></span>
				<input type="number" name="valor" value="<?php echo $convenioRow['cnv_descuento'] ?>" class="formulario__modal__input" data-label="Procentaje" min="0" max="100">
			</div>
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
validar('#formConvenio');
</script>