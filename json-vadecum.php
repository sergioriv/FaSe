<?php include'config.php';

$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT * FROM vadecum WHERE ( vd_medicamento LIKE '%$busqueda%' ) ORDER BY vd_medicamento ";
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
	$json[] = ['id'=>$row['IDVadecum'], 'text'=>$row['vd_medicamento'], 'descrip'=>$row['vd_presentacion']];
}

echo json_encode(array( 'items' => $json, 'pag' => $pag));
?>