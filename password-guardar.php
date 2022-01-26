<?php include'config.php';

foreach ($_POST as $clave => $valor) {
$_SESSION['consultoriosQuery'][$clave] = $valor;
}

extract($_SESSION['consultoriosQuery']);

	if($passwordActual==""){
		echo "<script type='text/javascript'>$('#passwordActual').addClass('validar');</script>";
		exit();
	} else {
		echo "<script type='text/javascript'>$('#passwordActual').removeClass('validar');</script>";
	}

	if($passwordActual!=""){
		if($newPassword!=$ConfirmPassword){
			echo "<script type='text/javascript'>$('#ConfirmPassword').addClass('validar');</script>";
			exit();
		} else {
			echo "<script type='text/javascript'>$('#ConfirmPassword').removeClass('validar');</script>";
		}

		$usuarioSql = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$sessionIDUsuario'");
		$usuarioRow = $usuarioSql->fetch_assoc();
		
		if(password_verify($passwordActual, $usuarioRow['us_password'])){
			$password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
			$query =$con->query("UPDATE usuarios SET us_password='$password_hash'  WHERE IDUsuario = '$sessionIDUsuario'");
			if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
?>
		<script type="text/javascript">
			$('#ConfirmPassword').removeClass('validar');
			setTimeout("location.href = '<?php echo $_SESSION[concultoriosAntes] ?>'");
		</script>
<?php
		} else {
			echo "<script type='text/javascript'>$('#passwordActual').addClass('validar');</script>";
			exit();
		}
	}

	if($passwordActual=="" && ($newPassword!="" || $ConfirmPassword!="")){
		echo "<script type='text/javascript'>$('#passwordActual').addClass('validar');</script>";
		exit();
	} else {
		echo "<script type='text/javascript'>$('#passwordActual').removeClass('validar');</script>";
	}

unset($_SESSION['consultoriosQuery']);

?>
<script type="text/javascript">
	setTimeout("location.href = '<?php echo $_SESSION[concultoriosAntes] ?>'");
</script>
