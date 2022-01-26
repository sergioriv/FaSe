<?php include'config.php';

$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT * FROM eps WHERE eps_estado='1' AND (eps_codigo LIKE '%$busqueda%' OR eps_nit LIKE '%$busqueda%' OR eps_nombre LIKE '%$busqueda%') ORDER BY eps_nombre ";

$rowCount = $con->query($query)->num_rows;

if($newlimit >= $rowCount || $rowCount <= 10){

	$pag = false;
} else{

	$pag = true;
}

$sql = $con->query($query.' LIMIT '.$newlimit.', 10 ');

$json = [];
while($row = $sql->fetch_assoc()){
	$json[] = ['id'=>$row['IDEps'], 'text'=>$row['eps_nombre']];
}

echo json_encode(array( 'items' => $json, 'pag' => $pag));

?>