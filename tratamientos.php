<?php include'config.php'; include'pagination-modal-params.php';

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

		<div class="contenedorTabs">
			<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Tratamientos</label>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Combos</label>
			<input id="tab-3" type="radio" name="tab-group" />
			<label for="tab-3" class="labelTab">Fases</label>

			<div class="contenidoTab">
				<div id="content-1">
					<?php $tratamientosQuery = "SELECT * FROM tratamientos INNER JOIN fases ON tratamientos.tr_idFase = fases.IDFase INNER JOIN cups ON tratamientos.tr_idCups = cups.IDCups WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' AND tr_combo='0' ORDER BY tr_nombre";

					$rowCountTratamientos = $con->query($tratamientosQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCountTratamientos,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationTratamientos'
						);
					    $pagination =  new Pagination($pagConfig);

					$tratamientosSql = $con->query($tratamientosQuery." LIMIT $numeroResultados");

					?>
					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a class="consultorioNuevo" data-page="tratamiento"><?php echo $iconoNuevo ?>Nuevo Tratamiento</a></div>
						<span>
							<i class="fa fa-search" aria-hidden="true"></i>
							<input type="text" id="searchTratamientos" list="tratamientos" class="buscador" placeholder="Buscar . . ." onkeyup="paginationTratamientos();">
						</span>
					</div>

					<div id="showResultsTratamientos">
						<table class="tableList">
							<thead>
								<tr>
									<th>Precio</th>
									<th>CUP</th>
									<th>Fase</th>
									<th>Tratamiento</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($tratamientosRow = $tratamientosSql->fetch_assoc()){ ?>
								<tr>
								    <td align="right" class="columnaCorta"><?php echo '$'.number_format($tratamientosRow['tr_precio'], 0, ".", ","); ?></td>
								    <td align="center" class="columnaCorta"><?php echo $tratamientosRow['cup_codigo'] ?></td>
								    <td><?php echo $tratamientosRow['fs_nombre'] ?></td>
								    <td><a id="<?php echo $tratamientosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="tratamiento"><?php echo $tratamientosRow['tr_nombre']; ?></a></td>
								    <td class="tableOption">
								    	<a title="Editar" id="<?php echo $tratamientosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="tratamiento"><?php echo $iconoEditar ?></a>
								    	<a id="<?php echo $tratamientosRow['IDTratamiento'] ?>" t="tratamiento" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
								    </td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>

				<div id="content-2">
					<?php
					$combosQuery = "SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' AND tr_combo='1' ORDER BY tr_nombre";

					$rowCountCombos = $con->query($combosQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCountCombos,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationCombos'
						);
					    $pagination =  new Pagination($pagConfig);

					$combosSql = $con->query($combosQuery." LIMIT $numeroResultados");
					?>
					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a class="consultorioNuevo" data-page="combo"><?php echo $iconoNuevo ?>Nuevo Combo</a></div>
						<span>
							<i class="fa fa-search" aria-hidden="true"></i>
							<input type="text" id="searchCombos" list="combos" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCombos();">
						</span>
					</div>
					<div id="showResultsCombos">
						<table class="tableList">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($combosRow = $combosSql->fetch_assoc()){ ?>
								<tr>
								    <td><a id="<?php echo $combosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="combo"><?php echo $combosRow['tr_nombre'] ?></a></td>
								    <td class="tableOption">
								    	<a title="Editar" id="<?php echo $combosRow['IDTratamiento'] ?>" class="consultorioEditar" data-page="combo"><?php echo $iconoEditar ?></a>
								    	<a id="<?php echo $combosRow['IDTratamiento'] ?>" t="combo" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
								    </td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
				</div>


				<div id="content-3">
					<?php $fasesQuery = "SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica) ORDER BY fs_idClinica DESC, fs_nombre ASC";

					$rowCount = $con->query($fasesQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCount,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationFases'
						);
					    $pagination =  new Pagination($pagConfig);

					$fasesSql = $con->query($fasesQuery." LIMIT $numeroResultados");
					?>

					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a class="consultorioNuevo" data-page="fase"><?php echo $iconoNuevo ?>Nueva Fase</a></div>
					</div>

					<div id="showResultsFases">
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
								    		<a title="Editar" id="<?php echo $fasesRow['IDFase'] ?>" class="consultorioEditar" data-page="fase"><?php echo $iconoEditar ?></a>
								    	<?php } ?>
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
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<script type="text/javascript">
		function paginationTratamientos(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchTratamientos').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/tratamientosData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsTratamientos').html(html);
		        }
		    });
		}

		function paginationCombos(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchCombos').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/combosData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsCombos').html(html);
		        }
		    });
		}

		function paginationFases(page_num) {
		    page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/fasesData.php',
		        data:'page='+page_num,
		        success: function (html) {
		            $('#showResultsFases').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){
		var consultoriosPage = $(this).attr("data-page");   
	    	$.ajax({
	        	url: consultoriosPage+".php",  
		        method:"POST", 
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');  
				}
		    });
		});
		$(document).on('click', '.consultorioEditar', function(){  
			var consultoriosId = $(this).attr("id");
			var consultoriosPage = $(this).attr("data-page");
		    if(consultoriosId != '')
		    {   
		    	$.ajax({
		        	url: consultoriosPage+".php",
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

		$(document).on('click', '.guardarTratamientoCombo', function(){  
			var valTratamientoCombo = $('#combo-tratamiento').val();
			var IDCombo = $(this).attr("data-combo");
			var valTratamientoPrecio = $('#combo-trata-precio').val();
		    if(valTratamientoCombo != 0 && IDCombo != 0 && valTratamientoPrecio>=0)
		    {   
		    	$.ajax({
		        	url:"combo-tratamiento-guardar.php",
			        method:"POST",
		            data:{tratamiento:valTratamientoCombo,IDCombo:IDCombo,precio:valTratamientoPrecio}, 
			        success:function(data){  
						$('#showResultsComboTratamientos').html(data);
					}
			    });  
			}         
		});

		$(document).on('click', '.eliminarTratamientoCombo', function(){  
			var consultoriosComboTrataId = $(this).attr("id");
			var consultoriosComboId = $(this).attr("data-combo");
		    if(consultoriosComboTrataId > 0 && consultoriosComboId > 0)
		    {  
		    	$.ajax({
		        	url:"combo-tratamiento-eliminar.php",
		            method:"POST",  
		            data:{id:consultoriosComboTrataId,IDCombo:consultoriosComboId},  
		            success:function(data){  
						$('#showResultsComboTratamientos').html(data); 
					}
		    	});
			}            
		});
	</script>
</body>
</html>