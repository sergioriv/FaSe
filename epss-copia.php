<?php include'config.php'; include'pagination.php';

$epssSql = $con->query("SELECT * FROM eps WHERE eps_estado='1' ORDER BY eps_nombre");

$numeroEpss = $epssSql->num_rows;
$paginacion = new pag();
$paginacion->records($numeroEpss);
$paginacion->records_per_page($numeroResultados);
$limit = 'LIMIT ' .(($paginacion->get_page() - 1) * $numeroResultados). ',' .$numeroResultados;

$epssSql = $con->query("SELECT * FROM eps WHERE eps_estado='1' ORDER BY eps_nombre $limit");

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php' ?>
</head>

<style type="text/css">

</style>

<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">AFP / ARL / CCF / EPS / PARAFISCALES</div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" name="searchList" id="searchList" list="epss" class="buscador" placeholder="Buscar . . .">
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
			<?php $paginacion->render();?>
		</div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<?php include'footer.php'; ?>

</body>
</html>