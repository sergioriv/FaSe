<?php include'config-lobby.php';

$validar = $_SESSION['consultoriosValidar'];

if($validar==1){

	if($_POST['correo']){ $_SESSION['consultoriosLobbyCorreo'] = $_POST['correo']; }
	$correo = $_SESSION['consultoriosLobbyCorreo'];

	$valCorreo = $con->query("SELECT * FROM usuarios WHERE us_correo = '$correo' AND us_estado = '1'");
	$usuarioRow = $valCorreo->fetch_assoc();

	if($valCorreo->num_rows==0){
?>
		<input type='radio' id='alertError'>
		<label class='alerta error' for='alertError'>
			<div>No se encuentra registrado.</div>
		</label>
		<script type="text/javascript">
		$(document).ready(function() {
			document.getElementById('loginCorreo').focus();
			$('#loginCorreo').addClass('validar');
		    setTimeout(function() {
		        $(".alerta").fadeOut(500);
		    },3000);
		});
		</script>
<?php
	} else if($valCorreo->num_rows==1){
		
		$_SESSION['consultoriosLobbyClinica'] = $usuarioRow['us_idClinica'];
		$_SESSION['consultoriosLobbyIDUsuario'] = $usuarioRow['IDUsuario'];
		$_SESSION['consultoriosLobbyRol'] = $usuarioRow['IDUsuario'];
		echo "<script>$('#lobbyForm').load('validar-password.php');</script>";

	}
	else{
		$valClinicas = $con->query("SELECT DISTINCT clinicas.IDClinica, clinicas.cl_nombre FROM clinicas, usuarios WHERE usuarios.us_idClinica = clinicas.IDClinica AND usuarios.us_correo = '$correo' AND usuarios.us_estado = '1'");
		if($valClinicas->num_rows==1){
			$clinicaRow = $valClinicas->fetch_assoc();
			$_SESSION['consultoriosLobbyClinica'] = $usuarioRow['us_idClinica'];

			$valRol = $con->query("SELECT * FROM usuarios WHERE us_idClinica = '$_SESSION[consultoriosLobbyClinica]' AND us_correo = '$_SESSION[consultoriosLobbyCorreo]' AND us_estado = '1'");
			if($valRol->num_rows==1){

				$rolRow = $valRol->fetch_assoc();
				$_SESSION['consultoriosLobbyRol'] = $rolRow['IDUsuario'];
				echo "<script>$('#lobbyForm').load('validar-password.php');</script>";

			} else { echo "<script>$('#lobbyForm').load('validar-rol.php');</script>"; }

		} else { echo "<script>$('#lobbyForm').load('validar-empresa.php');</script>"; }
	}

}
if($validar==2){

	if($_POST['clinica']){ $_SESSION['consultoriosLobbyClinica'] = $_POST['clinica']; }

	$valRol = $con->query("SELECT * FROM usuarios WHERE us_idClinica = '$_SESSION[consultoriosLobbyClinica]' AND us_correo = '$_SESSION[consultoriosLobbyCorreo]' AND us_estado = '1'");
	if($valRol->num_rows==1){

		$rolRow = $valRol->fetch_assoc();
		$_SESSION['consultoriosLobbyRol'] = $rolRow['IDUsuario'];
		$_SESSION['consultoriosLobbyIDUsuario'] = $rolRow['IDUsuario'];
		echo "<script>$('#lobbyForm').load('validar-password.php');</script>";

	}
	else { echo "<script>$('#lobbyForm').load('validar-rol.php');</script>"; }

	//echo "<script>$('#lobbyForm').load('validar-rol.php');</script>";

}
if($validar==3){

	if($_POST['rol']){ $_SESSION['consultoriosLobbyRol'] = $_POST['rol']; }

	echo "<script>$('#lobbyForm').load('validar-password.php');</script>";

}

?>