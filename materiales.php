<?php include'config.php'; include'pagination-modal-params.php';

	$semaforoRed = date ( 'Y-m-d' , strtotime ( '+30days' , strtotime ( $fechaHoy ) ) ) ;
	$semaforoYellow = date ( 'Y-m-d' , strtotime ( '+90days' , strtotime ( $fechaHoy ) ) ) ;
	$semaforoNeutro = $hoyAno.'-'.$hoyMes.'-'.$hoyDia;

$materialesQuery = "SELECT * FROM materiales WHERE mt_idClinica = '$sessionClinica' AND mt_estado='1' ORDER BY mt_codigo";
/*
if($sessionRol==1){
	$materialesQuery = "SELECT * FROM materiales, sucursales WHERE materiales.mt_idSucursal = sucursales.IDSucursal 
	AND sucursales.sc_idClinica = '$sessionClinica' AND materiales.mt_estado='1' ORDER BY materiales.mt_codigo";
} else if($sessionRol==2){
	$materialesQuery = "SELECT * FROM materiales, sucursales WHERE sucursales.sc_idClinica = '$sessionClinica' AND sucursales.IDSucursal='$sessionUsuario' AND materiales.mt_idSucursal = sucursales.IDSucursal AND materiales.mt_estado='1' ORDER BY materiales.mt_codigo";
} else if($sessionRol==4){

	$usuarioInventario = $con->query("SELECT * FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();

	$materialesQuery = "SELECT * FROM materiales, sucursales WHERE sucursales.sc_idClinica = '$sessionClinica' AND sucursales.IDSucursal='$usuarioInventario[ui_idSucursal]' AND materiales.mt_idSucursal = sucursales.IDSucursal AND materiales.mt_estado='1' ORDER BY materiales.mt_codigo";
}
*/
$rowCount = $con->query($materialesQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationMateriales'
	);
    $pagination =  new Pagination($pagConfig);

$materialesSql = $con->query($materialesQuery." LIMIT $numeroResultados");

	$queryEntradaSession = '';
		if($sessionRol==2){
			$queryEntradaSession = "AND me_idSucursal = '$sessionUsuario'";
		} else if($sessionRol==4){
			$usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
			$queryEntradaSession = "AND me_idSucursal = '$usuarioInventario[ui_idSucursal]'";
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

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Items
				<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Item</a> 
				<a class="consultorioOrdenEntrada"><?php echo $iconoNuevo ?>Nueva Entrada</a>
				<a class="consultorioDescargar" data-page="materiales_inicial"><i class="fa fa-download"></i>Inventario inicial</a>
			</div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchMateriales" list="materiales" class="buscador" placeholder="Buscar . . ." onkeyup="paginationMateriales();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th class="estado"></th>
						<th>Cod.</th>
						<th>Item</th>
						<th>Cant.</th>
						<th>Temp.</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($materialesRow = $materialesSql->fetch_assoc()){
						$cantidadActual = 0;
						$cantEntradas = 0;
						$cantSalidas = 0;

						$entradasSql = $con->query("SELECT IDMatEntrada, me_cantidad FROM materialesentrada WHERE me_idMaterial = '$materialesRow[IDMaterial]' $queryEntradaSession AND me_estado='1'");

						while($entradasRow = $entradasSql->fetch_assoc()){
							$cantEntradas += $entradasRow['me_cantidad'];

							$salidasSql = $con->query("SELECT SUM(ms_cantidad) AS cantSalida FROM materialessalida WHERE ms_idMatEntrada = '$entradasRow[IDMatEntrada]' AND ms_estado='1'")->fetch_assoc();
							$cantSalidas += $salidasSql['cantSalida'];
						}

						$cantidadActual = $cantEntradas - $cantSalidas;

						if($materialesRow['mt_vencimiento'] == 0) { $estadoVendimiento = 'estadoNeutro'; }
						else {
							$querySemaforoNeutro = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_cero = 0 AND me_fechaVencimiento < '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;
							$querySemaforoRed = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_cero = 0 AND me_fechaVencimiento <= '$semaforoRed' AND me_fechaVencimiento >= '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;
							$querySemaforoYellow = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_cero = 0 AND me_fechaVencimiento <= '$semaforoYellow' AND me_fechaVencimiento >= '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;

							if($querySemaforoRed >= 1 ) { $estadoVendimiento = 'semaforoRojo'; }
							else if($querySemaforoYellow >= 1) { $estadoVendimiento = 'semaforoAmarillo'; }
							else if($querySemaforoNeutro >= 1) { $estadoVendimiento = 'estadoNeutro'; }
						}

					?>
					<tr>
						<td class="estado <?php echo $estadoVendimiento ?>">&nbsp</td>
					    <td><?php echo $materialesRow['mt_codigo'] ?></td>
					    <td><a id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEditar"><?php echo $materialesRow['mt_nombre']; ?></a></td>
					    <td class="centro"><?php echo $cantidadActual; ?></td>
					    <td><?php echo $materialesRow['mt_temperatura']; ?></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a title="Eliminar" id="<?php echo $materialesRow['IDMaterial'] ?>" t="material" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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

	<?php  ?>

	<script type="text/javascript">
		function paginationMateriales(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchMateriales').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/materialesData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"material.php",  
		        method:"POST", 
		        success:function(data){  
					$('.contenedorPrincipal').html(data); 
					//$('#consultoriosModal').modal('show');  
				}
		    });
		});
		$(document).on('click', '.consultorioEditar', function(){  
			var consultoriosId = $(this).attr("id"); 
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url:"material.php",
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


		$(document).on('click', '.consultorioOrdenEntrada', function(){  
 
		    	$.ajax({
		        	url:"orden-entrada.php",
		            method:"POST",
		            success:function(data){  
						$('.contenedorPrincipal').html(data);  
					}
		    	});           
		});
/*		$(document).on('click', '.consultorioEntrada', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"material-entrada.php",  
		            method:"POST",  
		            data:{id:consultoriosId},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});
*/		$(document).on('click', '.consultorioSalida', function(){  
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


		$(document).on('click', '.ordenEntradaVer', function(){  
			var ordenID = $(this).attr("data-id"); 
		    if(ordenID != '')
		    {   
		    	$.ajax({
		        	url: "orden-entrada.php",
			        method:"POST",
		            data:{ver:1,ordenID:ordenID}, 
			        success:function(data){
			        	$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
			    });  
			}         
		});
		/*
		$(document).on('click', '.consultorioHistorial', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"material-historial.php",  
		            method:"POST",  
		            data:{id:consultoriosId},  
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
		    	});  
			}            
		});*/
	</script>
</body>
</html>