<?php include'config.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-pasarela.php'; ?>
</head>

<style type="text/css">
.carga .proceso {
  width: 16.666%;
}
</style>

<body>
	<div class="contenedorPrincipal">
		<form method="post" action="pasarela-logoCarga.php" enctype="multipart/form-data">
		<div class="contenedorProceso">
			<a class="boton boton-secundario" onClick="location.href='./'">Login</a>
			<div class="carga"><div class="proceso"></div></div>
				<button class="boton boton-primario">Siguiente</button>
		</div>
		<!--<div class="contenedorAlerta"><?php include'mensajes.php'; ?></div>-->
<!--
		<div class="tituloBuscador">
			<div class="titulo tituloSecundario">Sucursales<a class="consultorioNuevo"><?php echo $iconoNuevo ?>Nueva Sucursal</a></div>
		</div>
-->		<div class="pasarelaLogo">
			<div id="msjPhoto" class="cargaImg" onclick="$('#filePhoto').click()">
				<?php
					if($clinicaRow['cl_logo']!=''){ echo "<img src='$clinicaRow[cl_logo]'/>"; }
					else { echo '<span>Logotipo</span>'; }
				?>				
			</div>
		    <input type="file" name="filePhoto" id="filePhoto">
		</div>
		</form>
		<div class="contenedorFooter"><?php include'pasarela-footer.php' ?></div>
	</div>
<!--
	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>
-->
	<script src="js/jquery-2-2-0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/cargaImg.js"></script>

</body>
</html>