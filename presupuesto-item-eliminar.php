<?php include'config.php';

$id = $_POST['id'];
$presupuestoID = $_POST['presupuesto'];

$query = $con->query("DELETE FROM presupuestotratamientos WHERE IDPresupuestoTrata = '$id'");

if(!$query){
?>
	<script>$('#msj-presupuesto').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al eliminar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>');</script>
<?php
}

$sumaTotal = 0;
$totalPresupuesto = 0;
$cantidadItems = 0;

						$fasesSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica)");
							while($fasesRow = $fasesSql->fetch_assoc()){
								$tratamientosFase = $con->query("SELECT * FROM presupuestotratamientos WHERE ppt_idFase='$fasesRow[IDFase]' AND ppt_idPresupuesto='$presupuestoID'")->num_rows;
								if($tratamientosFase>0){
							?>
								<div class="titulo tituloSecundario top">Fase <?= $fasesRow['fs_nombre'] ?></div>
				            	<table class="tableList">
				            		<thead>
								        <tr>
								          <th>Combo / Tratamiento</th>
								          <th>Dientes</th>
								          <th class="columnaCorta">Cant.</th>
								          <th>Precio</th>
								          <th>Dto.</th>
								          <th>Total</th>
								          <th>&nbsp</th>
								        </tr>
								     </thead>
				            		<tbody>
				            	<?php $tratamientosSql = $con->query("SELECT * FROM presupuestotratamientos, tratamientos WHERE presupuestotratamientos.ppt_idTratamiento = tratamientos.IDTratamiento AND presupuestotratamientos.ppt_idFase = '$fasesRow[IDFase]' AND presupuestotratamientos.ppt_idPresupuesto = '$presupuestoID' ORDER BY tratamientos.tr_nombre ASC, presupuestotratamientos.IDPresupuestoTrata ASC");
				            		while($tratamientosRow = $tratamientosSql->fetch_assoc()){

				            			$subtotalTratamiento = ( $tratamientosRow['ppt_cantidad'] * $tratamientosRow['ppt_precio'] );

				            			if($tratamientosRow['ppt_descuento'] > 0){
				            				$totalTratamiento = $subtotalTratamiento - (($subtotalTratamiento*$tratamientosRow['ppt_descuento'])/100);
				            			} else {
				            				$totalTratamiento = $subtotalTratamiento;
				            			}

				            			$sumaTotal += $totalTratamiento;
				            			$cantidadItems++;
				            	?>
				            			<tr>
					            			<td><?php echo $tratamientosRow['tr_nombre']?></td>
					            			<td><?php echo $tratamientosRow['ppt_dientes'] ?></td>
					            			<td align="center"><?php echo $tratamientosRow['ppt_cantidad']?></td>
					            			<td align="right"><?php echo '$'.number_format($tratamientosRow['ppt_precio'], 0, ".", ","); ?></td>
					            			<td align="center"><?php echo $tratamientosRow['ppt_descuento'].'%' ?></td>
					            			<td align="right"><?php echo '$'.number_format($totalTratamiento, 0, ".", ","); ?></td>
					            			<td class="tableOption">
					            				<a title="Eliminar" id="eliminarItemPresupuesto" class="eliminar" data-id="<?php echo $tratamientosRow['IDPresupuestoTrata'] ?>"><?php echo $iconoEliminar ?></a>
					            			</td>
					            		</tr>
					            <?php } ?>
				            		</tbody>
				            	</table>
				            	<p style="border-bottom: 1px solid var(--colorGray)">&nbsp</p>
				            <?php }
				        		}

				        		$totalPresupuesto = '$'.number_format($sumaTotal, 0, ".", ",");

				        		?>
				        		<script>
				        			$("#totalPresupuesto").html("Total presupuesto: <?= $totalPresupuesto ?>");
				        			$("#pre-total").val("<?= $sumaTotal ?>");
				        			$("#pre-items").val("<?= $cantidadItems ?>");
				        		</script>