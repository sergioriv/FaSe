<?php include'config.php';

$ciudadCL = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Empresa: <?php echo $clinicaRow['cl_nombre'] ?></h4>
</div>
<form class="form" id="formEmpresa" method="post" action="empresa-guardar.php" enctype="multipart/form-data">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">
				<input type="text" name="nombreEmp" value="<?php echo $clinicaRow['cl_nombre'] ?>" class="formulario__modal__input" data-label="Nombre de la Empresa">
				<span></span>
				<input type="text" name="nitEmp" value="<?php echo $clinicaRow['cl_nit'] ?>" class="formulario__modal__input" data-label="Nit de la Empresa">
			</div>
			<div class="container3PartForm">
				<input type="text" name="codigoEmp" value="<?php echo $clinicaRow['cl_codigo'] ?>" class="formulario__modal__input" data-label="Código del prestador de servicios de salud">
				<span></span>
				<input type="text" name="telefonoEmp" value="<?php echo $clinicaRow['cl_telefono'] ?>" class="formulario__modal__input" data-label="Teléfono / Celular">
			</div>
			<div class="container1Part contRips">
				<select name="ciudadEmp" id="ciudadEmp" class="formulario__modal__input" data-label="Ciudad">
						<?php
							if($clinicaRow['cl_idCiudad']!=0){
				            	echo "<option value=".$ciudadCL['IDCiudad']." selected>".$ciudadCL['cd_nombre']."</option>";
				            }
				        ?>
				</select>
			</div>
			<div class="container1Part">
				<input type="text" name="direccionEmp" value="<?php echo $clinicaRow['cl_direccion'] ?>" class="formulario__modal__input" data-label="Dirección">
			</div>
			<div class="container1Part">
				<input type="text" name="webEmp" value="<?php echo $clinicaRow['cl_web'] ?>" class="formulario__modal__input" data-label="Página Web">
			</div>
			<div class="contenedorLogo">
				<div id="msjPhoto" class="cargaLogo" onclick="$('#filePhoto').click()">
					<?php
						if($clinicaRow['cl_logo']!=''){ echo "<img src='$clinicaRow[cl_logo]'/>"; }
						else { echo '<span>LogoTipo<br>80x80</span>'; }
					?>				
				</div>
			    <input type="file" accept="image/png, .jpeg, .jpg" name="filePhoto" id="filePhoto">
			</div>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript" src="js/cargaLogo.js"></script>
<script type="text/javascript">
validar('#formEmpresa');
$('#ciudadEmp').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-ciudades.php',
		dataType: 'json',
		delay: 250,
		processResults: function (data){
			return {
				results: data.items,
				  "pagination": {
				    "more": data.pag
				  }
			};
		},
		cache: true
	}
});
</script>