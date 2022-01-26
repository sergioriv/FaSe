<?php include'config.php';

$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT * FROM cups WHERE (cup_codigo LIKE '%$busqueda%' OR cup_nombre LIKE '%$busqueda%') ORDER BY cup_codigo ";

$rowCount = $con->query($query)->num_rows;

if($newlimit >= $rowCount || $rowCount <= 10){

	$pag = false;
} else{

	$pag = true;
}

$sql = $con->query($query.' LIMIT '.$newlimit.', 10 ');

$json = [];
while($row = $sql->fetch_assoc()){
	$json[] = ['id'=>$row['IDCups'], 'text'=>$row['cup_codigo'].' | '.$row['cup_nombre']];
}

echo json_encode(array( 'items' => $json, 'pag' => $pag));
?>