<?php include'config.php'; include'pagination-modal-params.php';

$doctoresQuery = "SELECT * FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado='1' ORDER BY dc_nombres";

$rowCount = $con->query($doctoresQuery)->num_rows;

	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationDoctores'
	);
	$pagination =  new Pagination($pagConfig);

$doctoresSql = $con->query($doctoresQuery." LIMIT $numeroResultados");

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>
<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">
				<span class="cantRegistros" id="countDoctores">[<?php echo $rowCount ?>]</span>
				Doctores
				<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Doctor</a>
				<a class="consultorioDescargar" data-page="doctores" data-search="searchDoctores"><i class="fa fa-download"></i>Descargar</a>
			</div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchDoctores" list="doctores" class="buscador" placeholder="Buscar . . ." onkeyup="paginationDoctores();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th colspan="2">Doctor</th>
						<th>Identificación</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Horario</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($doctoresRow = $doctoresSql->fetch_assoc()){
						$tipoIdentiRow = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$doctoresRow[dc_idIdentificacion]'")->fetch_assoc();
					?>
					<tr>
					    <td class="imgUser">
							<?php
					    	if($doctoresRow['dc_foto']!=''){ echo "<img src='$doctoresRow[dc_foto]'>"; }
					    	else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
					    	?>
					    </td>
					    <td><a id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioEditar"><?php echo $doctoresRow['dc_nombres']; ?></a></td>
					    <td><?php echo $tipoIdentiRow['ti_nombre'].' '.$doctoresRow['dc_identificacion']; ?></td>
					    <td><?php echo $doctoresRow['dc_telefonoCelular']; ?></td>
					    <td><?php echo $doctoresRow['dc_correo']; ?></td>
						<td class="centro"><?php echo $doctoresRow['dc_atencionDe'].' / '.$doctoresRow['dc_atencionHasta']; ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a title="Horario" id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioHorario"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></a>
					    	<a title="Eliminar" id="<?php echo $doctoresRow['IDDoctor'] ?>" t="doctor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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

	<script type="text/javascript">
		function paginationDoctores(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchDoctores').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/doctoresData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){
			<?php if($rowCount < $clinicaRow['cl_cantDoctores']){ ?>
	    	$.ajax({
	        	url:"doctor.php",  
		        method:"POST", 
		        success:function(data){  
					$('.contenedorPrincipal').html(data); 
					//$('#consultoriosModal').modal('show');  
				}
		    });
		   	<?php } else { ?>
		   		$('.contenedorAlerta').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Llegó al limite de registros posibles para Doctores.</div><div class="close">&times;</div></label>');
		   	<?php } ?>
		});
		/*
		$(document).on('click', '.consultorioVer', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"doctor-ver.php",
			        method:"POST",
		            data:{id:consultoriosId}, 
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
			    });  
			}         
		});*/
		$(document).on('click', '.consultorioEditar', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"doctor.php",
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
		$(document).on('click', '.consultorioHorario', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"doctor-horario.php",  
		            method:"POST",  
		            data:{id:consultoriosId},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});
	</script>
</body>
</html>