<?php include'config.php'; include'pagination.php';

if($sessionRol==1){
	$citasQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==2){
	$citasQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
	$sucursalNameID = $sessionUsuario;
} else if($sessionRol==3){
	$citasQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
} else if($sessionRol==5){
	$userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
	$citasQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento	ORDER BY citas.ct_fechaOrden ASC";
	$sucursalNameID = $userCitas['uc_idSucursal'];
}

$citasSql = $con->query($citasQuery);

$numeroCitas = $citasSql->num_rows;
$paginacion = new pag();
$paginacion->records($numeroCitas);
$paginacion->records_per_page($numeroResultados);
$limit = 'LIMIT ' .(($paginacion->get_page() - 1) * $numeroResultados). ',' .$numeroResultados;

$citasSql = $con->query($citasQuery." $limit");


if($sessionRol==2 || $sessionRol==5){
	$sucursalName = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$sucursalNameID'")->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>

<body>
	<div class="contenedorPrincipal">

		<div id="msj" class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

<!--	<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Citas pendientes <?php if($sessionRol==5){ echo "| Sucursal: ".$sucursalName['sc_nombre']; } ?></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" name="searchList" id="searchList" list="citas" class="buscador" placeholder="Buscar . . .">
			</span>
		</div>
-->
	<div class="contenedorTabs">
		<input id="tab-1" type="radio" name="tab-group" checked />
		<label for="tab-1" class="labelTab">Citas pendientes</label>
		<input id="tab-2" type="radio" name="tab-group" />
		<label for="tab-2" class="labelTab">Citas sin evolución</label>

		<div class="contenidoTab">
			<div id="content-1">




				
				<div class="tituloBuscador">
					<div class="titulo tituloSecundario"></div>
					<span>
						<i class="fa fa-search" aria-hidden="true"></i>
						<input type="text" name="searchList" id="searchList" list="citasP" class="buscador" placeholder="Buscar . . .">
					</span>
				</div>

				<div id="showResults">
					<table class="tableList">
						<thead>
							<tr>
								<th class="estado">&nbsp</th>
								<th class="columnaCorta">Fecha de Cita</th>
								<th colspan="2">Paciente</th>
							<?php if($sessionRol==1||$sessionRol==3){ ?><th>Sucursal</th><?php } ?>
							<?php if($sessionRol!=3){ ?><th colspan="2">Doctor</th><?php } ?>
								<th>Tratamiento</th>
								<th class="columnaTCita">&nbsp</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while($citasRow = $citasSql->fetch_assoc()){
								
								if($citasRow['ct_estado']==0){ $estadoCita = 'estadoNeutro'; }
								elseif($citasRow['ct_estado']==1){ $estadoCita = 'estadoConfirmacion'; }
								else { $estadoCita = 'estadoCancelado'; }

								$pacienteUrl = str_replace(" ","-", $citasRow['pc_nombres']);

								$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].' '.$citasRow['ct_horaCita'];

								$estadoEvolucion = '';
								if($citasRow['ct_asistencia']==0){ $estadoEvolucion = 'iconGray';}

								if($citasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
								} else { $iSC = ''; $cSC = ''; }

								if($citasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
								} else { $iDC = ''; $cDC = ''; }

								if($citasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
								} else { $iTR = ''; $cTR = ''; }

								if($citasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
							    else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

							?>
							<tr>
								<td class="estado <?php echo $estadoCita ?>"></td>
							    <td class="columnaCorta"><?php echo $fechaCita ?></td>
							    <td class="imgUser">
							    	<?php
							    	if($citasRow['pc_foto']!=''){ echo "<img src='$citasRow[pc_foto]'>"; }
							    	else { echo '<i class="fa fa-user-circle " aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td><a id="<?php echo $citasRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasRow['pc_nombres']; ?></a></td>
							<?php if($sessionRol==1||$sessionRol==3){ ?>
								<td class="<?php echo $cSC ?>"><?php echo $iSC.$citasRow['sc_nombre'] ?></td><?php } ?>
							<?php if($sessionRol!=3){ ?>
								<td class="imgUser <?php echo $cDC ?>">
									<?php
							    	if($citasRow['dc_foto']!=''){ echo "<img src='$citasRow[dc_foto]'>"; }
							    	else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
							    	?>
							    </td>
							    <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasRow['dc_nombres']; ?></td><?php } ?>
							    <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasRow['tr_nombre']; ?></td>
							    <td class="columnaTCita"><?php echo $tipoCita ?></td>
							    <td class="tableOption">
							    <?php if($citasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
							    	<a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $citasRow["IDCita"] ?>&id=<?php echo $citasRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
							    	<a title="Cancelar Cita" id="<?php echo $citasRow['IDCita'] ?>" t="cita" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
							    <?php } else { 
							    ?>
							    	<a title="Evolucionar" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
							    <?php } ?>
							    </td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php $paginacion->render();?>
				</div>
			</div>



			<div id="content-2">
				contenido 2
			</div>

		</div>

	</div>

		
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<script type="text/javascript">
		$(document).on('click', '.consultorioEliminar', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosT = $(this).attr("t");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"desactivar.php",
		            method:"POST",  
		            data:{id:consultoriosId,t:consultoriosT},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

		$(document).on('click', '.consultoriosEvolucion', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosEv = 1;
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"cita-evolucionar.php",
		            method:"POST",  
		            data:{id:consultoriosId,ev:consultoriosEv},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});

		$(document).on('click', '.guardarMedicamento', function(){  
			var valVadecum = $('#vadecum').val();
			var cantMedicamento = $('#cantidadMedicamento').val();
			var medicamentoCitaID = $('#medicamentoCitaID').val();
		    if(valVadecum != 0 && cantMedicamento>0)
		    {
		    	$.ajax({
		        	url:"cita-medicamentos-guardar.php",
			        method:"POST",
		            data:{valVadecum:valVadecum,cantMedicamento:cantMedicamento,citaID:medicamentoCitaID}, 
			        success:function(data){  
						$('#listMedicamentos').html(data);
					}
			    });  
			}   
		});

		$(document).on('click', '.consultorioEditarPaciente', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"paciente.php",
			        method:"POST",
		            data:{id:consultoriosId}, 
			        success:function(data){  
						$('.contenedorPrincipal').html(data);  
						//$('#consultoriosModal').modal('show');  
					}
			    });  
			}         
		});
	</script>
</body>
</html>