<?php include'config-lobby.php';

$codUsuario = strtoupper($_POST['codigo']);
$iniciarRow = $con->query("SELECT * FROM usuarios WHERE IDUsuario='$_SESSION[consultoriosLobbyRol]' AND us_estado = '1'")->fetch_assoc();
if($iniciarRow['us_codVerificacion']!=$codUsuario){
?>

	<input type="radio" id="alertError">
	<label class="alerta error s" for="alertError">
		<div>Código no valido.</div>
	</label>
	<script type="text/javascript">
	$(document).ready(function() {
		document.getElementById('codigo').focus();
		$('#codigo').addClass('validar');
	    setTimeout(function() {
	        $(".s").fadeOut(500);
	    },5000);
	});
	</script>

<?php }
else {

	$_SESSION['concultoriosClinica'] = $iniciarRow['us_idClinica'];
	$_SESSION['concultoriosUsuario'] = $iniciarRow['us_id'];
	$_SESSION['concultoriosIDUsuario'] = $iniciarRow['IDUsuario'];
	$_SESSION['concultoriosRol'] = $iniciarRow['us_idRol'];

?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#codigo').removeClass('validar');
		$('#lobbyForm').load('validar-password-nuevas.php');
	});
	</script>
<?php } ?>