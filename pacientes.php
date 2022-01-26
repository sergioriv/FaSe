<?php include'config.php'; include'pagination-modal-params.php';

$pacientesQuery = "SELECT * FROM pacientes WHERE pc_idClinica='$sessionClinica' AND pc_estado='1' ORDER BY pc_nombres";

$rowCount = $con->query($pacientesQuery)->num_rows;

	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationPacientes'
	);
	$pagination =  new Pagination($pagConfig);

$pacientesSql = $con->query($pacientesQuery." LIMIT $numeroResultados");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php' ?>
	<?php include'footer.php'; ?>

	<script type="text/javascript">
		function paginationPacientes(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchPacientes').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/pacientesData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){
			<?php if($rowCount < $clinicaRow['cl_cantPacientes']){ ?> 
	    	$.ajax({
	        	url:"paciente.php",  
		        method:"POST", 
		        success:function(data){  
					$('.contenedorPrincipal').html(data); 
					//$('#consultoriosModal').modal('show');  
				}
		    });
		    <?php } else { ?>
		   		$('.contenedorAlerta').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Llegó al limite de registros posibles para Pacientes.</div><div class="close">&times;</div></label>');
		   	<?php } ?>
		});
		
		$(document).on('click', '.consultorioEditar', function(){  
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

	</script>
</head>
<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">
				<span class="cantRegistros" id="countPacientes">[<?php echo $rowCount ?>]</span>
				Pacientes
				<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Paciente</a>
				<a class="consultorioDescargar" data-page="pacientes" data-search="searchPacientes"><i class="fa fa-download"></i>Descargar</a>
			</div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchPacientes" list="pacientes" class="buscador" placeholder="Buscar . . ." onkeyup="paginationPacientes();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th colspan="2">Paciente</th>
						<th>Identificación</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($pacientesRow = $pacientesSql->fetch_assoc()){

						$tipoIdentiRow = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$pacientesRow[pc_idIdentificacion]'")->fetch_assoc();

						$pacienteUrl = str_replace(" ","-", $pacientesRow['pc_nombres']);

						if($pacientesRow['pc_telefonoCelular']>0){
                            $pacienteTelefono = $pacientesRow['pc_telefonoCelular'];
                        } else {
                            $pacienteTelefono = $pacientesRow['pc_telefonoFijo'];
                        }
					?>
					<tr>
					    <td class="imgUser">
					    	<?php
					    	if($pacientesRow['pc_foto']!=''){ echo "<img src='$pacientesRow[pc_foto]'>"; }
					    	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
					    	?>
					    </td>
					    <td><a title="<?php echo $pacienteUrl ?>" id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioEditar"><?php echo $pacientesRow['pc_nombres'] ?></a>
					    </td>
					    <td><?php echo $tipoIdentiRow['ti_nombre'].' '.$pacientesRow['pc_identificacion']; ?></td>
					    <td><?php echo $pacienteTelefono; ?></td>
					    <td><?php echo $pacientesRow['pc_correo']; ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a title="Nueva Cita" onClick="location.href='cita?id=<?php echo $pacientesRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></a>
					    	<a title="Eliminar" id="<?php echo $pacientesRow['IDPaciente'] ?>" t="paciente" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					    </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php echo $pagination->createLinks(); ?>
		</div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	
</body>
</html>