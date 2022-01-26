<?php include'config.php';

$ordenID = $_POST['orden'];

$ordenRow = $con->query("SELECT pr_nombre, ore_numeroOrden FROM ordenesentrada AS ore INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor WHERE IDOrdenEntrada = '$ordenID'")->fetch_assoc();

?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Proveedor: <?= $ordenRow['pr_nombre'] ?> | # Orden: <?= $ordenRow['ore_numeroOrden'] ?></h4>
</div>
<form class="form" method="post" action="material-entrada-guardar.php" id="formEntradaMaterial">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">
				<select name="sucursal" id="sucursal" class="formulario__modal__input" data-label="Sucursal" required>
					<option selected hidden value="">-- Seleccionar --</option>
					<?php
						$sucursalSql = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE sc_idClinica = '$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");
		            	while($sucursalRow = $sucursalSql->fetch_assoc()){	            	
		            		echo "<option value=".$sucursalRow['IDSucursal'].">".$sucursalRow['sc_nombre']."</option>";
						}
		            ?>
	            </select>
	            <span></span>
				<select name="material" id="material" class="formulario__modal__input" data-label="Item" required>
					<option selected hidden value="">-- Seleccionar --</option>
					<?php
						$materialSql = $con->query("SELECT IDMaterial, mt_codigo, mt_nombre, mt_vencimiento FROM materiales WHERE mt_idClinica = '$sessionClinica' AND mt_estado='1' ORDER BY mt_nombre");
		            	while($materialRow = $materialSql->fetch_assoc()){            	
		            		echo "<option data-vence=".$materialRow['mt_vencimiento']." value=".$materialRow['IDMaterial'].">".$materialRow['mt_codigo'].' | '.$materialRow['mt_nombre']."</option>";
						}
		            ?>
	            </select>
			</div>

			<div class="container3PartForm">
				<input type="text" name="lote" class="formulario__modal__input" data-label="Lote" required>
				<span></span>
				<input type="text" name="invima" class="formulario__modal__input" data-label="Registro invima">
			</div>

			<div class="container3PartForm">
				<input type="number" name="cantidad" min="1" class="formulario__modal__input" data-label="Cantidad" required>
				<span></span>
				<div id="data-vence">
					<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" min="<?php echo date('Y-m-d') ?>" name="vencimiento" id="vencimiento" class="formulario__modal__input" data-label="Fecha de Vencimiento">
				</div>
			</div>
			
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="orden" value="<?php echo $ordenID ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>

	<script type="text/javascript">

		$('#data-vence').hide();

		$( "#material" ).change(function() {
			$('#material option:selected').each(function() {

				if( $( this ).attr('data-vence') == 1 ){
					$('#data-vence').show();
					$('#vencimiento').attr('required', 'required');
				} else {
					$('#vencimiento').val('');
					$('#vencimiento').removeAttr('required')
					$('#data-vence').hide();
				}

			});
		});

		$('#formEntradaMaterial').submit(function() {	
			$.ajax({
			    type: 'POST',
			    url: $(this).attr('action'),
			    data: $(this).serialize(),
				// Mostramos un mensaje con la respuesta de PHP
			    success: function(data) {
			        $('#showResultsInventarioOrden').html(data);
			        $("#consultoriosModal").modal('hide');
			    }
			})        
			return false;
		});
	</script>
