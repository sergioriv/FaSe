<?php include'config.php'; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Iconos</h4>
</div>
<form class="form" method="post" action="iconos-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<?php $categoriasSql = $con->query("SELECT * FROM categoriasiconos ORDER BY IDCatIcono");
				while($categoriasRow = $categoriasSql->fetch_assoc()){
			?>			
			<div class="contenedorRadio radioIconos">
				<label><?php echo $categoriasRow['ci_nombre'] ?></label>
				<?php $selecIcono='';
					$iconosSql = $con->query("SELECT * FROM iconos WHERE ic_idCategoria = '$categoriasRow[IDCatIcono]'");
					while($iconosRow = $iconosSql->fetch_assoc()){
						if($iconosRow['IDIcono']==$iconoNuevoRow['IDIcono'] || $iconosRow['IDIcono']==$iconoEditarRow['IDIcono'] || $iconosRow['IDIcono']==$iconoEliminarRow['IDIcono']){ $selecIcono='checked'; } else { $selecIcono=''; }
				?>
				<input type="radio" id="icono<?php echo $iconosRow['IDIcono'] ?>" name="<?php echo $categoriasRow['ci_nombre'] ?>" value='<?php echo $iconosRow["IDIcono"] ?>' <?php echo $selecIcono ?>>
				<label for="icono<?php echo $iconosRow['IDIcono'] ?>"><?php echo $iconosRow['ic_icono'] ?></label>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>