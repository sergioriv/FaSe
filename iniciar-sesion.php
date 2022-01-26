<?php include'config-lobby.php';

$clinicaID = $_SESSION['consultoriosLobbyClinica'];
$usuarioID = $_SESSION['consultoriosLobbyRol'];
$correo = $_SESSION['consultoriosLobbyCorreo'];
$password = $_POST['password'];

$iniciarRow = $con->query("SELECT * FROM usuarios WHERE IDUsuario='$_SESSION[consultoriosLobbyRol]' AND us_estado = '1'")->fetch_assoc();

if (password_verify($password, $iniciarRow['us_password'])){
	$_SESSION['concultoriosClinica'] = $iniciarRow['us_idClinica'];
	$_SESSION['concultoriosUsuario'] = $iniciarRow['us_id'];
	$_SESSION['concultoriosIDUsuario'] = $iniciarRow['IDUsuario'];
	$_SESSION['concultoriosRol'] = $iniciarRow['us_idRol'];			
	//echo "<span>Iniciando Sesión . . .</span>";
?>
	<script type="text/javascript">
		document.getElementById("iniciar").disabled = true;
		$("#iniciar").load('mjlobby.php?action=i');
	</script>
<?php
	if($iniciarRow['us_idRol']==1){
		//$pasarela = $con->query("SELECT IDClinica, cl_pasarela FROM clinicas WHERE IDClinica = '$iniciarRow[us_id]'")->fetch_assoc();
		//if($pasarela['cl_pasarela']==0){
?><!--
			<script type="text/javascript">
			$(document).ready(function() {
				$('#loginPassword').removeClass('validar');
				setTimeout("location.href = 'pasarela-logo'",1500);
			});
			</script>-->
<?php
		//} else {
?>
			<script type="text/javascript">
			$(document).ready(function() {
				$('#loginPassword').removeClass('validar');
				setTimeout("location.href = 'dashboard'",1500);
			});
			</script>
<?php
		//}
	} elseif($iniciarRow['us_idRol']==4){
?>
		<script type="text/javascript">
		$(document).ready(function() {
			$('#loginPassword').removeClass('validar');
			setTimeout("location.href = 'materiales'",1500);
		});
		</script>
<?php
	} else {
?>
		<script type="text/javascript">
		$(document).ready(function() {
			$('#loginPassword').removeClass('validar');
			setTimeout("location.href = 'citas'",1500);
		});
		</script>
<?php
	}

} else { 
?>
	<input type='radio' id='alertError'>
	<label class='alerta error' for='alertError'>
		<div>Contraseña incorrecta.</div>
	</label>
	<script type="text/javascript">
	$(document).ready(function() {
		document.getElementById('loginPassword').focus();
		$('#loginPassword').addClass('validar');
	    setTimeout(function() {
	        $(".alerta").fadeOut(500);
	    },3000);
	});
	</script>
<?php } ?>