<?php include '../config.php';

$ene = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='01' AND ab_estado='1'")->fetch_assoc();
$feb = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='02' AND ab_estado='1'")->fetch_assoc();
$mar = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='03' AND ab_estado='1'")->fetch_assoc();
$abr = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='04' AND ab_estado='1'")->fetch_assoc();
$may = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='05' AND ab_estado='1'")->fetch_assoc();
$jun = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='06' AND ab_estado='1'")->fetch_assoc();
$jul = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='07' AND ab_estado='1'")->fetch_assoc();
$ago = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='08' AND ab_estado='1'")->fetch_assoc();
$sep = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='09' AND ab_estado='1'")->fetch_assoc();
$oct = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='10' AND ab_estado='1'")->fetch_assoc();
$nov = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='11' AND ab_estado='1'")->fetch_assoc();
$dic = $con->query("SELECT SUM(ab_abono) AS r FROM abonos WHERE ab_idClinica='$sessionClinica' AND YEAR(ab_fechaCreacion)='$hoyAno' AND MONTH(ab_fechaCreacion)='12' AND ab_estado='1'")->fetch_assoc();

$data = array(	0 => $ene['r'],
				1 => $feb['r'],
				2 => $mar['r'],
				3 => $abr['r'],
				4 => $may['r'],
				5 => $jun['r'],
				6 => $jul['r'],
				7 => $ago['r'],
				8 => $sep['r'],
				9 => $oct['r'],
				10 => $nov['r'],
				11 => $dic['r']
);

echo json_encode($data);

?>