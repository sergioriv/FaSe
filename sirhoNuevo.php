<?php include'config.php';
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Nuevo registro Sirho</h4>
</div>
<form class="form" id="formSirho" method="post" action="sirho-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			
			<div class="container3PartForm">
				<select name="sirho" class="formulario__modal__input" data-label="Sirho" required>
					<option selected hidden value="">-- Seleccionar --</option>
					<?php $categoriasSirho = $con->query("SELECT * FROM sirhocategorias");
						while($categoriasSirhoRow = $categoriasSirho->fetch_assoc()){
							echo "<optgroup label='$categoriasSirhoRow[shcg_nombre]'>";

							$sirho = $con->query("SELECT * FROM sirho WHERE sh_idCategoria = '$categoriasSirhoRow[IDSirhoCategoria]'");

							while($sirhoRow = $sirho->fetch_assoc()) {
								echo "<option value='$sirhoRow[IDSirho]'>$sirhoRow[sh_nombre]</option>";
							}
						}
					?>					
				</select>
				<span></span>
				<input type="number" name="cantidad" class="formulario__modal__input" data-label="Cantidad (Kilogramos)" maxlength="16" required>
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
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">validar('#formSirho');</script>