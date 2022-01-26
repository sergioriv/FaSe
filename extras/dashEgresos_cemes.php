<?php include'../config.php';

$mesID = $_POST['mes'];
$anioID = $_POST['anio'];

if($anioID == ""){
	$anioID = $hoyAno;
}
if($mesID == ""){
	$mesID = $hoyMes;
}

$formasPagoSql = $con->query("SELECT * FROM fomaspago");
$arrayMeses = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$IDmes = (int) $mesID;
?>
								<table class="tableList">
									<tbody>
										<?php $totalFormaPago = 0; 

										while($formasPagoRow = $formasPagoSql->fetch_assoc()){
											$abonosCE = $con->query("SELECT SUM(pra_abono) AS sumaCE FROM ordenesabonos WHERE pra_idClinica='$sessionClinica' AND pra_idFormaPago='$formasPagoRow[IDFormaPago]' AND pra_estado=1 AND YEAR(pra_fechaCreacion)='$anioID' AND MONTH(pra_fechaCreacion)='$mesID'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosCE['sumaCE'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

<script type="text/javascript">
	$('#contSelectCEMes').hide();
	$('#contCEMes').show();
	$('#contCEMes').html("<?php echo $arrayMeses[$IDmes] ?>");
</script>