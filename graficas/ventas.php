<?php include '../config.php';

$ene = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='01' AND ct_inicial='1'")->fetch_assoc();
$feb = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='02' AND ct_inicial='1'")->fetch_assoc();
$mar = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='03' AND ct_inicial='1'")->fetch_assoc();
$abr = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='04' AND ct_inicial='1'")->fetch_assoc();
$may = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='05' AND ct_inicial='1'")->fetch_assoc();
$jun = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='06' AND ct_inicial='1'")->fetch_assoc();
$jul = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='07' AND ct_inicial='1'")->fetch_assoc();
$ago = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='08' AND ct_inicial='1'")->fetch_assoc();
$sep = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='09' AND ct_inicial='1'")->fetch_assoc();
$oct = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='10' AND ct_inicial='1'")->fetch_assoc();
$nov = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='11' AND ct_inicial='1'")->fetch_assoc();
$dic = $con->query("SELECT SUM(ct_costo) AS r FROM citas WHERE ct_idClinica='$sessionClinica' AND YEAR(ct_fechaCreacion)='$hoyAno' AND MONTH(ct_fechaCreacion)='12' AND ct_inicial='1'")->fetch_assoc();

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