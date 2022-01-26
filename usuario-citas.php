<?php include'config.php'; 

if($sessionRol==5){
	$id = $sessionUsuario;
} else {
	$id = $_POST['id'];
}

$userCitasSql = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas = '$id'");
$userCitasRow = $userCitasSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Usuario Citas: '.$userCitasRow['uc_nombres']; } else {echo'Nuevo Usuario de Citas';} ?></h4>
</div>
<form class="form" id="formUserCitas" method="post" action="usuario-citas-guardar.php">
	<div class="modal-body">
		<div class="divForm">
			<div class="container1Part">
				<select name="sucursal" class="formulario__modal__input" data-label="Sucursal">
					<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
					<?php
						$sucursalesSql = $con->query("SELECT * FROM sucursales WHERE sc_idClinica = '$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");
		            	while($sucursalesRow = $sucursalesSql->fetch_assoc()){
		            		$sucursalSelected = '';
		            		if($sucursalesRow['IDSucursal']==$userCitasRow['uc_idSucursal']){ $sucursalSelected = "selected"; }
		            		echo "<option value=".$sucursalesRow['IDSucursal']." ".$sucursalSelected.">".$sucursalesRow['sc_nombre']."</option>";						
						}
		            ?>
	            </select>
	        </div>
	        <div class="container1Part">
				<input type="text" name="nombre" value="<?php echo $userCitasRow['uc_nombres'] ?>" class="formulario__modal__input" data-label="Nombre">
			</div>
			<div class="container1Part">
				<input type="email" name="correoReq" value="<?php echo $userCitasRow['uc_correo'] ?>" class="formulario__modal__input" data-label="Correo Electrónico">
			</div>
			<div class="contenedorCheckbox SliderSwitch pointer">
				<label for="switch">Enviar notificaciones
				<input id="switch" type="checkbox" name="enviarAlertas" value="1" <?php if($userCitasRow['uc_enviarCorreo']==1){echo"checked";} ?>>
				<div class="SliderSwitch__container">
					<div class="SliderSwitch__toggle"></div>
				</div>
				</label>
			</div>
		</div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script>validar('#formUserCitas');</script>