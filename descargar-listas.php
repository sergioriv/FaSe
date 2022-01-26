<?php include'config.php';

/*
	class="consultorioDescargar" 
	data-page
	data-search
	data-rango-de
	data-rango-hasta
	data-rango-id
 */

$search = trim($_POST['search']);
$page = $_POST['page'];

$rangoDe = $_POST['rangoDe'];
$rangoHasta = $_POST['rangoHasta'];
$rangoID = $_POST['rangoID'];

$searchUrl = '';


if($page == 'pacientes'){

	if(!empty($search)){
	    $searchUrl = '?search='.$search;
	}
	$pageDescargar = $page.'-descargar-excel.php'.$searchUrl;

}

if($page == 'citas_historico'){
	$pageDescargar = 'citas-historico-excel.php?de='.$rangoDe.'&hasta='.$rangoHasta;
}

if($page == 'doctor_referidos'){
	$pageDescargar = 'doctor-referidos-excel.php?id='.$rangoID.'&de='.$rangoDe.'&hasta='.$rangoHasta;
}

if($page == 'vendedor_referidos'){
	$pageDescargar = 'vendedor-referidos-excel.php?id='.$rangoID.'&de='.$rangoDe.'&hasta='.$rangoHasta;
}

if($page == 'materiales_inicial'){
	$pageDescargar = 'materiales-inicial-excel.php';
}

if($page == 'material_entradas'){
	$pageDescargar = 'material-entradas-excel.php?id='.$rangoID.'&de='.$rangoDe.'&hasta='.$rangoHasta;
}

if($page == 'material_salidas'){
	$pageDescargar = 'material-salidas-excel.php?id='.$rangoID.'&de='.$rangoDe.'&hasta='.$rangoHasta;
}

if($page == 'recaudosDia'){
	$pageDescargar = 'excel-recaudo-dia.php?dia='.$search;
}

if($page == 'recaudosDiaCE'){
	$pageDescargar = 'excel-egresos-dia.php?dia='.$search;
}

if($page == 'citasAtendidas'){
	$pageDescargar = 'excel-citas-atendidas.php?de='.$rangoDe.'&hasta='.$rangoHasta;
}


?>
    <script type="text/javascript">
        setTimeout("location.href = '<?php echo $pageDescargar ?>'");
    </script>