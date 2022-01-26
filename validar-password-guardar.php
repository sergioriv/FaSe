<?php include'config-lobby.php';

$newPass = $_POST['newPassword'];
$conPass = $_POST['ConfirmPassword'];
if($newPass!=$conPass){
?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
			
		<div>Las contraseñas ingresadas no coinciden.</div>

	</label>
<?php
} else {

	$password_hash = password_hash($conPass, PASSWORD_DEFAULT);

	$con->query("UPDATE usuarios SET us_password = '$password_hash' WHERE IDUsuario = '$_SESSION[concultoriosIDUsuario]'");

?>
	
	<input type="radio" id="alertExito">
	<label class="alerta exito" for="alertExito">
		<div>Contraseña cambiada!</div>
	</label>
	<script type="text/javascript">
		setTimeout("location.href = './'",2000);
	</script>
<?php } ?>