<?php include'config.php';
$ciudadesSql = $con->query("SELECT * FROM ciudades WHERE cd_idClinica='$sessionClinica' AND cd_estado='1' ORDER BY cd_nombre");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-pasarela.php'; ?>
</head>

<style type="text/css">
.carga .proceso {
  width: 83.330%;
}
</style>

<body>
	<div class="contenedorPrincipal">
		<div class="contenedorProceso">
			<a class="boton boton-secundario" onclick="location.href='pasarela-proveedores'">Anterior</a>
			<div class="carga"><div class="proceso"></div></div>
			<a class="boton boton-primario" onclick="location.href='pasarela-tipo-identificacion'">Siguiente</a>
		</div>
		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Ciudades<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nueva Ciudad</a></div>
		</div>

		<div id="showResults">
			<table class="tableList">
				<tbody>
					<?php while($ciudadesRow = $ciudadesSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $ciudadesRow['cd_nombre']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
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
	        	url:"ciudad.php",  
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