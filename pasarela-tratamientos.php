<?php include'config.php';
$tratamientosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' ORDER BY tr_nombre");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-pasarela.php'; ?>
</head>

<style type="text/css">
.carga .proceso {
  width: 49.998%;
}
</style>

<body>
	<div class="contenedorPrincipal">
		<div class="contenedorProceso">
			<a class="boton boton-secundario" onclick="location.href='pasarela-sucursales'">Anterior</a>
			<div class="carga"><div class="proceso"></div></div>
			<a class="boton boton-primario" onclick="location.href='pasarela-proveedores'">Siguiente</a>
		</div>
		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Tratamientos<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Tratamiento</a></div>
		</div>

		<div id="showResults">
			<table class="tableList">
				<tbody>
					<?php while($tratamientosRow = $tratamientosSql->fetch_assoc()){ ?>
					<tr>
					    <td align="right" class="columnaCorta"><?php echo '$'.number_format($tratamientosRow['tr_precio'], 0, ".", ","); ?></td>
					    <td align="right"><?php echo $tratamientosRow['tr_codigo']; ?></td>
					    <td><?php echo $tratamientosRow['tr_nombre']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="contenedorFooter"><?php include'pasarela-footer.php' ?></div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<script src="js/jquery-2-2-0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>

	<script type="text/javascript">
		$(document).on('click', '.consultorioNuevo', function(){   
	    	$.ajax({
	        	url:"tratamiento.php",  
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