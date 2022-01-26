<?php include'config.php';

$msjBoton = 0;

/* PACIENTE */
if($_POST['t']=='paciente'){
	$desactivarSql = $con->query("SELECT IDPaciente, pc_nombres, pc_idIdentificacion, pc_identificacion, pc_idSexo FROM pacientes WHERE IDPaciente = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();
	$ti_pacienteSql = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$desactivarRow[pc_idIdentificacion]'");
	$ti_pacienteRow = $ti_pacienteSql->fetch_assoc();

	if($desactivarRow['pc_idSexo']==1) { $identi='identificado'; } else { $identi='identificada'; }

	$ti = '';
	if($desactivarRow['pc_idIdentificacion']>0 && $desactivarRow['pc_identificacion']!=""){
		$ti = ", ".$identi." con <b>".$ti_pacienteRow['ti_nombre']." ".$desactivarRow['pc_identificacion']."</b>";
	}
	if($desactivarRow['pc_idIdentificacion']==0 && $desactivarRow['pc_identificacion']!=""){
		$ti = ", ".$identi." con <b>".$desactivarRow['pc_identificacion']."</b>";
	}


	$mensajeModal = "¿Está seguro de eliminar al Paciente <b>".$desactivarRow['pc_nombres']."</b>".$ti."?";
}

/* DOCTOR */
if($_POST['t']=='doctor'){
	$desactivarSql = $con->query("SELECT IDDoctor, dc_nombres, dc_identificacion, dc_genero FROM doctores WHERE IDDoctor = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	if($desactivarRow['dc_genero']=='M') { $identi='identificado'; } else { $identi='identificada'; }

	$ti = '';
	if($desactivarRow['dc_idIdentificacion']>0 && $desactivarRow['dc_identificacion']!=""){
		$ti = ", ".$identi." con <b>".$ti_pacienteRow['ti_nombre']." ".$desactivarRow['dc_identificacion']."</b>";
	}
	if($desactivarRow['dc_idIdentificacion']==0 && $desactivarRow['dc_identificacion']!=""){
		$ti = ", ".$identi." con <b>".$desactivarRow['dc_identificacion']."</b>";
	}

	$mensajeModal = "¿Está seguro de eliminar al Doctor <b>".$desactivarRow['dc_nombres']."</b>".$ti."?";
}

/* MATERIAL */
if($_POST['t']=='material'){
	$desactivarSql = $con->query("SELECT IDMaterial, mt_codigo, mt_nombre FROM materiales WHERE IDMaterial = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el Item <b>".$desactivarRow['mt_nombre']."</b> con código <b>".$desactivarRow['mt_codigo']."</b>?";
}

/* SUCURSAL */
if($_POST['t']=='sucursal'){
	$desactivarSql = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar la Sucursal <b>".$desactivarRow['sc_nombre']."</b>?";
}

/* PROVEEDOR */
if($_POST['t']=='proveedor'){
	$desactivarSql = $con->query("SELECT IDProveedor, pr_nombre, pr_nit FROM proveedores WHERE IDProveedor = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar al Proveedor <b>".$desactivarRow['pr_nombre']."</b> con NIT <b>".$desactivarRow['pr_nit']."</b>?";
}

/* EPS */
if($_POST['t']=='eps'){
	$desactivarSql = $con->query("SELECT IDEps, eps_nombre FROM eps WHERE IDEps = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar la EPS <b>".$desactivarRow['eps_nombre']."</b>?";
}

/* CIUDAD */
if($_POST['t']=='ciudad'){
	$desactivarSql = $con->query("SELECT IDCiudad, cd_nombre FROM ciudades WHERE IDCiudad = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar la Ciudad <b>".$desactivarRow['cd_nombre']."</b>?";
}

/* ESPECIALIDAD */
if($_POST['t']=='especialidad'){
	$desactivarSql = $con->query("SELECT IDEspecialidad, esp_nombre FROM especialidades WHERE IDEspecialidad = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar la Especialidad <b>".$desactivarRow['esp_nombre']."</b>?";
}

/* VENDEDOR */
if($_POST['t']=='vendedor'){
	$desactivarSql = $con->query("SELECT IDVendedor, vn_nombre FROM vendedores WHERE IDVendedor = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el Vendedor <b>".$desactivarRow['vn_nombre']."</b>?";
}

/* TRATAMIENTO */
if($_POST['t']=='tratamiento'){
	$desactivarSql = $con->query("SELECT IDTratamiento, tr_nombre FROM tratamientos WHERE IDTratamiento = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el Tratamiento <b>".$desactivarRow['tr_nombre']."</b>?";
}

/* TIPOS DE IDENTIFICACION */
if($_POST['t']=='tipoIdenti'){
	$desactivarSql = $con->query("SELECT IDTipoIdentificacion, ti_nombre FROM tiposidentificacion WHERE IDTipoIdentificacion = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el Tipo de Identificación <b>".$desactivarRow['ti_nombre']."</b>?";
}

/* USUARIO DE INVENTARIO*/
if($_POST['t']=='usInventario'){
	$desactivarSql = $con->query("SELECT IDUserInventario, ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el Usuario de Inventario <b>".$desactivarRow['ui_nombres']."</b>?";
}

/* USUARIO DE CITAS*/
if($_POST['t']=='usCitas'){
	$desactivarSql = $con->query("SELECT IDUserCitas, uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el Usuario de Citas <b>".$desactivarRow['uc_nombres']."</b>?";
}

/* CITA */
if($_POST['t']=='cita' || $_POST['t']=='citaPaciente' || $_POST['t']=='citaDoctor'){ $msjBoton = 1;
	$desactivarSql = $con->query("SELECT citas.IDCita, citas.ct_idPaciente, pacientes.IDPaciente, pacientes.pc_nombres, citas.ct_anoCita, citas.ct_mesCita, citas.ct_diaCita, citas.ct_horaCita FROM citas, pacientes WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.IDCita = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de Cancelar la cita del paciente <b>".$desactivarRow['pc_nombres'].' | '.$desactivarRow['ct_anoCita'].'/'.$desactivarRow['ct_mesCita'].'/'.$desactivarRow['ct_diaCita'].' '.$desactivarRow['ct_horaCita']."</b>?";
}

/* COMBOS */
if($_POST['t']=='combo'){
	$desactivarSql = $con->query("SELECT * FROM tratamientos WHERE IDTratamiento = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el combo <b>".$desactivarRow['tr_nombre']."</b>?";
}

/* CONVENIOS */
if($_POST['t']=='convenio'){
	$desactivarSql = $con->query("SELECT * FROM convenios WHERE IDConvenio = '$_POST[id]'");
	$desactivarRow = $desactivarSql->fetch_assoc();

	$mensajeModal = "¿Está seguro de eliminar el convenio <b>".$desactivarRow['cnv_nombre']."</b>?";
}
?>
<div class="modal-body"><?php echo $mensajeModal ?></div>
   
<div class="modal-footer">
	<form method="post" id="formDesactivar" action="desactivar-guardar.php" class="form">
		<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
		<input type="hidden" name="t" value="<?php echo $_POST['t'] ?>">
		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<button class="boton boton-primario">
			<?php
				if($msjBoton==1){ echo "Cancelar Cita"; }
				else{ echo "Eliminar"; } ?>
		</button>
	</form> 
</div>
<?php if($_POST['t']=='citaPaciente'){ ?>
	<script>
		$('#formDesactivar').submit(function() {
			$.ajax({
			    type: 'POST',
			    url: $(this).attr('action'),
			    data: $(this).serialize(),
			    // Mostramos un mensaje con la respuesta de PHP
			    success: function(data) {
			        $('#showResultsPcCitas').html(data);
			        $("#consultoriosModal").modal('hide');
			    }
			})        
			return false;
		});
	</script>
<?php }

if($_POST['t']=='citaDoctor'){ ?>
	<script>
		$('#formDesactivar').submit(function() {
			$.ajax({
			    type: 'POST',
			    url: $(this).attr('action'),
			    data: $(this).serialize(),
			    // Mostramos un mensaje con la respuesta de PHP
			    success: function(data) {
			        $('#showResultsHsDoctorCitas').html(data);
			        $("#consultoriosModal").modal('hide');
			    }
			})        
			return false;
		});
	</script>
<?php } ?>