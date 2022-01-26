<?php include'config.php';
$proveedoresSql = $con->query("SELECT * FROM proveedores WHERE pr_idClinica='$sessionClinica' AND pr_estado='1' ORDER BY pr_nombre");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-pasarela.php'; ?>
</head>

<style type="text/css">
.carga .proceso {
  width: 66.664%;
}
</style>

<body>
	<div class="contenedorPrincipal">
		<div class="contenedorProceso">
			<a class="boton boton-secundario" onclick="location.href='pasarela-tratamientos'">Anterior</a>
			<div class="carga"><div class="proceso"></div></div>
			<a class="boton boton-primario" onclick="location.href='pasarela-ciudades'">Siguiente</a>
		</div>
		<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Proveedores<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nuevo Proveedor</a></div>
		</div>

		<div id="showResults">
			<table class="tableList">
				<tbody>
					<?php while($proveedoresRow = $proveedoresSql->fetch_assoc()){
						$ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$proveedoresRow[pr_idCiudad]'");
						$ciudadRow = $ciudadSql->fetch_assoc();
					?>
					<tr>
					    <td><?php echo $proveedoresRow['pr_nombre']; ?></td>
					    <td><?php echo $proveedoresRow['pr_nit']; ?></td>
					    <td><?php echo $proveedoresRow['pr_telefonoFijo']; ?></td>
					    <td><?php echo $ciudadRow['cd_nombre'] ?></td>
					    <td><?php echo $proveedoresRow['pr_direccion']; ?></td>
					    <td><?php echo $proveedoresRow['pr_correo']; ?></td>
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
	        	url:"proveedor.php",  
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