<?php include'config.php'; include'pagination-modal-params.php';

$sucursalesQuery = "SELECT * FROM sucursales WHERE sc_idClinica='$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre";

$rowCount = $con->query($sucursalesQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationSucursales'
	);
    $pagination =  new Pagination($pagConfig);

$sucursalesSql = $con->query($sucursalesQuery." LIMIT $numeroResultados");

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
			<div class="titulo tituloSecundario"><span class="cantRegistros" id="countSucursales">[<?php echo $rowCount ?>]</span>Sucursales<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nueva Sucursal</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchSucursales" list="sucursales" class="buscador" placeholder="Buscar . . ." onkeyup="paginationSucursales();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th>Sucursal</th>
						<th>Unid.</th>
						<th>Teléfono</th>
						<th>Ciudad</th>
						<th>Dirección</th>
						<th>Email</th>
						<th>Horario</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($sucursalesRow = $sucursalesSql->fetch_assoc()){
						$ciudadRow = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$sucursalesRow[sc_idCiudad]'")->fetch_assoc();

						$cantidadUnidades = $con->query("SELECT COUNT(*) AS cantidad FROM unidadesodontologicas WHERE uo_idSucursal = '$sucursalesRow[IDSucursal]' ")->fetch_assoc();
					?>
					<tr>
					    <td><a id="<?php echo $sucursalesRow['IDSucursal'] ?>" class="consultorioEditar"><?php echo $sucursalesRow['sc_nombre']; ?></a></td>
					    <td align="center"><?php echo $cantidadUnidades['cantidad']; ?></td>
					    <td><?php echo $sucursalesRow['sc_telefonoFijo']; ?></td>
					    <td><?php echo $ciudadRow['cd_nombre'] ?></td>
					    <td><?php echo $sucursalesRow['sc_direccion']; ?></td>
					    <td><?php echo $sucursalesRow['sc_correo']; ?></td>
					    <td class="centro"><?php echo $sucursalesRow['sc_atencionDe'].' / '.$sucursalesRow['sc_atencionHasta']; ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $sucursalesRow['IDSucursal'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a id="<?php echo $sucursalesRow['IDSucursal'] ?>" t="sucursal" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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
		function paginationSucursales(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchSucursales').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/sucursalesData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){
			<?php if($rowCount < $clinicaRow['cl_cantSucursales']){ ?>   
	    	$.ajax({
	        	url:"sucursal.php",  
		        method:"POST", 
		        success:function(data){  
					$('.contenedorPrincipal').html(data); 
					//$('#consultoriosModal').modal('show');  
				}
		    });
		    <?php } else { ?>
		   		$('.contenedorAlerta').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Llegó al limite de registros posibles para Sucursales.</div><div class="close">&times;</div></label>');
		   	<?php } ?>
		});
		$(document).on('click', '.consultorioEditar', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"sucursal.php",
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
</body>
</html>