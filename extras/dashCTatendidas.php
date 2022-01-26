<?php include'../config.php'; include('../pagination-modal-params.php');

$atendidas_de = str_replace('-', '', $_POST['atendidas_de']);
$atendidas_hasta = str_replace('-', '', $_POST['atendidas_hasta']);

$ver_atendidas_de = str_replace('-', '/', $_POST['atendidas_de']);
$ver_atendidas_hasta = str_replace('-', '/', $_POST['atendidas_hasta']);

$citasAtendidasQuery = "SELECT 
								ct.ct_anoCita,
								ct.ct_mesCita,
								ct.ct_diaCita,
								pc.pc_nombres,
								dc.dc_nombres,
								tr.tr_nombre,
								ct.ct_costo,
								ct.ct_trataPorcentaje
									FROM citas AS ct
									INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
									INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
									INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
									WHERE ct.ct_idClinica = '$sessionClinica' AND ct.ct_evolucionada=1 AND ct.ct_estado IN(0,1) AND ct.ct_fechaInicio BETWEEN '$atendidas_de' AND '$atendidas_hasta' ORDER BY IDCita DESC";

		$dashRowCountCitasAtendidas = $con->query($citasAtendidasQuery)->num_rows;

		$pagConfig = array(
			'totalRows' => $dashRowCountCitasAtendidas,
		    'perPage' => $numeroResultados,
			'link_func' => 'paginationDashCitasAtendidas'
		);
		$pagination =  new Pagination($pagConfig);

		$dashCitasAtendidasSql = $con->query($citasAtendidasQuery." LIMIT $numeroResultados");

?>
						<table class="tableList">
                                <thead>
                                    <tr>
                                        <th>Fecha de Cita</th>
                                        <th>Paciente</th>
                                        <th>Doctor</th>
                                        <th>Tratamiento</th>
                                        <th>Vr Total Tratamiento</th>
                                        <th>Valor cita</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($dashCitasAtendidasRow = $dashCitasAtendidasSql->fetch_assoc()){
                                        $valorCitaAtendidaOP = ( $dashCitasAtendidasRow['ct_costo'] * $dashCitasAtendidasRow['ct_trataPorcentaje'] ) / 100;

										$valorTRCitaAtendida = '$ '.number_format($dashCitasAtendidasRow['ct_costo'], 0, ".", ",");
                                        $valorCitaAtendida = '$ '.number_format($valorCitaAtendidaOP, 0, ".", ",");

                                    ?>
                                        <tr>
                                            <td align="center" class="columnaCorta"><?= $dashCitasAtendidasRow['ct_anoCita'].'/'.$dashCitasAtendidasRow['ct_mesCita'].'/'.$dashCitasAtendidasRow['ct_diaCita'] ?></td>
                                            <td><?= $dashCitasAtendidasRow['pc_nombres'] ?></td>
                                            <td><?= $dashCitasAtendidasRow['dc_nombres'] ?></td>
                                            <td><?= $dashCitasAtendidasRow['tr_nombre'] ?></td>
											<td align="right"><?= $valorTRCitaAtendida ?></td>
                                            <td align="right"><?= '('.$dashCitasAtendidasRow['ct_trataPorcentaje'].'%) '.$valorCitaAtendida ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
		<?php echo $pagination->createLinks(); ?>

<script type="text/javascript">
	$('#contSelectCTatendidas').hide();
	$('#contCTatendidas').show();
	$('#contCTatendidasTitle').html("Citas atendidas <?= $ver_atendidas_de.' - '.$ver_atendidas_hasta ?>");
</script>