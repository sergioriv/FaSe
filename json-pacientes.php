<?php include'config.php';

$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT IDPaciente, pc_nombres FROM pacientes WHERE pc_idClinica='$sessionClinica' AND pc_estado='1' AND  (pc_nombres LIKE '%$busqueda%') ORDER BY pc_nombres ";

$rowCount = $con->query($query)->num_rows;

if($newlimit >= $rowCount || $rowCount <= 10){

	$pag = false;
} else {

	$pag = true;
}

$sql = $con->query($query.' LIMIT '.$newlimit.', 10 ');

$json = [];
while($row = $sql->fetch_assoc()){
	$json[] = ['id'=>$row['IDPaciente'], 'text'=>$row['pc_nombres']];
}

echo json_encode(array( 'items' => $json, 'pag' => $pag));
?>