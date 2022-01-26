<?php include '../config.php';

$ene = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='01' AND ct_estado<'2'")->fetch_assoc();
$feb = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='02' AND ct_estado<'2'")->fetch_assoc();
$mar = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='03' AND ct_estado<'2'")->fetch_assoc();
$abr = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='04' AND ct_estado<'2'")->fetch_assoc();
$may = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='05' AND ct_estado<'2'")->fetch_assoc();
$jun = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='06' AND ct_estado<'2'")->fetch_assoc();
$jul = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='07' AND ct_estado<'2'")->fetch_assoc();
$ago = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='08' AND ct_estado<'2'")->fetch_assoc();
$sep = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='09' AND ct_estado<'2'")->fetch_assoc();
$oct = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='10' AND ct_estado<'2'")->fetch_assoc();
$nov = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='11' AND ct_estado<'2'")->fetch_assoc();
$dic = $con->query("SELECT COUNT(*) AS ct FROM citas WHERE ct_idClinica='$sessionClinica' AND ct_anoCita='$hoyAno' AND ct_mesCita='12' AND ct_estado<'2'")->fetch_assoc();

$data = array(	0 => $ene['ct'],
				1 => $feb['ct'],
				2 => $mar['ct'],
				3 => $abr['ct'],
				4 => $may['ct'],
				5 => $jun['ct'],
				6 => $jul['ct'],
				7 => $ago['ct'],
				8 => $sep['ct'],
				9 => $oct['ct'],
				10 => $nov['ct'],
				11 => $dic['ct']
);

echo json_encode($data);

?>