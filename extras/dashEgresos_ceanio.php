<?php include'../config.php';

$anioID = $_POST['anio'];

$formasPagoSql = $con->query("SELECT * FROM fomaspago");

?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoSql->fetch_assoc()){
											$abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$formasPagoRow[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$anioID'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosCE['sumaCE'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

<script type="text/javascript">
	$('#contSelectCEAnio').hide();
	$('#contCEAnio').show();
	$('#contCEAnio').html("<?php echo $anioID ?>");
</script>