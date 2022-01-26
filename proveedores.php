<?php include'config.php'; include'pagination-modal-params.php';

$proveedoresQuery = "SELECT * FROM proveedores WHERE pr_idClinica='$sessionClinica' AND pr_estado='1' ORDER BY pr_nombre";

$rowCount = $con->query($proveedoresQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationProveedores'
	);
    $pagination =  new Pagination($pagConfig);

$proveedoresSql = $con->query($proveedoresQuery." LIMIT $numeroResultados");

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
			<div class="titulo tituloSecundario">Proveedores<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Proveedor</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchProveedores" list="proveedores" class="buscador" placeholder="Buscar . . ." onkeyup="paginationProveedores();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th>Proveedor</th>
						<th>NIT</th>
						<th>Teléfono</th>
						<th>Ciudad</th>
						<th>Dirección</th>
						<th>Email</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($proveedoresRow = $proveedoresSql->fetch_assoc()){
						$ciudadRow = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$proveedoresRow[pr_idCiudad]'")->fetch_assoc();
					?>
					<tr>
					    <td><a id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioEditar"><?php echo $proveedoresRow['pr_nombre']; ?></a></td>
					    <td><?php echo $proveedoresRow['pr_nit']; ?></td>
					    <td><?php echo $proveedoresRow['pr_telefonoFijo']; ?></td>
					    <td><?php echo $ciudadRow['cd_nombre'] ?></td>
					    <td><?php echo $proveedoresRow['pr_direccion']; ?></td>
					    <td><?php echo $proveedoresRow['pr_correo']; ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a title="Orden de Entrada" id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioOrdenEntrada"><i class="fa fa-file-o" aria-hidden="true"></i></a>
					    	<a title="Eliminar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" t="proveedor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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
		function paginationProveedores(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchProveedores').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/proveedoresData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"proveedor.php",  
		        method:"POST", 
		        success:function(data){  
					$('.contenedorPrincipal').html(data);  
				}
		    });
		});
		$(document).on('click', '.consultorioEditar', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"proveedor.php",
			        method:"POST",
		            data:{id:consultoriosId}, 
			        success:function(data){  
						$('.contenedorPrincipal').html(data);  
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

		$(document).on('click', '.consultorioOrdenEntrada', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"orden-entrada.php",
		            method:"POST",  
		            data:{id:consultoriosId},  
		            success:function(data){  
						$('.contenedorPrincipal').html(data);  
					}
		    	});  
			}            
		});

		$(document).on('click', '.consultorioSalida', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"material-salida.php",  
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