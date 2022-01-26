<?php include'config.php'; include'pagination-modal-params.php';

$vendedoresQuery = "SELECT * FROM vendedores WHERE vn_idClinica='$sessionClinica' AND vn_estado='1' ORDER BY vn_nombre ASC ";

 $rowCount = $con->query($vendedoresQuery)->num_rows;
 	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationVendedores'
	);
    $pagination =  new Pagination($pagConfig);

$vendedoresSql = $con->query($vendedoresQuery." LIMIT $numeroResultados");
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
			<div class="titulo tituloSecundario">Vendedores<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Vendedor</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchVendedores" list="vendedores" class="buscador" placeholder="Buscar . . ." onkeyup="paginationVendedores();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Teléfono</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($vendedoresRow = $vendedoresSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $vendedoresRow['vn_nombre'] ?></td>
					    <td><?php echo $vendedoresRow['vn_telefono'] ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $vendedoresRow['IDVendedor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a title="Eliminar" id="<?php echo $vendedoresRow['IDVendedor'] ?>" t="vendedor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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
		function paginationVendedores(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchVendedores').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/vendedoresData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"vendedor.php",  
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
		        	url:"vendedor.php",
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