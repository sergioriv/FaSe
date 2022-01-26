<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$ordenID = $_POST['ordenID'];

$ordenEntrada = $con->query("SELECT ore_numeroOrden, ore_numeroFactura, pr_nombre FROM ordenesentrada AS ore
		INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
		WHERE IDOrdenEntrada = '$ordenID'")->fetch_assoc();

?>
<div class="modal-header">  
	<a class="close" data-dismiss="modal">&times;</a>  
	<h4 class="modal-title"><?= $ordenEntrada['pr_nombre'].' | # Orden: '.$ordenEntrada['ore_numeroOrden'].' | # Factura: '.$ordenEntrada['ore_numeroFactura'] ?></h4>
</div>

	<div class="modal-body">

		<div class="divForm">

<?php
			$abonosOrdenQuery = "SELECT * FROM ordenesabonos WHERE pra_idOrden = '$ordenID' AND pra_estado = 1 ORDER BY IDOrdenAbono DESC ";

			$rowCountAbonosOrden = $con->query($abonosOrdenQuery)->num_rows;
				$pagConfig = array(
					'totalRows' => $rowCountAbonosOrden,
				    'perPage' => $numeroResultados,
					'link_func' => 'paginationAbonosOrden'
				);
			    $pagination =  new Pagination($pagConfig);

			$abonosOrdenSql = $con->query($abonosOrdenQuery." LIMIT $numeroResultados");

	?>
			<div id="showResultsAbonosOrden">
				<table class="tableList">
			        <thead>
			        	<th>#</th>
			        	<th class="columnaCorta">Fecha</th>
			        	<th>Usuario</th>
			        	<th>Comentario</th>
			        	<th>Forma Pago</th>
					    <th>Valor</th>
					    <th>&nbsp</th>
			        </thead>
			        <tbody>
			        	<?php while($abonosOrdenRow = $abonosOrdenSql->fetch_assoc()){

			        			$rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$abonosOrdenRow[pra_idUsuario]'")->fetch_assoc();
			        			$usID = $rol['us_id'];
			        			if($rol['us_idRol']==1){
			        				$usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
			        				$nombreUsuario = $usuario['cl_nombre'];
			        			} else if($rol['us_idRol']==2){
			        				$usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
			        				$nombreUsuario = $usuario['sc_nombre'];
			        			} else {
			        				$usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
			        				$nombreUsuario = $usuario['dc_nombres'];
			        			}

			        			$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosOrdenRow[pra_idFormaPago]'")->fetch_assoc();

						        	if($abonosOrdenRow['pra_idFormaPago']==2){
										$bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosOrdenRow[pra_idBanco]'")->fetch_assoc();
										$abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosOrdenRow['pra_cheque'];
									} else {
										$abonoFormaPago = $formaPago['fp_nombre'];
									}
			        	?>
							<tr>
								<td align="right"><?= $abonosOrdenRow['pra_consecutivo'] ?></td>
								<td><?= $abonosOrdenRow['pra_fechaCreacion'] ?></td>
								<td><?= $nombreUsuario ?></td>
								<td><?= $abonosOrdenRow['pra_comentario'] ?></td>
								<td align="center"><?= $abonoFormaPago ?></td>
								<td align="right"><?= '$'.number_format($abonosOrdenRow['pra_abono'], 0, '.', ',') ?></td>
								<td class="tableOption">
									<a title="Descargar PDF" href="orden-abono-pdf.php?q=<?= encrypt( 'id='.$abonosOrdenRow['IDOrdenAbono'] ) ?>"><i class="fa fa-download"></i></a>
								</td>
							</tr>
			        	<?php
			        	}
			        	?>
			        </tbody>
			    </table>
			    <?php echo $pagination->createLinks(); ?>
			</div>
		</div>
	</div>
   
	<div class="modal-footer">  
		<a class="boton boton-secundario" data-dismiss="modal">Cerrar</a>
	</div>

<script type="text/javascript">

	function paginationAbonosOrden(page_num) {
		page_num = page_num?page_num:0;
		$.ajax({
			type: 'POST',
			url: 'get/abonosOrdenData.php',
			data:'page='+page_num+'&id='+<?= $ordenID ?>,
			success: function (html) {
				$('#showResultsAbonosOrden').html(html);
			}
		});
	}

</script>