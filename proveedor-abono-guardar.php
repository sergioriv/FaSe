<?php include'config.php'; include('pagination-modal-params.php');

$facturaID = $_POST['facturaID'];
$formaPago = $_POST['formaPago'];
$abono = $_POST['abono'];
$banco = $_POST['banco'];
$cheque = $_POST['cheque'];

$proveedor = $con->query("SELECT me_idProveedor FROM materialesentrada WHERE IDMatEntrada = '$facturaID'")->fetch_assoc();

$proveedorID = $proveedor['me_idProveedor'];

if($abono>0){

	

	$query = $con->query("INSERT INTO proveedores_abonos SET pra_idClinica='$sessionClinica', pra_idUsuario='$sessionIDUsuario', pra_idProveedor='$proveedorID', pra_idFactura='$facturaID', pra_abono='$abono', pra_idFormaPago='$formaPago', pra_idBanco='$banco', pra_cheque='$cheque', pra_estado=1, pra_fechaCreacion='$fechaHoy'");

	if($query){
?>
		<script type="text/javascript">
			$('#msj-factura').html('<input type="radio" id="alertExito"><label class="alerta exito s" for="alertExito"><div>Abono guardado.</div><div class="close">&times;</div></label>');
		</script>
		
<?php
	} else {
?>
		<script type="text/javascript">
			$('#msj-factura').html('<input type="radio" id="alertError"><label class="alerta error s" for="alertError"><div>Error al guardar, Intentelo nuevamente.</div><div class="close">&times;</div></label>');
		</script>
		
<?php
	}

} else {
?>
	<script type="text/javascript">
		$('#msj-factura').html('<input type="radio" id="alertError"><label class="alerta error s" for="alertError"><div>Error al guardar, Intentelo nuevamente.</div><div class="close">&times;</div></label>');
	</script>
<?php
}

				$facturasProveedorQuery = "SELECT IDMatEntrada, me_factura, me_facturaFecha, me_facturaValor, me_fechaCreacion FROM materialesentrada WHERE me_idProveedor = '$proveedorID' ORDER BY me_fechaCreacion DESC";

						$rowCountFacturasProveedor = $con->query($facturasProveedorQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountFacturasProveedor,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationFacturasProveedor'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $facturasProveedorSql = $con->query($facturasProveedorQuery." LIMIT $numeroResultados");
?>
						<table class="tableList">
							<thead>
								<tr>
									<th class="columnaCorta">Fecha de Factura</th>
									<th># Factura</th>
									<th>Valor Factura</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while( $facturasProveedorRow = $facturasProveedorSql->fetch_assoc() ){

									$facturaAbonos = $con->query("SELECT SUM(pra_abono) AS abonos FROM proveedores_abonos WHERE pra_idProveedor = '$proveedorID' AND pra_idFactura = '$facturasProveedorRow[IDMatEntrada]'")->fetch_assoc();

									$facturaDeuda = $facturasProveedorRow['me_facturaValor'] - $facturaAbonos['abonos'];

									$facturaValor = '$'.number_format($facturaDeuda, 2, ".", ",");
								?>
									<tr>
										<td><?= $facturasProveedorRow['me_facturaFecha'] ?></td>
										<td><?= $facturasProveedorRow['me_factura'] ?></td>
										<td><?= $facturaValor ?></td>
										<td class="tableOption">
											<a class="facturaAbono" data-id="<?= $facturasProveedorRow['IDMatEntrada'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>