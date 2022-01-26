<?php include'config.php'; $id = $_POST['id'];
$pacienteSql = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$id'");
$pacienteRow = $pacienteSql->fetch_assoc();

	$compleanosHoy = new DateTime($fechaHoy);
	$cumpleanos = new DateTime($pacienteRow['pc_fechaNacimiento']);
	$diffCumpleanos = $compleanosHoy->diff($cumpleanos);
	$edad = $diffCumpleanos->y;

	$epsSql = $con->query("SELECT * FROM eps WHERE IDEPS = '$pacienteRow[pc_idEps]'");
	$epsRow = $epsSql->fetch_assoc();

	$tipoIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$pacienteRow[pc_idIdentificacion]'");
	$tipoIdentiRow = $tipoIdentiSql->fetch_assoc();

	$ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$pacienteRow[pc_idCiudad]'");
	$ciudadRow = $ciudadSql->fetch_assoc();

	$estadoCivilSql = $con->query("SELECT * FROM estadosciviles WHERE IDEstadoCivil = '$pacienteRow[pc_idEstadoCivil]'");
	$estadoCivilRow = $estadoCivilSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>
  <h4 class="modal-title">Paciente: <?php echo $pacienteRow['pc_nombres'] ?></h4>
</div>
<div class="modal-body">
	<div class="contenedorTabs">
		        <input id="tab-1" type="radio" name="tab-group" checked />
		        <label for="tab-1" class="labelTab">Información</label>
		        <input id="tab-2" type="radio" name="tab-group" />
		        <label for="tab-2" class="labelTab">Antecedentes</label>
		        <input id="tab-3" type="radio" name="tab-group" />
		        <label for="tab-3" class="labelTab">Tratamientos</label>
		        <a onClick="location.href='registro-fotografico.php?id=<?php echo $id ?>&paciente=<?php echo $pacienteUrl ?>'">
		        	<label class="labelTab">Registro Fotográfico</label>
		        </a>

		    <div class="contenidoTab">
		        <div class="divForm" id="content-1">

		        	<div class="consultorioView">
						<div class="viewImg">
					 		<?php
								if($pacienteRow['pc_foto']!=''){ echo "<img src='$pacienteRow[pc_foto]'>"; }
							   	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
							?>
						</div>
						<h4>
						 	<?php
						  		if($pacienteRow['pc_genero']=='M'){ echo "Masculino"; }
								if($pacienteRow['pc_genero']=='F'){ echo "Femenino"; }
							?> 
						</h4>
						<h4><?php echo $tipoIdentiRow['ti_nombre'].' '.$pacienteRow['pc_identificacion'] ?></h4>
						<h4><?php if($edad>0){ echo $edad.' años'; } ?></h4>
						<h4><?php echo $epsRow['eps_nombre'] ?></h4>
						<p>&nbsp</p>
						<h4><?php echo $pacienteRow['pc_telefonoFijo'] ?></h4>
						<h4><?php echo $pacienteRow['pc_telefonoCelular'] ?></h4>
						<h4><?php echo $ciudadRow['cd_nombre'].' '.$pacienteRow['pc_direccion'] ?></h4>
						<h4><?php echo $pacienteRow['pc_area'] ?></h4>
						<h4><?php echo $pacienteRow['pc_correo'] ?></h4>
						<h4><?php echo $pacienteRow['pc_fechaNacimiento'] ?></h4>
						<h4><?php echo $estadoCivilRow['ec_nombre'] ?></h4>
						<h4><?php echo $pacienteRow['pc_ocupacion'] ?></h4>
					</div>

		        </div>
		        <div class="divForm" id="content-2">

		        	<div class="consultorioView">
			        	<h4><?php echo nl2br(trim($pacienteRow['pc_medicamentos'])) ?></h4>
			        	<p>&nbsp</p>
			            <h4>RIPS:</h4>
			            <table class="tableList">
			            	<tbody>
			            	<?php $pacienteRipsSql = $con->query("SELECT * FROM pacientesrips, rips WHERE pacientesrips.prip_idRips = rips.IDRips AND pacientesrips.prip_idPaciente = '$id'");
			            		while($pacienteRipsRow = $pacienteRipsSql->fetch_assoc()){
			            	?>
			            		<tr>
				           			<td><?php echo $pacienteRipsRow['rip_codigo'].' | '.$pacienteRipsRow['rip_nombre'] ?></td>
				           			<td><?php echo $pacienteRipsRow['prip_fechaCreacion'] ?></td>
				           		</tr>
				            <?php } ?>
			            	</tbody>
			            </table>
		        	</div>

		        </div>
		        <div class="divForm" id="content-3">

		        	<table class="tableList">
		            	<tbody>
		            <?php $tratamientosSql = $con->query("SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$id' AND citas.ct_inicial = '1' ORDER BY citas.ct_fechaOrden DESC");
		            	while($tratamientosRow = $tratamientosSql->fetch_assoc()){
			            	if($tratamientosRow['ct_terminado']==1){
								$estado = 'Activo';
							} else {
								$estado = 'Terminado '.$tratamientosRow['ct_terminadoFecha'];
							}
		            ?>
		            		<tr>
			           			<td><?php echo $tratamientosRow['tr_nombre'] ?></td>
								<td><?php echo $estado ?></td>
			           		</tr>
			        <?php } ?>
		            	</tbody>
		            </table>

		        </div>
	</div>

















	
</div>