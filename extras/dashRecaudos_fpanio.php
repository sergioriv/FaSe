<?php include'../config.php';

$anioID = $_POST['anio'];

$formasPagoSql = $con->query("SELECT * FROM fomaspago");

?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoSql->fetch_assoc()){
											$abonosFP = $con->query("SELECT SUM(ab_abono) AS sumaFP FROM abonos WHERE ab_idClinica='$sessionClinica' AND ab_idFormaPago='$formasPagoRow[IDFormaPago]' AND ab_estado=1 AND YEAR(ab_fechaCreacion)='$anioID'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosFP['sumaFP'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

<script type="text/javascript">
	$('#contSelectFPAnio').hide();
	$('#contFPAnio').show();
	$('#contFPAnio').html("<?php echo $anioID ?>");
</script>