<?php include'config.php'; $id = $_POST['id'];
$tratamientoRow = $con->query("SELECT * FROM tratamientos WHERE IDTratamiento = '$id'")->fetch_assoc();

$selectCupsRow = $con->query("SELECT * FROM cups WHERE IDCups = '$tratamientoRow[tr_idCups]'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Tratamiento: '.$tratamientoRow['tr_nombre']; }else{echo'Nuevo Tratamiento';} ?></h4>
</div>
<form class="form" id="formTratamiento" method="post" action="tratamiento-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			
			<div class="container1Part contRips">
				<select name="cups" id="cups" class="formulario__modal__input" data-label="CUPS">
				    <?php
						if($tratamientoRow['tr_idCups']!=0){
				           	echo "<option value=".$selectCupsRow['IDCups']." selected>".$selectCupsRow['cup_codigo'].' | '.$selectCupsRow['cup_nombre']."</option>";
				        }
				    ?>
			    </select>
		    </div>
		    <select name="fase" id="fase" class="formulario__modal__input" data-label="Fase" required>
		    	<option value="" selected hidden>-- Seleccionar --</option>
				<?php $fasesSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica) AND NOT IDFase = '1000'");
					while($fasesRow = $fasesSql->fetch_assoc()){
						$faseSelected = '';
			            if($fasesRow['IDFase']==$tratamientoRow['tr_idFase']){ $faseSelected = "selected"; }
						echo "<option value=".$fasesRow['IDFase']." ".$faseSelected.">".$fasesRow['fs_nombre']."</option>";
					}
				?>
		    </select>
			<input type="text" name="nombre" value="<?php echo $tratamientoRow['tr_nombre'] ?>" class="formulario__modal__input" data-label="Nombre">
			<input type="text" name="precio" id="precio" maxlength="16" value="<?php echo $tratamientoRow['tr_precio'] ?>" class="formulario__modal__input" data-label="Precio">
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
validar('#formTratamiento');
$('#precio').number( true, 0 );

$('#cups').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-cups.php',
		dataType: 'json',
		delay: 250,
		processResults: function (data){
			return {
				results: data.items,
				  "pagination": {
				    "more": data.pag
				  }
			};
		},
		cache: true
	}
});
</script>