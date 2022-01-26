<?php include'../config.php';

$fechaID = explode('-', $_POST['dia']);

if($_POST['dia'] == date('Y-m-d')){
	$fechaMostrar = 'Hoy';
} else {
	$fechaMostrar = str_replace('-', '/', $_POST['dia']);
}

$formasPagoSql = $con->query("SELECT * FROM fomaspago");
?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoSql->fetch_assoc()){
											$abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$formasPagoRow[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$fechaID[0]' AND MONTH(pra_fechaCreacion)='$fechaID[1]' AND DAY(pra_fechaCreacion)='$fechaID[2]'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosCE['sumaCE'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

<script type="text/javascript">
	$('#contSelectCEDia').hide();
	$('#contCEDia').show();
	$('#contCEDiaTitle').html("<?php echo $fechaMostrar ?>");
</script>