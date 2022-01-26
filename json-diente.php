<?php include'config.php';

$odontogramaID = $_SESSION['consultorioTmpOdontogramaID'];
$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT * FROM pacienteodontogramasector WHERE ods_idOdontograma='$odontogramaID' ORDER BY ods_diente ASC ";
//OR vd_presentacion LIKE '%".$busqueda."%'

$rowCount = $con->query($query)->num_rows;

if($newlimit >= $rowCount || $rowCount <= 10){

	$pag = false;
} else{

	$pag = true;
}

$sql = $con->query($query.' LIMIT '.$newlimit.', 10 ');

$json = [];
while($row = $sql->fetch_assoc()){
	
	if($row['ods_class'] == 1){
		$classConvencion = explode('-', $row['ods_classConvencion']);
		$json[] = [
			'id'=>$row['ods_diente'], 
			'text'=>$row['ods_diente'], 
			'class'=>$classConvencion[1], 
			'convenciones'=>$convenciones
		];
	}
	else {
		$classConvencion = NULL;
		$convenciones = '';

		if($row['ods_sector1']>0 
			|| $row['ods_sector2']>0 
			|| $row['ods_sector3']>0 
			|| $row['ods_sector4']>0 
			|| $row['ods_sector5']>0 
			|| $row['ods_sector6']>0 
			|| $row['ods_sector7']>0 
		){
			$array_convenciones = array($row['ods_sector1'],$row['ods_sector2'],$row['ods_sector3'],$row['ods_sector4'],$row['ods_sector5'],$row['ods_sector6'],$row['ods_sector7']);

			$resultado_convenciones = array_unique($array_convenciones);

			for ($i=0; $i < sizeof($array_convenciones) ; $i++) { 

				$convencionRow = $con->query("SELECT IDConvencion, cv_nombre FROM convenciones WHERE IDConvencion='$resultado_convenciones[$i]'")->fetch_assoc();
				if($convencionRow['IDConvencion'] > 0){
					$convenciones .= '<i>'.$convencionRow['cv_nombre'].'</i>';
				}
			}

			$json[] = [
				'id'=>$row['ods_diente'], 
				'text'=>$row['ods_diente'], 
				'class'=>$classConvencion[1], 
				'convenciones'=>$convenciones
			];
		}
		
	}

		
}

/*
, 'sector1'=>$row['ods_sector1'], 'sector2'=>$row['ods_sector2'],	'sector3'=>$row['ods_sector3'],	'sector4'=>$row['ods_sector4'],	'sector5'=>$row['ods_sector5'],	'sector6'=>$row['ods_sector6'],	'sector7'=>$row['ods_sector7']
 */

echo json_encode(array( 'items' => $json, 'pag' => $pag));
?>