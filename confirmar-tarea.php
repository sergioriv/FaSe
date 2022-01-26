<?php include'config.php';

$tareaID = $_POST['tarea'];
$tipo = $_POST['tipo'];

$tarea = $con->query("SELECT tar.*, tpt.tpt_nombre, pc.pc_nombres, pc.pc_telefonoCelular, pc.pc_telefonoFijo, pc.pc_correo FROM tareas AS tar
	INNER JOIN citas AS ct ON tar.tar_idCita = ct.IDCita 
	INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
	INNER JOIN tipotarea AS tpt ON tar.tar_idTipo = tpt.IDTipoTarea 
	WHERE IDTarea = '$tareaID'")->fetch_assoc();

if($tarea['pc_telefonoCelular']>0){
	$tareaPacienteTelefono = $tarea['pc_telefonoCelular'];
} else {
	$tareaPacienteTelefono = $tarea['pc_telefonoFijo'];
}

$usuarioTareaSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$tarea[tar_responsable]'")->fetch_assoc();
    if($usuarioTareaSql['us_idRol']==2){
        $responsableNombreSql = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal = '$usuarioTareaSql[us_id]'")->fetch_assoc();
        $responsableNombre = $responsableNombreSql['sc_nombre'];
    } elseif($usuarioTareaSql['us_idRol']==3){
        $responsableNombreSql = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor = '$usuarioTareaSql[us_id]'")->fetch_assoc();
        $responsableNombre = $responsableNombreSql['dc_nombres'];
    } elseif($usuarioTareaSql['us_idRol']==4){
        $responsableNombreSql = $con->query("SELECT ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$usuarioTareaSql[us_id]'")->fetch_assoc();
        $responsableNombre = $responsableNombreSql['ui_nombres'];
    } elseif($usuarioTareaSql['us_idRol']==5){
        $responsableNombreSql = $con->query("SELECT uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$usuarioTareaSql[us_id]'")->fetch_assoc();
        $responsableNombre = $responsableNombreSql['uc_nombres'];
    }

?>
<style type="text/css">
	.tableList tbody th { text-align: left; }
</style>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>
  <h4 class="modal-title">
  	<?php if($tarea['tar_estado']==0){ echo "¿Está seguro de dar por completada la tarea?"; }
  			else { echo "Información tarea"; }
  	?>
  </h4>
</div>
<form method="post" id="formConfirmarTarea" action="confirmar-tarea-guardar.php" class="form">
	<div class="modal-body divForm">

		<table class="tableList tableListAuto tableListTop">
			<tr>
				<th class="columnaCorta">Paciente:</th>
				<td><?= $tarea['pc_nombres'] ?></td>
			</tr>
			<tr>
				<th class="columnaCorta">Teléfono:</th>
				<td><?= $tareaPacienteTelefono ?></td>
			</tr>
			<tr>
				<th class="columnaCorta">Correo:</th>
				<td><?= $tarea['pc_correo'] ?></td>
			</tr>
			<tr>
				<th class="columnaCorta">Fecha para:</th>
				<td><?= str_replace('-','/',$tarea['tar_fecha']) ?></td>
			</tr>
			<?php if($tarea['tar_estado']==1){ ?>
				<tr>
					<th class="columnaCorta">Completada:</th>
					<td><?= str_replace('-','/',$tarea['tar_completada']) ?></td>
				</tr>
			<?php } ?>
			<tr>
				<th class="columnaCorta">Tipo tarea:</th>
				<td><?= $tarea['tpt_nombre'] ?></td>
			</tr>
			<tr>
				<th class="columnaCorta">Responsable:</th>
				<td><?= $responsableNombre ?></td>
			</tr>
			<tr>
				<th class="columnaCorta">Nota:</th>
				<td><?= $tarea['tar_nota'] ?></td>
			</tr>
		</table>

	</div>
	   
	<div class="modal-footer">

<?php if($tipo=='pendientes'){ ?>
			<input type="hidden" name="tarea" value="<?php echo $tareaID ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<button class="boton boton-primario">Completar tarea</button>
<?php } else { ?>
			<a class="boton boton-secundario" data-dismiss="modal">Cerrar</a>
<?php } ?>
	</div>
</form> 

<?php if($tipo=='pendientes'){ ?>
<script type="text/javascript">
	$('#formConfirmarTarea').submit(function() {
				$.ajax({
		        	type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(), 
			        success:function(data){
						paginationDashTareasPendientes(0);
						paginationDashTareasRealizadas(0);
						$('#consultoriosModal').modal('hide');
					}
			    });
			    return false;
			});
</script>
<?php } ?>