<?php include'config-lobby.php';

$convencion = $_POST['convencion'];

$IDdiente = $_POST['IDdiente'];

$IDsector = $_POST['IDsector'];

$pacienteID = $_POST['pacienteID'];

$odontogramaID = $_POST['odontogramaID'];

$colSector = 'ods_sector'.$IDsector;


/*
$consulta = $con->query("SELECT * FROM odontogramapaciente WHERE odp_IDPaciente='$pacienteID' AND odp_fechaInt='$valFecha' AND odp_diente='$IDdiente' AND odp_sector='$IDsector'");
if($consulta->num_rows == 0){
	$query = $con->query("INSERT INTO odontogramapaciente SET odp_IDPaciente='$pacienteID', odp_fecha='$fechaOdontograma', odp_fechaInt='$valFecha', odp_diente='$IDdiente', odp_sector='$IDsector', odp_idConvencion='$convencion'");
} else {
	$query = $con->query("UPDATE odontogramapaciente SET odp_idConvencion='$convencion' WHERE odp_IDPaciente='$pacienteID' AND odp_fechaInt='$valFecha' AND odp_diente='$IDdiente' AND odp_sector='$IDsector'");
}
*/
if($convencion==1){ 
	$classConvencion = 'tableOdontoEsp-caries'; $valorConvencion = 0; $valorClass = 1; }
elseif($convencion==4){ 
	$classConvencion = 'tableOdontoEsp-exodoncia'; $valorConvencion = 0; $valorClass = 1; }
elseif($convencion==6){ 
	$classConvencion = 'tableOdontoEsp-incluido'; $valorConvencion = 0; $valorClass = 1; }
elseif($convencion==9){ 
	$classConvencion = 'tableOdontoEsp-endodoncia'; $valorConvencion = 0; $valorClass = 1; }
else { 
	$classConvencion = ''; $valorConvencion = $convencion; $valorClass = 0; }

$consultaDiente = $con->query("SELECT * FROM pacienteodontogramasector WHERE ods_idOdontograma='$odontogramaID' AND ods_diente='$IDdiente'");
if($consultaDiente->num_rows == 0){
	//echo "no exite";
	$query = $con->query("INSERT INTO pacienteodontogramasector SET ods_idOdontograma='$odontogramaID', ods_diente='$IDdiente', $colSector='$valorConvencion', ods_class='$valorClass', ods_classConvencion='$classConvencion'");
} else {
	//echo "existe";
	if($convencion==1 || $convencion==4 || $convencion==6 || $convencion==9) {
		$con->query("UPDATE pacienteodontogramasector SET ods_sector1='0', ods_sector2='0', ods_sector3='0', ods_sector4='0', ods_sector5='0', ods_sector6='0', ods_sector7='0', ods_class='$valorClass', ods_classConvencion='$classConvencion' WHERE ods_idOdontograma='$odontogramaID' AND ods_diente='$IDdiente'");
	} else {
		$query = $con->query("UPDATE pacienteodontogramasector SET $colSector='$valorConvencion', ods_classConvencion='$classConvencion' WHERE ods_idOdontograma='$odontogramaID' AND ods_diente='$IDdiente'");
	}
}

?>