<?php include'config.php'; include'pagination-modal-params.php';

$epssQuery = "SELECT * FROM eps WHERE eps_estado='1' ORDER BY eps_nombre ASC";

    $rowCount = $con->query($epssQuery)->num_rows;

//Initialize Pagination class and create object
    $pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationEpss'
	);
    $pagination =  new Pagination($pagConfig);

$epssSql = $con->query($epssQuery." LIMIT $numeroResultados");

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>

<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">AFP / ARL / CCF / EPS / PARAFISCALES</div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchEpss" list="epss" class="buscador" placeholder="Buscar . . ." onkeyup="paginationEpss();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th>Cod.</th>
						<th>NIT</th>
						<th>Nombre</th>
					</tr>
				</thead>
				<tbody>
					<?php while($epssRow = $epssSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $epssRow['eps_codigo']; ?></td>
					    <td><?php echo $epssRow['eps_nit']; ?></td>
					    <td><?php echo $epssRow['eps_nombre']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php echo $pagination->createLinks(); ?>
		</div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

<script type="text/javascript">
		function paginationEpss(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchEpss').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/epssData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}
</script>
</body>
</html>