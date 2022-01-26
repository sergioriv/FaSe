<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosCita'][$clave] = $valor;
}

extract($_SESSION['consultoriosCita']);

	$Rpaciente = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$paciente'")->fetch_assoc();
	$Rdoctor = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$doctor'")->fetch_assoc();
	$Rsucursal = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$sucursal'")->fetch_assoc();
	$Runidad = $con->query("SELECT * FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$unidad'")->fetch_assoc();
	$Rtratamiento = $con->query("SELECT * FROM tratamientos WHERE IDTratamiento = '$tratamiento'")->fetch_assoc();
	if($Rtratamiento['tr_idCups']!=0){
		$cups = $con->query("SELECT * FROM cups WHERE IDCups = '$Rtratamiento[tr_idCups]'")->fetch_assoc();
		$cup = $cups['cup_codigo'].' | ';
	}

	$citaHoraHasta = strtotime ( '+'.$duracion.'minute' , strtotime ( $horaCita ) ) ;
	$citaHoraHastaFull = date ( 'H:i' , $citaHoraHasta);

	$validacionCita = $con->query("SELECT * FROM citas WHERE ct_idPaciente='$paciente' AND ct_idTratamiento='$tratamiento' ORDER BY IDCita DESC")->fetch_assoc();
	if($validacionCita['ct_terminado']==1 || $validacionCita['ct_terminado']==2){ $primerCita = 0; }
	else { $primerCita = 1;	}

	if($tratamientoPrecio==0 || $tratamientoPrecio==NULL){
		$precioTratamiento = '';
	} else {
		$precioTratamiento = ' - $'.number_format($tratamientoPrecio, 2, ",", ".");
	}

?>
<style type="text/css">
	.tableList tbody th { text-align: left; }
</style>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Información Cita</h4>
</div>
<form method="post" id="confirmarCitaForm" action="cita-guardar.php" class="form">
<div class="modal-body divForm">
	<table class="tableList tableListAuto tableListTop">
		<tr>
			<th class="columnaCorta">Paciente</th>
			<td><?php echo $Rpaciente['pc_nombres'] ?></td>
		</tr>
		<tr>
			<th>Sucursal</th>
			<td><?php echo $Rsucursal['sc_nombre'] ?></td>
		</tr>
		<tr>
			<th>Unidad</th>
			<td><?php echo $Runidad['uo_nombre'] ?></td>
		</tr>
		<tr>
			<th>Doctor</th>
			<td><?php echo $Rdoctor['dc_nombres'] ?></td>
		</tr>
		<tr>
			<th>Tratamiento</th>
			<td><?php echo $cup.$Rtratamiento['tr_nombre'].$precioTratamiento ?></td>
		</tr>
		<tr>
			<th>Fecha</th>
			<td><?php echo $annoCita.'/'.$mesCita.'/'.$diaCita ?></td>
		</tr>
		<tr>
			<th>Hora</th>
			<td><?php echo $horaCita.' - '.$citaHoraHastaFull ?></td>
		</tr>
		<tr>
			<th>Duración</th>
			<td><?php echo $duracion.' minutos' ?></td>
		</tr>
	</table>
	<p>&nbsp</p>
	<?php if($primerCita==1){ ?>
		<select name="control" class="formulario__modal__input top" data-label="Tipo de Cita" required>
			<option selected hidden value="">-- Seleccionar --</option>
			<option value="1">Primer Cita</option>
			<option value="0">Cita Control</option>
		</select>		
	<?php } else { ?>
		<input type="hidden" name="control" value="0" required>
	<?php } ?>
	<textarea name="notaCita" rows="4" class="formulario__modal__input top" data-label="Motivo de Consulta"></textarea>
	<div class="contenedorCheckbox SliderSwitch pointer">
		<label>¿Enviar una copia la correo?
		<input type="checkbox" id="enviarCorreo" name="enviarCorreo" value="1">
		<div class="SliderSwitch__container">
			<div class="SliderSwitch__toggle"></div>
		</div>
		</label>
	</div>

	<div class="container1Part" id="formCorreoEnviar">
		<input type="email" id="correoEnviar" name="correoEnviar" class="formulario__modal__input top" data-label="Correo Electrónico" value="<?php echo $Rpaciente['pc_correo'] ?>">
	</div>

</div>
   
<div class="modal-footer">
		<input type="hidden" name="annoCita" value="<?php echo $annoCita ?>">
		<input type="hidden" name="mesCita" value="<?php echo $mesCita ?>">
		<input type="hidden" name="diaCita" value="<?php echo $diaCita ?>">
		<input type="hidden" id="horaCita" name="horaCita" value="<?php echo $horaCita ?>">
		<input type="hidden" name="horaInt" value="<?php echo $horaInt ?>">
		<input type="hidden" name="sucursal" value="<?php echo $sucursal ?>">
		<input type="hidden" name="unidad" value="<?php echo $unidad ?>">
		<input type="hidden" name="doctor" value="<?php echo $doctor ?>">
		<input type="hidden" name="tratamiento" value="<?php echo $tratamiento ?>">
		<input type="hidden" name="tipoTratamiento" value="<?php echo $tipoTratamiento ?>">
		<input type="hidden" name="tratamientoPrecio" value="<?php echo $tratamientoPrecio ?>">
		<input type="hidden" name="tratamientoPresupuesto" value="<?php echo $tratamientoPresupuesto ?>">
		<input type="hidden" name="paciente" value="<?php echo $paciente ?>">
		<input type="hidden" name="citaID" value="<?php echo $citaID ?>">
		<input type="hidden" name="duracion" value="<?php echo $duracion ?>">
		<input type="hidden" name="dash" value="<?php echo $_POST['dash'] ?>">
		<input type="hidden" name="dashDate" value="<?php echo $_POST['dashDate'] ?>">
		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<button class="boton boton-primario">Guardar Cita</button>
	
</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">

<?php if($_POST['dash'] == 1){ ?>
		$('#confirmarCitaForm').submit(function() {
				$.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#msj-comparativo').html(data);
		                $("#consultoriosModal").modal('hide');
		            }
		        })
		        return false;
		});
<?php } ?>

	var formCorreoEnviar = document.getElementById("formCorreoEnviar");
	var correoEnviar = document.getElementById("correoEnviar");
	var enviarCorreo = document.getElementById("enviarCorreo");
	formCorreoEnviar.style.display='none';
$('#enviarCorreo').click(function(){
	if (enviarCorreo.checked) {
        formCorreoEnviar.style.display='grid';
        correoEnviar.setAttribute("required", "required");
    }
    else {
    	formCorreoEnviar.style.display='none';
        correoEnviar.removeAttribute("required");
    }
});
</script>
<?php
unset($_SESSION['consultoriosCita']);
?>