<?php include'config-lobby.php';
$_SESSION['consultoriosValidar']=3;
?>
<form class="form" action="validar.php" id="login" method="post">
	<select name="rol" id="rol" required>
		<option selected hidden value="">Seleccionar Rol</option>
		<?php
			$rolesSql = $con->query("SELECT * FROM usuarios, rolesusuario WHERE usuarios.us_idRol = rolesusuario.IDRol AND usuarios.us_correo = '$_SESSION[consultoriosLobbyCorreo]' AND usuarios.us_idClinica = '$_SESSION[consultoriosLobbyClinica]' AND usuarios.us_estado = '1' ORDER BY rolesusuario.IDRol");

	       	while($rolesRow = $rolesSql->fetch_assoc()){
	       		$nombre='';
	       		if($rolesRow['us_idRol']==1){
	       			$cnSql = $con->query("SELECT IDClinica, cl_nombre  FROM clinicas WHERE IDClinica = '$rolesRow[us_id]'")->fetch_assoc();
	       			$nombre = $cnSql['cl_nombre'];
	       		}
	       		if($rolesRow['us_idRol']==2){
	       			$snSql = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$rolesRow[us_id]'")->fetch_assoc();
	       			$nombre = $snSql['sc_nombre'];
	       		}
	       		if($rolesRow['us_idRol']==3){
	       			$dnSql = $con->query("SELECT IDDoctor, dc_nombres FROM doctores WHERE IDDoctor = '$rolesRow[us_id]'")->fetch_assoc();
	       			$nombre = $dnSql['dc_nombres'];
	       		}
	       		if($rolesRow['us_idRol']==4){
	       			$dnSql = $con->query("SELECT IDUserInventario, ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$rolesRow[us_id]'")->fetch_assoc();
	       			$nombre = $dnSql['ui_nombres'];
	       		}
	       		if($rolesRow['us_idRol']==5){
	       			$dnSql = $con->query("SELECT IDUserCitas, uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$rolesRow[us_id]'")->fetch_assoc();
	       			$nombre = $dnSql['uc_nombres'];
	       		}

	       		echo "<option value=".$rolesRow['IDUsuario'].">".$rolesRow['ru_nombre'].' | '.$nombre."</option>";	
	       		//echo $rolesRow['ru_nombre'].' | '.$nombre;	
			}
	    ?>
	</select>
	<button class="btn-lobby btn-primary" id="iniciar">Siguiente</button>
</form>
<script type="text/javascript">
	$('#rol').focus();

	$('#login').submit(function() {

		
  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#msj-login').html(data);
	            }
	        })        
	        return false;
    	
    }); 
</script>