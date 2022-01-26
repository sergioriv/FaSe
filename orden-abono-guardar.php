<?php include'config.php';

$egresoID = $_POST['egresoID'];

$ordenID = $_POST['ordenID'];
$formaPago = $_POST['formaPago'];
$abono = $_POST['abono'];
$banco = $_POST['banco'];
$cheque = $_POST['cheque'];
$comentario = nl2br( trim( $_POST['comentario'] ) );

$ordenEntrada = $con->query("SELECT ore_idProveedor, ore_facturaValor FROM ordenesentrada WHERE IDOrdenEntrada = '$ordenID'")->fetch_assoc();

$proveedorID = $ordenEntrada['ore_idProveedor'];

if(!empty($egresoID)){

	if( $abono>0 ){

		$query = $con->query("UPDATE ordenesabonos SET pra_abono='$abono', pra_idFormaPago='$formaPago', pra_idBanco='$banco', pra_cheque='$cheque', pra_comentario='$comentario' WHERE IDOrdenAbono = '$egresoID'");

	}

	if($query){ $_SESSION['consultoriosExito']=3; }
	else { $_SESSION['consultoriosExito']=1; }

?>
		<script type="text/javascript">
			setTimeout("location.href = 'flujo-caja'");
		</script>
<?php	

} else {



if($abono>0){

	$consecutivoSql = $con->query("SELECT MAX(pra_consecutivo) as consecutivo FROM ordenesabonos WHERE pra_idClinica = '$sessionClinica'")->fetch_assoc();

	$consecutivo = $consecutivoSql['consecutivo']+1;

	$query = $con->query("INSERT INTO ordenesabonos SET pra_idClinica='$sessionClinica', pra_idUsuario='$sessionIDUsuario', pra_idOrden='$ordenID', pra_consecutivo='$consecutivo', pra_abono='$abono', pra_idFormaPago='$formaPago', pra_idBanco='$banco', pra_cheque='$cheque', pra_comentario='$comentario', pra_estado=1, pra_fechaCreacion='$fechaHoy'");



	$cuentaOrden = 0;

		$abonosOrden = $con->query("SELECT SUM(pra_abono) as abonos FROM ordenesabonos WHERE pra_idOrden = '$ordenID'")->fetch_assoc();
		$cuentaOrden = $ordenEntrada['ore_facturaValor'] - $abonosOrden['abonos'];

	if($cuentaOrden <= 0){
		$pagada = 1;
	} else { $pagada = 0; }

	$con->query("UPDATE ordenesentrada SET ore_pagada = '$pagada' WHERE IDOrdenEntrada = '$ordenID'");



	if($query){
?>
		<input type="radio" id="alertExito">
		<label class="alerta exito s" for="alertExito">
			<div>Abono guardado.</div>
			<div class="close">&times;</div>
		</label>
		
<?php
	} else {
?>
		<input type="radio" id="alertError">
		<label class="alerta error s" for="alertError">
			<div>Error al guardar, Intentelo nuevamente.</div>
			<div class="close">&times;</div>
		</label>
		
<?php
	}

} else {
?>
	<input type="radio" id="alertError">
	<label class="alerta error s" for="alertError">
		<div>Error al guardar, Intentelo nuevamente.</div>
		<div class="close">&times;</div>
	</label>
	
<?php
}

$cuentaProveedor = 0;
	$abonosCuenta = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos AS pra
		INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
		WHERE ore_idProveedor = '$proveedorID' AND ore_estado = 1 AND pra_estado = 1")->fetch_assoc();

	$facturaCuenta = $con->query("SELECT SUM(ore_facturaValor) AS facturas FROM ordenesentrada WHERE ore_idProveedor = '$proveedorID' AND ore_estado = 1")->fetch_assoc();

	$cuentaProveedor = $facturaCuenta['facturas'] - $abonosCuenta['abonos'];

	$cuentaPro = '$'.number_format($cuentaProveedor, 0, ".", ","); 

?>
<script type="text/javascript">
	$('#estadoCuenta').html('<?= $cuentaPro ?>');

		    $.ajax({
		        type: 'POST',
		        url: 'get/ordenesProveedorData.php',
		        data:'page=0&proveedorID='+ <?= $proveedorID; ?>,
		        success: function (html) {
		            $('#showResultsFacturaProveedor').html(html);
		        }
		    });

</script>
<?php } ?>