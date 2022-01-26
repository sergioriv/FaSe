<?php include'config.php'; include'pagination-modal-params.php';

$id = $_POST['id'];
$doctorID = $_POST['doctorID'];

$query = $con->query("DELETE FROM doctoreshorarios WHERE IDDocHorario = '$id'");

if($query){  
?>
	<script>$('#msjNuevoHorario').html('<div class="contenedorAlerta"><input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Se ha eliminado el horario.</div><div class="close">&times;</div></label></div>');</script>
<?php
} else {
?>
	<script>$('#msjNuevoHorario').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al eliminar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>');</script>
<?php
}

	$horariosPersonalizadosQuery = "SELECT * FROM doctoreshorarios WHERE dch_idDoctor = '$doctorID' ORDER BY dch_fechaInt DESC";

						$rowCountHorarioPersonalizado = $con->query($horariosPersonalizadosQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountHorarioPersonalizado,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationHorariosPersonalizados'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $horariosPersonalizadosSql = $con->query($horariosPersonalizadosQuery." LIMIT $numeroResultados");

?>
						<table class="tableList">
							<thead>
								<tr>
									<th class="columnaCorta">Fecha</th>
									<th>Horario de atención</th>
									<th>Bloque de descanso</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($horariosPersonalizadosRow = $horariosPersonalizadosSql->fetch_assoc()){ ?>
								<tr>
									<td><?php echo $horariosPersonalizadosRow['dch_fecha'] ?></td>
									<td class="centro"><?php echo $horariosPersonalizadosRow['dch_atencionDe'].' / '.$horariosPersonalizadosRow['dch_atencionHasta'] ?></td>
									<td class="centro"><?php echo $horariosPersonalizadosRow['dch_horarioLibreDe'].' / '.$horariosPersonalizadosRow['dch_horarioLibreHasta'] ?></td>
									<td class="tableOption">
										<a title="Eliminar" id="<?php echo $horariosPersonalizadosRow['IDDocHorario'] ?>" class="consultorioEliminarHorario eliminar"><?php echo $iconoEliminar ?></a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>