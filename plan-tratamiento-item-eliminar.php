<?php include'config.php';

$id = $_POST['id'];
$planID = $_SESSION['consultorioTmpPlanID'];

$query = $con->query("DELETE FROM plantratatratamientos WHERE IDPlanTrataTrata = '$id'");

if(!$query){
?>
	<script>$('#msj-plan-tratamiento').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al eliminar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>');</script>
<?php
}

$cantidadItems = 0;

						$fasesSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica)");
							while($fasesRow = $fasesSql->fetch_assoc()){
								$tratamientosFase = $con->query("SELECT * FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE tr_idFase='$fasesRow[IDFase]' AND pltt_idPlan='$planID'")->num_rows;
								if($tratamientosFase>0){
							?>
								<div class="titulo tituloSecundario top">Fase <?= $fasesRow['fs_nombre'] ?></div>
				            	<table class="tableList tableListAuto tableListTop">
				            		<thead>
								        <tr>
								          <th>Tratamiento</th>
								          <th class="columnaCorta">Diente</th>
								          <th>&nbsp</th>
								        </tr>
								     </thead>
				            		<tbody>
				            	<?php $tratamientosSql = $con->query("SELECT IDPlanTrataTrata, pltt_diente, tr_nombre FROM plantratatratamientos AS pltt INNER JOIN tratamientos AS tr ON pltt.pltt_idTratamiento = tr.IDTratamiento WHERE pltt_idPlan = '$planID' AND tr_idFase = '$fasesRow[IDFase]' ORDER BY pltt_diente ASC, tr_nombre ASC");
				            		while($tratamientosRow = $tratamientosSql->fetch_assoc()){
				            			$cantidadItems++;
				            	?>
				            			<tr>
					            			<td><?php echo $tratamientosRow['tr_nombre']?></td>
					            			<td align="center"><?php echo $tratamientosRow['pltt_diente'] ?></td>
					            			<td class="tableOption">
					            				<a title="Eliminar" id="eliminarItemPlan" class="eliminar" data-id="<?php echo $tratamientosRow['IDPlanTrataTrata'] ?>"><?php echo $iconoEliminar ?></a>
					            			</td>
					            		</tr>
					            <?php } ?>
				            		</tbody>
				            	</table>				            	
				            <?php }
				        		}
				        	?>
				        		<script>
				        			$("#plan-items").val("<?= $cantidadItems ?>");
				        		</script>