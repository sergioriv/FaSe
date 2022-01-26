<?php include'config.php';
$egresoSQL = $con->query("SELECT * FROM ordenesabonos AS pra
			INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
			INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
			WHERE IDOrdenAbono = '$_POST[id]'")->fetch_assoc();
?>
<div class="modal-body">¿Está seguro de anular el Comprobante de Egreso <b>#<?php echo $egresoSQL['pra_consecutivo'] ?></b> del proveedor <b><?= $egresoSQL['pr_nombre'] ?></b>?</div>
   
<div class="modal-footer">  
	<form method="post" action="orden-abono-eliminar-guardar.php" id="anularEgreso" class="form">
		<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<button class="boton boton-primario">Anular Abono</button>
	</form> 
</div>