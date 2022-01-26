<?php include'config.php'; include'pagination-modal-params.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

$id = $_POST['id'];
$query = $con->query("INSERT INTO materialessalida SET ms_idUsuario='$sessionIDUsuario', ms_idMatEntrada='$id', ms_cantidad='$cantidad', ms_detalles='$detalles', ms_estado='1', ms_fechaCreacion='$fechaHoy'");
if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }

unset($_SESSION['consultoriosQuery']);

$entradaRow = $con->query("SELECT me_cantidad, me_idMaterial, me_cero FROM materialesentrada WHERE IDMatEntrada = '$id'")->fetch_assoc();

$cantidadActual = 0;
	$cantidadSalidasSql = $con->query("SELECT SUM(ms_cantidad) AS cantSalida FROM materialessalida WHERE ms_idMatEntrada = '$id' ")->fetch_assoc();

	$cantidadActual = $entradaRow['me_cantidad'] - $cantidadSalidasSql['cantSalida'];

	$cantidadMostrar = $cantidadActual .' / '. $entradaRow['me_cantidad'];

if($cantidadActual <= 0 && $entradaRow['me_cero']==0){
	$con->query("UPDATE materialesentrada SET me_cero = 1 WHERE IDMatEntrada = '$id'");
}


			$querySalidasSession = '';
				if($sessionRol==2){
					$querySalidasSession = "AND me_idSucursal = '$sessionUsuario'";
				} else if($sessionRol==4){
					$usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
					$querySalidasSession = "AND me_idSucursal = '$usuarioInventario[ui_idSucursal]'";
				}

				$salidasQuery = "SELECT * FROM materialessalida AS ms 
						INNER JOIN materialesentrada AS me ON ms.ms_idMatEntrada = me.IDMatEntrada
						WHERE ms_estado = '1' AND me_idMaterial = '$entradaRow[me_idMaterial]' $querySalidasSession
						ORDER BY IDMatSalida DESC ";
				$rowSalidasCount = $con->query($salidasQuery)->num_rows;
				$pagConfig = array(
			        'totalRows' => $rowSalidasCount,
			        'perPage' => $numeroResultados,
			        'link_func' => 'paginationMaterialSalidas'
			    );
			    $pagination =  new Pagination($pagConfig);

    			$salidasSql = $con->query($salidasQuery." LIMIT $numeroResultados");


?>
<script type="text/javascript">
<?php if($query){ ?>

		$('#msj-entradaMaterial').html('<input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Se ha guardado con exito.</div><div class="close">&times;</div></label>');

<?php } else { ?>

		$('#msj-entradaMaterial').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label>');

<?php } ?>

			$( '#' + <?= $id ?> +'.cantidadActual' ).html('<?= $cantidadMostrar ?>');
		<?php if($cantidadActual <= 0){ ?>
			$( '#' + <?= $id ?> +'.consultorioSalida' ).html('');
		<?php } ?>

</script>



					<table class="tableList">
		        		<thead>
		        			<tr>		        				
		        				<th>Cant.</th>
		        				<th class="columnaCorta">Fecha</th>
		        				<th>Usuario</th>
		        				<th># Lote</th>
		        				<th>Reg. Invima</th>
		        				<th>Descripción</th>
		        			</tr>
		        		</thead>
		            	<tbody>
		        	<?php while($salidasRow = $salidasSql->fetch_assoc()){

		        			$rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$salidasRow[ms_idUsuario]'")->fetch_assoc();
		        			$usID = $rol['us_id'];
		        			if($rol['us_idRol']==1){
		        				$usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['cl_nombre'];
		        			} else if($rol['us_idRol']==2){
		        				$usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['sc_nombre'];
		        			} else {
		        				$usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['dc_nombres'];
		        			}
		        	?>
			        		<tr>
			        			<td align="center"><?php echo $salidasRow['ms_cantidad'] ?></td>
			        			<td class="columnaCorta"><?php echo $salidasRow['ms_fechaCreacion'] ?></td>
			        			<td><?php echo $nombreUsuario ?></td>
			        			<td align="center"><?php echo $salidasRow['me_numeroLote'] ?></td>
			        			<td align="center"><?php echo $salidasRow['me_invima'] ?></td>
			        			<td class="text-justify"><?php echo $salidasRow['ms_detalles'] ?></td>
				           	</tr>
		        	<?php } ?>
		        		</tbody>
		        	</table>
		        	<?php echo $pagination->createLinks(); ?>