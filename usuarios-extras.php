<?php include'config.php'; include'pagination-modal-params.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>
<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="contenedorTabs">
			<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Usuarios de Citas</label>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Usuarios de Inventario</label>

			<div class="contenidoTab">
				<div id="content-1">
					<?php $userCitasQuery = "SELECT * FROM usuarioscitas, sucursales WHERE usuarioscitas.uc_idSucursal = sucursales.IDSucursal AND usuarioscitas.uc_idClinica='$sessionClinica' AND sucursales.sc_estado='1' AND usuarioscitas.uc_estado='1' ORDER BY usuarioscitas.uc_nombres";

					$rowCount = $con->query($userCitasQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCount,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationUserCitas'
						);
					    $pagination =  new Pagination($pagConfig);

					$userCitasSql = $con->query($userCitasQuery." LIMIT $numeroResultados");
					?>

					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a class="consultorioNuevo" page="usuario-citas"><?php echo $iconoNuevo ?>Nuevo Usuario de Citas</a></div>
						<span>
							<i class="fa fa-search" aria-hidden="true"></i>
							<input type="text" id="searchUserCitas" list="usCitas" class="buscador" placeholder="Buscar . . ." onkeyup="paginationUserCitas();">
						</span>
					</div>

					<div id="showResults_userCitas">
						<table class="tableList">
							<thead>
								<tr>
									<th>Nombres</th>
									<th>Correo</th>
									<th>Sucursal</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($userCitasRow = $userCitasSql->fetch_assoc()){

									if($userCitasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
									} else { $iSC = ''; $cSC = ''; }
								?>
								<tr>
								    <td><?php echo $userCitasRow['uc_nombres']; ?></td>
								    <td><?php echo $userCitasRow['uc_correo']; ?></td>
								    <td class="<?php echo $cSC ?>"><?php echo $iSC.$userCitasRow['sc_nombre']; ?></td>
								    <td class="tableOption">
								    	<a title="Editar" id="<?php echo $userCitasRow['IDUserCitas'] ?>" class="consultorioEditar" page="usuario-citas"><?php echo $iconoEditar ?></a>
								    	<a title="Eliminar" id="<?php echo $userCitasRow['IDUserCitas'] ?>" t="usCitas" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
								    </td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>

				</div>


				<div id="content-2">
					<?php $userInventarioQuery = "SELECT * FROM usuariosinventario, sucursales WHERE usuariosinventario.ui_idSucursal = sucursales.IDSucursal AND usuariosinventario.ui_idClinica='$sessionClinica' AND sucursales.sc_estado='1' AND usuariosinventario.ui_estado='1' ORDER BY usuariosinventario.ui_nombres";

					$rowCount = $con->query($userInventarioQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCount,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationUserInventarios'
						);
					    $pagination =  new Pagination($pagConfig);

					$userInventarioSql = $con->query($userInventarioQuery." LIMIT $numeroResultados");
					?>

					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a class="consultorioNuevo" page="usuario-inventario"><?php echo $iconoNuevo ?>Nuevo Usuario de Inventario</a></div>
						<span>
							<i class="fa fa-search" aria-hidden="true"></i>
							<input type="text" id="searchUserInventarios" list="usInventario" class="buscador" placeholder="Buscar . . ." onkeyup="paginationUserInventarios();">
						</span>
					</div>

					<div id="showResults_userInventario">
						<table class="tableList">
							<thead>
								<tr>
									<th>Nombres</th>
									<th>Correo</th>
									<th>Sucursal</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($userInventarioRow = $userInventarioSql->fetch_assoc()){

									if($userInventarioRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
									} else { $iSC = ''; $cSC = ''; }
								?>
								<tr>
								    <td><?php echo $userInventarioRow['ui_nombres']; ?></td>
								    <td><?php echo $userInventarioRow['ui_correo']; ?></td>
								    <td class="<?php echo $cSC ?>"><?php echo $iSC.$userInventarioRow['sc_nombre']; ?></td>
								    <td class="tableOption">
								    	<a title="Editar" id="<?php echo $userInventarioRow['IDUserInventario'] ?>" class="consultorioEditar" page="usuario-inventario"><?php echo $iconoEditar ?></a>
								    	<a title="Eliminar" id="<?php echo $userInventarioRow['IDUserInventario'] ?>" t="usInventario" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
								    </td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
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
		function paginationUserCitas(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchUserCitas').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/userCitasData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults_userCitas').html(html);
		        }
		    });
		}

		function paginationUserInventarios(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchUserInventarios').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/userInventariosData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults_userInventario').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){
	    	$.ajax({
	        	url:$(this).attr('page'),  
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
		        	url:$(this).attr('page'),
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