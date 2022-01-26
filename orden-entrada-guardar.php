<?php include'config.php'; include'pagination-modal-params.php';

$proveedorID = $_POST['proveedor'];
$orden = $_POST['orden'];
$factura = $_POST['factura'];
$facturaFecha = str_replace('-', '/', $_POST['facturaFecha']);
$facturaFechaVencimiento = str_replace('-', '/', $_POST['facturaFechaVencimiento']);
$facturaValor = $_POST['facturaValor'];

$action = $_POST['action'];

if($action == 'next'){

	$query = $con->query("INSERT INTO ordenesentrada SET ore_idClinica='$sessionClinica', ore_idUsuario='$sessionIDUsuario', ore_idProveedor='$proveedorID', ore_numeroOrden='$orden', ore_numeroFactura='$factura', ore_facturaFecha='$facturaFecha', ore_facturaFechaVencimiento='$facturaFechaVencimiento', ore_facturaValor='$facturaValor', ore_estado='1', ore_fechaCreacion='$fechaHoy'");
	$ordenID = $con->insert_id;

} else if($action == 'save'){

	$ordenID = $_POST['ordenID'];
	$query = $con->query("UPDATE ordenesentrada SET ore_numeroOrden='$orden', ore_numeroFactura='$factura', ore_facturaFecha='$facturaFecha', ore_facturaFechaVencimiento='$facturaFechaVencimiento', ore_facturaValor='$facturaValor' WHERE IDOrdenEntrada = '$ordenID'");
?>
	<script type="text/javascript">
		setTimeout("location.href = '<?= $_SESSION[concultoriosAntes] ?>'",0);
	</script>
<?php
}

if($query){
?>
	<script type="text/javascript">

		   	$.ajax({
		       	url:"orden-entrada.php",
		        method:"POST",
		       	data: {id:<?= $proveedorID ?>,ordenID:<?= $ordenID ?>},
		        success:function(data){  
					$('.contenedorPrincipal').html(data);  
				}
		    });

	</script>
<?php } else {
?>
		<script type="text/javascript">
			$('#msj-factura').html('<input type="radio" id="alertError"><label class="alerta error s" for="alertError"><div>Error al guardar, Intentelo nuevamente.</div><div class="close">&times;</div></label>');
		</script>
<?php } ?>