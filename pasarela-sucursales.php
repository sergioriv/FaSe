<?php include'config.php';
$sucursalesSql = $con->query("SELECT * FROM sucursales WHERE sc_idClinica='$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");

$sucursalesNum = $sucursalesSql->num_rows;
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-pasarela.php'; ?>
</head>

<style type="text/css">
.carga .proceso {
  width: 33.332%;
}
</style>

<body>
	<div class="contenedorPrincipal">
		<div class="contenedorProceso">
			<a class="boton boton-secundario" onclick="location.href='pasarela-logo'">Anterior</a>
			<div class="carga"><div class="proceso"></div></div>
			<?php if($sucursalesNum >= 1 ){	?>
				<a class="boton boton-primario" onclick="location.href='pasarela-tratamientos'">Siguiente</a>
			<?php } else { ?>
				<a class="boton boton-gray">Siguiente</a>
			<?php } ?>
		</div>
		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Sucursales<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nueva Sucursal</a></div>
		</div>

		<div id="showResults">
			<table class="tableList">
				<tbody>
					<?php while($sucursalesRow = $sucursalesSql->fetch_assoc()){
						$ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$sucursalesRow[sc_idCiudad]'");
						$ciudadRow = $ciudadSql->fetch_assoc();
					?>
					<tr>
					    <td><?php echo $sucursalesRow['sc_nombre']; ?></td>
					    <td><?php echo $sucursalesRow['sc_telefonoFijo']; ?></td>
					    <td><?php echo $ciudadRow['cd_nombre'] ?></td>
					    <td><?php echo $sucursalesRow['sc_direccion']; ?></td>
					    <td><?php echo $sucursalesRow['sc_correo']; ?></td>
					    <td><?php echo $sucursalesRow['sc_atencionDe'].' / '.$sucursalesRow['sc_atencionHasta']; ?></td>
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
	        	url:"sucursal.php",  
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