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
											$abonosFP = $con->query("SELECT SUM(ab_abono) AS sumaFP FROM abonos WHERE ab_idClinica='$sessionClinica' AND ab_idFormaPago='$formasPagoRow[IDFormaPago]' AND ab_estado=1 AND YEAR(ab_fechaCreacion)='$anioID' AND MONTH(ab_fechaCreacion)='$mesID'")->fetch_assoc();

										?>
										<tr>
											<td><?php echo $formasPagoRow['fp_nombre'] ?></td>
											<td align="right"><?php echo '$'.number_format($abonosFP['sumaFP'], 0, ".", ","); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>

<script type="text/javascript">
	$('#contSelectFPMes').hide();
	$('#contFPMes').show();
	$('#contFPMes').html("<?php echo $arrayMeses[$IDmes] ?>");
</script>