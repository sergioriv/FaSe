<?php include'config.php';

$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT * FROM rips WHERE rip_estado = '1' AND ( rip_codigo LIKE '%$busqueda%' ) ORDER BY rip_codigo ";

$rowCount = $con->query($query)->num_rows;

if($newlimit >= $rowCount || $rowCount <= 10){

	$pag = false;
} else{

	$pag = true;
}

$sql = $con->query($query.' LIMIT '.$newlimit.', 10 ');

$json = [];
while($row = $sql->fetch_assoc()){
	$json[] = ['id'=>$row['IDRips'], 'text'=>$row['rip_codigo'].' | '.$row['rip_nombre']];
}

echo json_encode(array( 'items' => $json, 'pag' => $pag));
?>