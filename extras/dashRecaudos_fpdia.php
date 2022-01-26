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
											$abonosFP = $con->query("SELECT SUM(ab_abono) AS sumaFP FROM abonos WHERE ab_idClinica='$sessionClinica' AND ab_idFormaPago='$formasPagoRow[IDFormaPago]' AND ab_estado=1 AND YEAR(ab_fechaCreacion)='$fechaID[0]' AND MONTH(ab_fechaCreacion)='$fechaID[1]' AND DAY(ab_fechaCreacion)='$fechaID[2]'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosFP['sumaFP'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

<script type="text/javascript">
	$('#contSelectFPDia').hide();
	$('#contFPDia').show();
	$('#contFPDiaTitle').html("<?php echo $fechaMostrar ?>");
</script>