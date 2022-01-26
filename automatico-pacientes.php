<?php include'config-lobby.php'; ?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css">
	*{
		font-family: verdana;
		font-weight: bold;
		background: #171717;
		color: #cecece;
		    line-height: 2;
		    font-size: 15px;
	}
</style>
<?php

$fechaEmailHoy = $hoyAno.'/'.$hoyMes.'/'.$hoyDia;


$correo15 = strtotime ( '+ 14 days' , strtotime ( $fechaEmailHoy ) ) ;
echo $fechaCorreo15 = date ( 'Ymd' , $correo15);
echo '<br>';
$correo8 = strtotime ( '+ 7 days' , strtotime ( $fechaEmailHoy ) ) ;
echo $fechaCorreo8 = date ( 'Ymd' , $correo8);
echo '<br>';
$correo1 = strtotime ( '+ 1 days' , strtotime ( $fechaEmailHoy ) ) ;
echo $fechaCorreo1 = date ( 'Ymd' , $correo1);


echo '<p>&nbsp</p>';

$citasSql = $con->query("SELECT * FROM citas, pacientes, doctores, tratamientos WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_estado<='1'");

while($citasRow = $citasSql->fetch_assoc()){
	$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'];
	$dias	= (strtotime($fechaEmailHoy)-strtotime($fechaCita))/86400;
	$dias 	= abs($dias);
	$dias 	= floor($dias);
	echo 'ID:'.$citasRow['IDCita'].' | dias: '.$dias.' | '.$citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].'<br>';
}

echo '<p>&nbsp</p>';
$citasSql = $con->query("SELECT * FROM citas, pacientes, doctores, tratamientos WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_estado<='1' AND (citas.ct_fechaInicio='$fechaCorreo15' OR citas.ct_fechaInicio='$fechaCorreo8' OR citas.ct_fechaInicio='$fechaCorreo1')");

while($citasRow = $citasSql->fetch_assoc()){

	$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'];
	$dias	= (strtotime($fechaEmailHoy)-strtotime($fechaCita))/86400;
	$dias 	= abs($dias);
	$dias 	= floor($dias);

	echo 'ID:'.$citasRow['IDCita'].' | dias: '.$dias.' | '.$citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].'<br>';
}


?>