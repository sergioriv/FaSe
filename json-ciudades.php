<?php include'config.php';

$busqueda = trim($_GET['q']);
$page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
$newlimit = ($page-1)*10;

$query = "SELECT * FROM ciudades, departamentos WHERE ciudades.cd_idDep = departamentos.IDDepartamento AND ciudades.cd_estado='1' AND (ciudades.cd_nombre LIKE '%$busqueda%' OR departamentos.dp_nombre LIKE '%$busqueda%') ORDER BY departamentos.dp_nombre, ciudades.cd_nombre ";

$rowCount = $con->query($query)->num_rows;

if($newlimit >= $rowCount || $rowCount <= 10){

	$pag = false;
} else{

	$pag = true;
}

$sql = $con->query($query.' LIMIT '.$newlimit.', 10 ');

$json = [];
while($row = $sql->fetch_assoc()){
	$json[] = ['id'=>$row['IDCiudad'], 'text'=>$row['dp_nombre'].' | '.$row['cd_nombre']];
}

echo json_encode(array( 'items' => $json, 'pag' => $pag));
?>