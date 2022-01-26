<?php include'config.php'; include'pagination-modal-params.php';

$fasesQuery = "SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica) ORDER BY fs_idClinica DESC, fs_nombre ASC";

$rowCount = $con->query($fasesQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationFases'
	);
    $pagination =  new Pagination($pagConfig);

$fasesSql = $con->query($fasesQuery." LIMIT $numeroResultados");

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
			<div class="titulo tituloSecundario">Fases<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nueva Fase</a></div>
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
					<?php while($fasesRow = $fasesSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $fasesRow['fs_nombre']; ?></td>
					    <td class="tableOption">
					    	<?php if( $fasesRow['fs_idClinica']==$sessionClinica ){ ?>
					    		<a title="Editar" id="<?php echo $fasesRow['IDFase'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<?php } ?>
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
		function paginationFases(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/fasesData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"fase.php",  
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
		        	url:"fase.php",
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