<?php include'config-lobby.php';

$codUsuario = strtoupper($_POST['codigo']);
$usuario = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$_SESSION[consultorioRegistroID]'")->fetch_assoc();
if($usuario['us_codVerificacion']!=$codUsuario){
?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div>Código no valido.</div>
	</label>
	<script type="text/javascript">
	$(document).ready(function() {
		document.getElementById('codigo').focus();
		$('#codigo').addClass('validar');
	    setTimeout(function() {
	        $(".alerta").fadeOut(500);
	    },5000);
	});
	</script>
<?php }
else {

	$_SESSION['concultoriosClinica'] = $usuario['us_idClinica'];
	$_SESSION['concultoriosUsuario'] = $usuario['us_id'];
	$_SESSION['concultoriosIDUsuario'] = $usuario['IDUsuario'];
	$_SESSION['concultoriosRol'] = $usuario['us_idRol'];

?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#codigo').removeClass('validar');
		setTimeout("location.href = 'dashboard.php'");
	});
	</script>
<?php } ?>