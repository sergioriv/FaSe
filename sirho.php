<?php include'config.php'; include'pagination-modal-params.php';

$sirhoQuery = "SELECT * FROM sirhoclinica, sirho, sirhocategorias WHERE sirhoclinica.shcl_idSirho = sirho.IDSirho AND sirho.sh_idCategoria = sirhocategorias.IDSirhoCategoria AND sirhoclinica.shcl_idClinica='$sessionClinica' AND sirhoclinica.shcl_estado='1' ORDER BY sirhoclinica.IDSirhoClinica DESC";

$rowCount = $con->query($sirhoQuery)->num_rows;

	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationSirho'
	);
	$pagination =  new Pagination($pagConfig);

$sirhoSql = $con->query($sirhoQuery." LIMIT $numeroResultados");

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
			<div class="titulo tituloSecundario">Sirho<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo</a><a class="reporteSirho"><i class="fa fa-download" aria-hidden="true"></i>Descargar reporte</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchSirho" list="sirho" class="buscador" placeholder="Buscar . . ." onkeyup="paginationSirho();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th class="columnaCorta">Fecha</th>
						<th>Categoria</th>
						<th>Sirho</th>
						<th>Cant. (Kg)</th>
					</tr>
				</thead>
				<tbody>
					<?php while($sirhoRow = $sirhoSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $sirhoRow['shcl_fechaCreacion'] ?></td>
					    <td><?php echo $sirhoRow['shcg_nombre'] ?></td>
					    <td><?php echo $sirhoRow['sh_nombre'] ?></td>
					    <td class="centro"><?php echo number_format($sirhoRow['shcl_cantidad'], 0, ".", ","); ?></td>
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
		function paginationSirho(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchSirho').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/sirhoData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){
	    	$.ajax({
	        	url:"sirhoNuevo.php",  
		        method:"POST", 
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');
				}
		    });
		});

		$(document).on('click', '.reporteSirho', function(){
	    	$.ajax({
	        	url:"reporte-sirho.php",  
		        method:"POST", 
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');
				}
		    });
		});

	</script>
</body>
</html>