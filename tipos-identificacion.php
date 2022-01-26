<?php include'config.php'; include'pagination.php';

$tiposIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE ti_idClinica='$sessionClinica' AND ti_estado='1' ORDER BY ti_nombre");

$numeroTiposIdenti = $tiposIdentiSql->num_rows;
$paginacion = new pag();
$paginacion->records($numeroTiposIdenti);
$paginacion->records_per_page($numeroResultados);
$limit = 'LIMIT ' .(($paginacion->get_page() - 1) * $numeroResultados). ',' .$numeroResultados;

$tiposIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE ti_idClinica='$sessionClinica' AND ti_estado='1' ORDER BY ti_nombre $limit");

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php' ?>
</head>
<body>
	<div class="contenedorPrincipal">

		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Tipos de Identificación<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo</a></div>
			<span>
				<!--<i class="fa fa-search" aria-hidden="true"></i>-->
				<input type="hidden" name="searchList" id="searchList" list="" class="buscador" placeholder="Buscar . . .">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<tbody>
					<?php while($tiposIdentiRow = $tiposIdentiSql->fetch_assoc()){ ?>
					<tr>
					    <td><a id="<?php echo $tiposIdentiRow['IDTipoIdentificacion'] ?>" class="consultorioEditar"><?php echo $tiposIdentiRow['ti_nombre']; ?></a></td>
					    <td class="tableOption">
					    	<a title="Editar" id="<?php echo $tiposIdentiRow['IDTipoIdentificacion'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a id="<?php echo $tiposIdentiRow['IDTipoIdentificacion'] ?>" t="tipoIdenti" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					    </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php $paginacion->render();?>
		</div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<?php include'footer.php'; ?>

	<script type="text/javascript">
		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"tipoIdenti.php",  
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
		        	url:"tipoIdenti.php",
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