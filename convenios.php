<?php include'config.php'; include'pagination-modal-params.php';

$conveniosQuery = "SELECT * FROM convenios WHERE cnv_idClinica='$sessionClinica' AND cnv_estado='1' ORDER BY cnv_nombre";

$rowCount = $con->query($conveniosQuery)->num_rows;
	$pagConfig = array(
		'totalRows' => $rowCount,
	    'perPage' => $numeroResultados,
		'link_func' => 'paginationConvenios'
	);
    $pagination =  new Pagination($pagConfig);

$conveniosSql = $con->query($conveniosQuery." LIMIT $numeroResultados");

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
			<div class="titulo tituloSecundario">Convenios<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Convenio</a></div>
			<span>
				<i class="fa fa-search" aria-hidden="true"></i>
				<input type="text" id="searchConvenio" list="convenios" class="buscador" placeholder="Buscar . . ." onkeyup="paginationConvenios();">
			</span>
		</div>

		<div id="showResults">
			<table class="tableList">
				<thead>
					<tr>
						<th>Creador</th>
						<th>Nombre</th>
						<th>%</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($conveniosRow = $conveniosSql->fetch_assoc()){

						$creadorConvenioSql = $con->query("SELECT us_idRol, us_id FROM usuarios WHERE IDUsuario = '$conveniosRow[cnv_idUsuario]'")->fetch_assoc();

						$IDusuarioConvenio = $creadorConvenioSql['us_id'];
						$nombreUsuarioConvenio = '';
					    if($creadorConvenioSql['us_idRol']==1){
					   		$usuarioConvenio = $con->query("SELECT cl_nombre FROM clinicas WHERE IDClinica='$IDusuarioConvenio'")->fetch_assoc();
					   		$nombreUsuarioConvenio = $usuarioConvenio['cl_nombre'];

					    } elseif($creadorConvenioSql['us_idRol']==2){
					   		$usuarioConvenio = $con->query("SELECT sc_nombre FROM sucursales WHERE IDSucursal='$IDusuarioConvenio'")->fetch_assoc();
					   		$nombreUsuarioConvenio = $usuarioConvenio['sc_nombre'];

					    } elseif($creadorConvenioSql['us_idRol']==3){
					   		$usuarioConvenio = $con->query("SELECT dc_nombres FROM doctores WHERE IDDoctor='$IDusuarioConvenio'")->fetch_assoc();
					   		$nombreUsuarioConvenio = $usuarioConvenio['dc_nombres'];
					    }
					?>
					<tr>
					    <td><?php echo $nombreUsuarioConvenio ?></td>
					    <td><?php echo $conveniosRow['cnv_nombre']; ?></td>
					    <td align="center"><?php echo $conveniosRow['cnv_descuento']; ?></td>
					    <td class="tableOption">
					    	<a id="<?php echo $conveniosRow['IDConvenio'] ?>" t="convenio" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
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
		function paginationConvenios(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchConvenio').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/conveniosData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResults').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"convenio.php",  
		        method:"POST", 
		        success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show');  
				}
		    });
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