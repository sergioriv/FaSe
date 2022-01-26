<?php include'config.php';
$abonoSQL = $con->query("SELECT * FROM abonos, sucursales WHERE abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.IDAbono = '$_POST[id]'")->fetch_assoc();
?>
<div class="modal-body">¿Está seguro de anular el Recibo de caja <b>#<?php echo $abonoSQL['ab_consecutivo'] ?></b> de la Sucursal <b><?php echo $abonoSQL['sc_nombre'] ?></b>?</div>
   
<div class="modal-footer">  
	<form method="post" action="paciente-abono-eliminar-guardar.php" id="anularAbono" class="form">
		<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
		<input type="hidden" name="pc" value="<?php echo $_POST['pc'] ?>">
		<input type="hidden" name="tp" value="<?php echo $_POST['tp'] ?>">
		<input type="hidden" name="sucursalID" value="<?php echo $_POST['sucursalID'] ?>">
		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<button class="boton boton-primario">Anular Abono</button>
	</form> 
</div>
<?php if($_POST['tp']!='ab'){ ?>
	<script type="text/javascript">
		$('#anularAbono').submit(function() {

			
	  			// Enviamos el formulario usando AJAX
		        $.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('.listAbonos').html(data);
			            $("#consultoriosModal").modal('hide');
		            }
		        })        
		        return false;
	    	
	    }); 
	</script>
<?php } ?>