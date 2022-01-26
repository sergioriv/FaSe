<?php include'config.php'; include 'pagination-modal-params.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>

<body>
	<div class="contenedorPrincipal">

		<div id="msj" class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

	<div class="contenedorTabs">
		<input id="tab-1" type="radio" name="tab-group" checked />
		<label for="tab-1" class="labelTab">AFP / ARL / CCF / EPS / PARAFISCALES</label>
		<input id="tab-2" type="radio" name="tab-group" />
		<label for="tab-2" class="labelTab">CIE-10</label>
		<input id="tab-3" type="radio" name="tab-group" />
		<label for="tab-3" class="labelTab">CUPS</label>
		<input id="tab-4" type="radio" name="tab-group" />
		<label for="tab-4" class="labelTab">CIUO-08</label>
		<input id="tab-5" type="radio" name="tab-group" />
		<label for="tab-5" class="labelTab">Vademecum</label>

		<div class="contenidoTab">

			<div id="content-1">

				<?php $epssQuery = "SELECT * FROM eps WHERE eps_estado='1' ORDER BY eps_nombre ASC";

					$rowCountEps = $con->query($epssQuery)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountEps,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationEpss'
						);
					    $pagination =  new Pagination($pagConfig);

					$epssSql = $con->query($epssQuery." LIMIT $numeroResultados");
				?>
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countEpss">Cantidad: [<?php echo $rowCountEps ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListEpss" class="buscador" placeholder="Buscar . . ." onkeyup="paginationEpss();">
					</span>
				</div>

				<div id="showResultsEpss">
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

			<div id="content-2">

				<?php $cie10Query = "SELECT * FROM rips WHERE rip_estado='1' ORDER BY rip_nombre ASC";

					$rowCountCie10 = $con->query($cie10Query)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountCie10,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationCie10'
						);
					    $pagination =  new Pagination($pagConfig);

					$cie10Sql = $con->query($cie10Query." LIMIT $numeroResultados");
				?>
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countCie10">Cantidad: [<?php echo $rowCountCie10 ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListCie10" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCie10();">
					</span>
				</div>

				<div id="showResultsCie10">
					<table class="tableList">
						<thead>
							<tr>
								<th>Cod.</th>
								<th>Nombre</th>
							</tr>
						</thead>
						<tbody>
							<?php while($cie10Row = $cie10Sql->fetch_assoc()){ ?>
							<tr>
							    <td><?php echo $cie10Row['rip_codigo']; ?></td>
							    <td><?php echo $cie10Row['rip_nombre']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>

			<div id="content-3">

				<?php $cupsQuery = "SELECT * FROM cups WHERE cup_estado='1' ORDER BY cup_nombre ASC";

					$rowCountCups = $con->query($cupsQuery)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountCups,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationCups'
						);
					    $pagination =  new Pagination($pagConfig);

					$cupsSql = $con->query($cupsQuery." LIMIT $numeroResultados");
				?>
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countCups">Cantidad: [<?php echo $rowCountCups ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListCups" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCups();">
					</span>
				</div>

				<div id="showResultsCups">
					<table class="tableList">
						<thead>
							<tr>
								<th>Cod.</th>
								<th>Nombre</th>
							</tr>
						</thead>
						<tbody>
							<?php while($cupsRow = $cupsSql->fetch_assoc()){ ?>
							<tr>
							    <td><?php echo $cupsRow['cup_codigo']; ?></td>
							    <td><?php echo $cupsRow['cup_nombre']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>

			<div id="content-4">

				<?php $ciuo8Query = "SELECT * FROM ocupaciones ORDER BY IDOcupacion ASC";

					$rowCountCiuo8 = $con->query($ciuo8Query)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountCiuo8,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationCiuo8'
						);
					    $pagination =  new Pagination($pagConfig);

					$ciuo8Sql = $con->query($ciuo8Query." LIMIT $numeroResultados");
				?>
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countCiuo8">Cantidad: [<?php echo $rowCountCiuo8 ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListCiuo8" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCiuo8();">
					</span>
				</div>

				<div id="showResultsCiuo8">
					<table class="tableList">
						<thead>
							<tr>
								<th>Cod.</th>
								<th>Nombre</th>
							</tr>
						</thead>
						<tbody>
							<?php while($ciuo8Row = $ciuo8Sql->fetch_assoc()){ ?>
							<tr>
							    <td><?php echo $ciuo8Row['ocu_codigo']; ?></td>
							    <td><?php echo $ciuo8Row['ocu_nombre']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>

			<div id="content-5">

				<?php $vademecumQuery = "SELECT * FROM vadecum ORDER BY vd_medicamento ASC";

					$rowCountVademecum = $con->query($vademecumQuery)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountVademecum,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationVademecum'
						);
					    $pagination =  new Pagination($pagConfig);

					$vademecumSql = $con->query($vademecumQuery." LIMIT $numeroResultados");
				?>
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"><span class="cantRegistros" id="countVademecum">Cantidad: [<?php echo $rowCountVademecum ?>]</span></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" id="searchListVademecum" class="buscador" placeholder="Buscar . . ." onkeyup="paginationVademecum();">
					</span>
				</div>

				<div id="showResultsVademecum">
					<table class="tableList">
						<thead>
							<tr>
								<th>Medicamento</th>
								<th>Presentación</th>
							</tr>
						</thead>
						<tbody>
							<?php while($vademecumRow = $vademecumSql->fetch_assoc()){ ?>
							<tr>
							    <td><?php echo $vademecumRow['vd_medicamento']; ?></td>
							    <td><?php echo $vademecumRow['vd_presentacion']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php echo $pagination->createLinks(); ?>
				</div>
			</div>

		</div>

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
		    var busqueda = $('#searchListEpss').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/epssData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsEpss').html(html);
		        }
		    });
		}
		function paginationCie10(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchListCie10').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/cie10Data.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsCie10').html(html);
		        }
		    });
		}
		function paginationCups(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchListCups').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/cupsData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsCups').html(html);
		        }
		    });
		}
		function paginationCiuo8(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchListCiuo8').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/ciuo8Data.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsCiuo8').html(html);
		        }
		    });
		}
		function paginationVademecum(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchListVademecum').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/vademecumData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsVademecum').html(html);
		        }
		    });
		}
	</script>
</body>
</html>