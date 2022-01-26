<?php include'config.php'; include'pagination-modal-params.php';

$combosQuery = "SELECT * FROM combos WHERE cb_idClinica='$sessionClinica' AND cb_estado='1' ORDER BY cb_nombre";

$rowCount = $con->query($combosQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationCombos'
	);
    $pagination =  new Pagination($pagConfig);

$combosSql = $con->query($combosQuery." LIMIT $numeroResultados");
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
			<div class="titulo tituloSecundario">Combos tratamientos<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Combo</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchCombos" list="combos" class="buscador" placeholder="Buscar . . ." onkeyup="paginationCombos();">
			</span>
		</div>

		<div id="showResultsCombos">
			<table class="tableList">
				<thead>
					<tr>
						<th class="columnaCorta">Precio</th>
						<th>Nombre</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($combosRow = $combosSql->fetch_assoc()){ ?>
					<tr>
					    <td align="right" class="columnaCorta"><?php echo '$'.number_format($combosRow['cb_precio'], 0, ".", ","); ?></td>
					    <td><a id="<?php echo $combosRow['IDCombo'] ?>" class="consultorioEditar"><?php echo $combosRow['cb_nombre'] ?></a></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $combosRow['IDCombo'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a id="<?php echo $combosRow['IDCombo'] ?>" t="combo" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"combo.php",  
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
		        	url:"combo.php",
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

		
	</script>
</body>
</html>