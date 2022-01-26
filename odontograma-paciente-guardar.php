<?php include'config.php'; include('pagination-modal-params.php'); include'encrypt.php';

$odontogramaID = $_POST['odontogramaID'];
$pacienteID = $_POST['pacienteID'];
$nota = nl2br(trim($_POST['notaOdontograma']));

$dientesafectados = $_POST['dientesafectados'];

$odontogramaConsecutivo = $con->query("SELECT MAX(pod_consecutivo) as consecutivo FROM pacienteodontograma WHERE pod_idClinica='$sessionClinica' AND pod_estado=1")->fetch_assoc();
$fechaOdontograma = date('Y/m/d');
$fechaOdontogramaInt = date('Ymd');


$consecutivo = @$odontogramaConsecutivo['consecutivo'] + 1 ;

$query = $con->query("INSERT INTO pacienteodontograma SET pod_idClinica='$sessionClinica', pod_idPaciente='$pacienteID', pod_consecutivo='$consecutivo', pod_fecha='$fechaOdontograma', pod_fechaInt='$fechaOdontogramaInt', pod_estado=0, pod_nota='$nota', pod_dientes='$dientesafectados'");
$id_insert = $con->insert_id;


if(isset($_POST['imageOdontograma']) && !empty($_POST['imageOdontograma']))
{
	$dataImage = file_get_contents($_POST['imageOdontograma']);
	$newImage = 'img-odonto/O'.$id_insert.'.jpg';
	file_put_contents($newImage, $dataImage);
	$queryImage = $con->query("UPDATE pacienteodontograma SET pod_estado=1, pod_odontoImage='$newImage' WHERE IDOdontograma = '$id_insert'");

?>
	<script type="text/javascript">
		$('#msj-odontograma').html('<input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Odontograma guardado</div><div class="close">&times;</div></label>');
	</script>

<?php
} else if(empty($_POST['imageOdontograma']))
{
?>
	<script type="text/javascript">
		$('#msj-odontograma').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, el Odontograma se encontraba vacío</div><div class="close">&times;</div></label>');
	</script>
<?php
} else 
{
?>
	<script type="text/javascript">
		$('#msj-odontograma').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label>');
	</script>
<?php
}

							$pcOdontogramasQuery = "SELECT * FROM pacienteodontograma WHERE pod_idPaciente = '$pacienteID' AND pod_estado = 1 ORDER BY IDOdontograma DESC";

							$rowCountPcOdontogramas = $con->query($pcOdontogramasQuery)->num_rows;

							//Initialize Pagination class and create object
								$pagConfig = array(
									'totalRows' => $rowCountPcOdontogramas,
									'perPage' => $numeroResultados,
									'link_func' => 'paginationPcOdontogramas'
								);
								$pagination =  new Pagination($pagConfig);

							$pcOdontogramasSql = $con->query($pcOdontogramasQuery." LIMIT $numeroResultados");
?>
								<table class="tableList">
									<thead>
										<tr>
											<th class="columnaCorta">Fecha</th>
											<th>Consecutivo</th>
											<th>Nota</th>
											<th>&nbsp</th>
										</tr>
									</thead>
									<tbody>
										<?php while($pcOdontogramaRow = $pcOdontogramasSql->fetch_assoc()){ ?>
										<tr>
											<td><?= $pcOdontogramaRow['pod_fecha'] ?></td>
											<td align="center"><?= $pcOdontogramaRow['pod_consecutivo'] ?></td>
											<td align="center"><?= $pcOdontogramaRow['pod_nota'] ?></td>
											<td class="tableOption">
												<a title="Ver odontograma" class="verOdontograma" data-id="<?= $pcOdontogramaRow['IDOdontograma'] ?>"><i class="fa fa-table" aria-hidden="true"></i></a>
												<a title="Crear plan de tratamiento" class="nuevoPlan" data-odontograma="<?= $pcOdontogramaRow['IDOdontograma'] ?>"><i class="fa fa-clipboard"></i></a>
												<a title="Descargar PDF" href="odontograma-paciente-pdf.php?q=<?= encrypt( 'id='.$pcOdontogramaRow['IDOdontograma'] ) ?>"><i class="fa fa-download"></i></a>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>