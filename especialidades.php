<?php include'config.php'; include'pagination-modal-params.php';

$especialidadesQuery = "SELECT * FROM especialidades WHERE esp_idClinica='$sessionClinica' AND esp_estado='1' ORDER BY esp_nombre";

 $rowCount = $con->query($especialidadesQuery)->num_rows;
 	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationEspecialidades'
	);
    $pagination =  new Pagination($pagConfig);

$especialidadesSql = $con->query($especialidadesQuery." LIMIT $numeroResultados");
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
			<div class="titulo tituloSecundario">Especialidades<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nueva Especialidad</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchEspecialidades" list="especialidades" class="buscador" placeholder="Buscar . . ." onkeyup="paginationEspecialidades();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($especialidadesRow = $especialidadesSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $especialidadesRow['esp_nombre'] ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $especialidadesRow['IDEspecialidad'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a title="Eliminar" id="<?php echo $especialidadesRow['IDEspecialidad'] ?>" t="especialidad" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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
		function paginationEspecialidades(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchEspecialidades').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/especialidadesData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"especialidad.php",  
		        method:"POST", 
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');  
				}
		    });
		});
		$(document).on('click', '.consultorioEditar', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"especialidad.php",
			        method:"POST",
		            data:{id:consultoriosId}, 
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
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
</body>
</html>