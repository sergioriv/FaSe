<?php include'config.php'; include'pagination-modal-params.php';

$id = $_POST['id'];
$sucursalID = $_POST['sucursalID'];

$unidad = $con->query("SELECT uo_nombre FROM unidadesodontologicas WHERE IDUnidadOdontologica = $id")->fetch_assoc();

$query = $con->query("UPDATE unidadesodontologicas SET uo_estado = 0 WHERE IDUnidadOdontologica = $id");

if($query){
?>
	<script type="text/javascript">
		$('#msj-unidades').html('<input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Se ha eliminado la unidad <b><?php echo $unidad["uo_nombre"] ?></b>.</div><div class="close">&times;</div></label>');
	</script>
<?php
} else {
?>
	<script type="text/javascript">
		$('#msj-unidades').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al eliminar la unidad, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label>');
	</script>
<?php
}

	$unidadesSucursalQuery = "SELECT * FROM unidadesodontologicas WHERE uo_idSucursal = $sucursalID AND uo_estado = 1";

					$rowCountScUnidades = $con->query($unidadesSucursalQuery)->num_rows;
					$pagConfig = array(
						'totalRows' => $rowCountScUnidades,
					    'perPage' => $numeroResultados,
						'link_func' => 'paginationScUnidades'
					);
				    $pagination =  new Pagination($pagConfig);
				    $unidadesSucuralSql = $con->query($unidadesSucursalQuery." LIMIT $numeroResultados");
?>

						<table class="tableList">
			        		<thead>
			        			<tr>
			        				<th>Nombre</th>
									<th>&nbsp</th>
			        			</tr>
			        		</thead>
			            	<tbody>
			        	<?php while($unidadesRow = $unidadesSucuralSql->fetch_assoc()){	?>
				        		<tr>				        			
				        			<td><?php echo $unidadesRow['uo_nombre'] ?></td>
				        			<td class="tableOption">
								    	<a id="<?php echo $unidadesRow['IDUnidadOdontologica'] ?>" class="consultorioEliminarUnidad eliminar"><?php echo $iconoEliminar ?></a>
								    </td>
					           	</tr>
			        	<?php } ?>
			        		</tbody>
			        	</table>
			        	<?php echo $pagination->createLinks(); ?>