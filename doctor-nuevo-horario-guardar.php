<?php include'config.php'; include'pagination-modal-params.php';

$fecha = $_POST['fecha'];
$doctorID = $_POST['doctorID'];
$horarioNuevoDcDe = $_POST['horarioNuevoDcDe'];
$horarioNuevoDcHasta = $_POST['horarioNuevoDcHasta'];
$horarioNuevoLibreDcDe = $_POST['horarioNuevoLibreDcDe'];
$horarioNuevoLibreDcHasta = $_POST['horarioNuevoLibreDcHasta'];

$fechaInt = str_replace("/", "", $fecha);

$verificarFecha = $con->query("SELECT * FROM doctoreshorarios WHERE dch_idDoctor = '$doctorID' AND dch_fechaInt = '$fechaInt'")->num_rows;
if($verificarFecha > 0){
?>
	<script>$('#msjNuevoHorario').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, la fecha <b><?php echo $fecha ?></b>, ya tiene un horario asignado.</div><div class="close">&times;</div></label></div>');</script>
<?php
} else {
	$query = $con->query("INSERT INTO doctoreshorarios SET 
		dch_idDoctor='$doctorID', 
		dch_fecha='$fecha', 
		dch_fechaInt='$fechaInt', 
		dch_atencionDe='$horarioNuevoDcDe', 
		dch_atencionHasta='$horarioNuevoDcHasta', 
		dch_horarioLibreDe='$horarioNuevoLibreDcDe', 
		dch_horarioLibreHasta='$horarioNuevoLibreDcHasta', 
		dch_fechaCreacion='$fechaHoy'");

	if($query){  
?>
		<script>$('#msjNuevoHorario').html('<div class="contenedorAlerta"><input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Se ha guardado con exito.</div><div class="close">&times;</div></label></div>');</script>
<?php
	} else {
?>
		<script>$('#msjNuevoHorario').html('<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label></div>');</script>
<?php
	}
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
										<a title="Eliminar" id="<?php echo $horariosPersonalizadosRow['IDDocHorario'] ?>" t="horarioPersonalizado" class="consultorioEliminarHorario eliminar"><?php echo $iconoEliminar ?></a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>