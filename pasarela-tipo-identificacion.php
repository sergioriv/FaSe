<?php include'config.php';
$tiposIdentiSql = $con->query("SELECT * FROM tiposidentificacion WHERE ti_idClinica='$sessionClinica' AND ti_estado='1' ORDER BY ti_nombre");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-pasarela.php'; ?>
</head>

<style type="text/css">
.carga .proceso {
  width: 100%;
}
</style>

<body>
	<div class="contenedorPrincipal">
		<div class="contenedorProceso">
			<a class="boton boton-secundario" onclick="location.href='pasarela-ciudades'">Anterior</a>
			<div class="carga"><div class="proceso"></div></div>
			<a class="boton boton-primario" onclick="location.href='pacientes'">Finalizar</a>
		</div>
		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Tipos de Identificación<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo</a></div>
		</div>

		<div id="showResults">
			<table class="tableList">
				<tbody>
					<?php while($tiposIdentiRow = $tiposIdentiSql->fetch_assoc()){ ?>
					<tr>
					    <td><?php echo $tiposIdentiRow['ti_nombre']; ?></td>
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
	        	url:"tipoIdenti.php",  
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