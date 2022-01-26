<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$id = $_POST['id'];
$_SESSION['FaSe_editID'] = $_POST['id'];

$citaSql = $con->query("SELECT * FROM citas, pacientes, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.IDCita = '$id'");
$citaRow = $citaSql->fetch_assoc();

$ripRow = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip]'")->fetch_assoc();
$rip1Row = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip1]'")->fetch_assoc();
$rip2Row = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip2]'")->fetch_assoc();
$rip3Row = $con->query("SELECT * FROM rips WHERE IDRips = '$citaRow[ct_idRip3]'")->fetch_assoc();
$externaRow = $con->query("SELECT * FROM causaexterna")->fetch_assoc();
$finalidadRow = $con->query("SELECT * FROM finalidadconsulta")->fetch_assoc();

	$progress_tratamiento_inicial = $con->query("SELECT IDCita FROM citas WHERE ct_idTratamiento = '$citaRow[ct_idTratamiento]' AND ct_idPaciente = '$citaRow[ct_idPaciente]' AND ct_inicial = '1' AND IDCita <= '$id' ORDER BY IDCita DESC")->fetch_assoc();

	$progress_tratamiento_suma = $con->query("SELECT SUM(ct_trataPorcentaje) AS porcentaje FROM citas 
					WHERE ct_idTratamiento = '$citaRow[ct_idTratamiento]' AND ct_idPaciente = '$citaRow[ct_idPaciente]' AND IDCita BETWEEN '$progress_tratamiento_inicial[IDCita]' AND '$id' ORDER BY IDCita DESC")->fetch_assoc();

	$trataProcentaje_inicial = $progress_tratamiento_suma['porcentaje'];
?>
	<script type="text/javascript">
		$('#tab-100').click();
		$('#ch100').click(function() {
			$('#tab-100').each(function(){
				$(this).click();
			})
		});
	</script>
	<style type="text/css">
		.tableList tbody th { text-align: left; }
	</style>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Evolución cita: <?php echo $citaRow['ct_anoCita'].'/'.$citaRow['ct_mesCita'].'/'.$citaRow['ct_diaCita'].' '.$citaRow['ct_horaCita'].' | '.$citaRow['pc_nombres'] ?></h4>
</div>
<form class="form" method="post">
	<div class="modal-body">
		<div class="divForm">

			<?php if($citaRow['ct_asistencia']>0){
				if($citaRow['ct_asistencia']==2){
					echo "<input type='checkbox' id='tab-100' disabled checked><input type='hidden' name='asistencia' value='1'>";
				} else {
					echo "<input type='checkbox' id='tab-100' disabled><input type='hidden' name='asistencia' value='0'>";
				}
			} else { echo "<input type='checkbox' id='tab-100'>"; } ?>


			<div class="containerPart titulo tituloSecundario">
				<div class="contenedorCheckbox SliderSwitch 
				<?php if($citaRow['ct_asistencia']==0){ echo 'pointer'; } ?>">
					<label>Asistencia del paciente
						
						<?php if($citaRow['ct_asistencia']>0){
									if($citaRow['ct_asistencia']==2){
										echo "<input type='checkbox' checked disabled>";
									} else {
										echo "<input type='checkbox' disabled>";
									}
								} else { echo "<input type='checkbox' name='asistencia' value='1' id='ch100' checked>"; } ?>


						
						<div class="SliderSwitch__container">
							<div class="SliderSwitch__toggle"></div>
						</div>
					</label>
				</div>

				<div id="descargaEvolucion">
					<a href="cita-evolucion-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Descargar Evolución</a>
					<a href="cita-medicamentos-pdf.php?q=<?= encrypt( 'id='.$id ) ?>"><i class="fa fa-download"></i>Descargar Medicamentos</a>
				</div>
			</div>


			<div class="formEvolucion">

			<div class="divForm" id="content-100">
				<div class="contenedorTabs">
				    <input id="tab-101" type="radio" name="tab-group" checked />
					<label for="tab-101" class="labelTab">Descripción</label>
				    <input id="tab-102" type="radio" name="tab-group" />
					<label for="tab-102" class="labelTab">Diagnosticos</label>
				    <input id="tab-103" type="radio" name="tab-group" />
					<label for="tab-103" class="labelTab">Medicamentos</label>
				    <input id="tab-104" type="radio" name="tab-group" />
					<label for="tab-104" class="labelTab">Notas Aclaratorias</label>

					<div class="contenidoTab">

				        <div class="divForm" id="content-101">				
							<table class="tableList tableListAuto tableListTop">
								<tr>
									<th>Descripción</th>
									<td><?= nl2br($citaRow['ct_descripcion']) ?></td>
								</tr>
								<tr>
									<th>Finalidad de Consulta</th>
									<td><?= $finalidadRow['fc_nombre'] ?></td>
								</tr>
								<tr>
									<th>Causa Externa</th>
									<td><?= $externaRow['ce_nombre'] ?></td>
								</tr>
								<tr>
									<th>Progreso del tratamiento: <b><?= $citaRow['tr_nombre'] ?></b></th>
									<td><?= $trataProcentaje_inicial.' %' ?></td>
								</tr>
							</table>
						</div>

						<div class="divForm" id="content-102">
							<table class="tableList tableListAuto tableListTop">
								<tr>
									<th>CIE 10 DX Ppal.</th>
									<td><?= $ripRow['rip_nombre'] ?></td>
								</tr>
								<tr>
									<th>CIE 10 DX Rel. 1</th>
									<td><?= $rip1Row['rip_nombre'] ?></td>
								</tr>
								<tr>
									<th>CIE 10 DX Rel. 2</th>
									<td><?= $rip2Row['rip_nombre'] ?></td>
								</tr>
								<tr>
									<th>CIE 10 DX Rel. 3</th>
									<td><?= $rip3Row['rip_nombre'] ?></td>
								</tr>
							</table>
						</div>

						<div class="divForm" id="content-103">

							<?php $citaMedicamentosQuery = "SELECT * FROM citamedicamentos, vadecum WHERE citamedicamentos.cm_idVadecum = vadecum.IDVadecum AND citamedicamentos.cm_idCita='$id' ORDER BY citamedicamentos.IDCitaMedicamento ASC";

								$rowCountCitaMedicamentos = $con->query($citaMedicamentosQuery)->num_rows;
								$pagConfig = array(
									'totalRows' => $rowCountCitaMedicamentos,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationCitaMedicamentos'
								);
							    $pagination =  new Pagination($pagConfig);

								$citaMedicamentosSql = $con->query($citaMedicamentosQuery." LIMIT $numeroResultados");

							?>
							<div id="listMedicamentos">
								<table class="tableList tableSinheight tablePadding">
			                        <thead>
			                          <tr>
			                            <th class="columnaCorta">Fecha asignación</th>
                            			<th>Cant.</th>
			                            <th>Medicamento</th>
			                          </tr>
			                        </thead>
			                        <tbody>
			                        	<?php while($citaMedicamentosRow = $citaMedicamentosSql->fetch_assoc()){
			                        	?>
			                        	<tr>
			                        		<td><?php echo $citaMedicamentosRow['cm_fechaCreacion'] ?></td>   
			                        		<td><?php echo $citaMedicamentosRow['cm_cantidad'] ?></td>   
			                        		<td class="selectMedicamento"><?php echo '<span>'.$citaMedicamentosRow['vd_medicamento']
			                        				.'</span><i>'.$citaMedicamentosRow['vd_presentacion'].'</i>' ?></td>
			                        	</tr>
			                        <?php } ?>
			                        </tbody>
			                    </table>
			                    <?php echo $pagination->createLinks(); ?>
							</div>

						</div>

						<div class="divForm" id="content-104">

							<?php $aclaratoriasQuery = "SELECT usuarios.us_nombre, notaaclaratoria.* FROM notaaclaratoria INNER JOIN usuarios ON notaaclaratoria.na_idUsuario = usuarios.IDUsuario WHERE na_idCita = '$id' ORDER BY IDNotaAclaratoria DESC ";

								$rowCountCitaNotas = $con->query($aclaratoriasQuery)->num_rows;
								$pagConfig = array(
									'totalRows' => $rowCountCitaNotas,
								    'perPage' => $numeroResultados,
									'link_func' => 'paginationCitaNotasAclaratorias'
								);
							    $pagination =  new Pagination($pagConfig);

								$aclaratoriasSql = $con->query($aclaratoriasQuery." LIMIT $numeroResultados");


							?>

							<div class="contRips container3PartInput">
								<input type="text" id="notaAclaratoria" class="formulario__modal__input" data-label="Nota">
								<span></span>
								<a class="boton boton-primario guardarNotaAclaratoria">Agregar</a>
							</div>

							<div id="listNotaAclaratoria">
								<table class="tableList">
									<thead>
										<tr>
											<th class="columnaCorta">Fecha</th>
											<th>Usuario</th>
											<th>Nota</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($aclaratoriasRow = $aclaratoriasSql->fetch_assoc()) { ?>
												<tr>
													<td><?= $aclaratoriasRow['na_fechaCreacion'] ?></td>
													<td><?= $aclaratoriasRow['us_nombre'] ?></td>
													<td><?= $aclaratoriasRow['na_nota'] ?></td>
												</tr>
										<?php
											}
										?>
									</tbody>
								</table>
								<?php echo $pagination->createLinks(); ?>
							</div>

						</div>
					</div>
				</div>
				
			</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">

		function paginationCitaMedicamentos(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/citaMedicamentosData.php',
		        data:'page='+page_num+'&q='+true+'&id='+<?php echo $id ?>,
		        success: function (html) {
		            $('#listMedicamentos').html(html);
		        }
		    });
		}

		function paginationCitaNotasAclaratorias(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/citaNotasAclaratoriasData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#listNotaAclaratoria').html(html);
		        }
		    });
		}

</script>