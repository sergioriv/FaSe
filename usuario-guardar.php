<?php include'config.php';

$correoUser = $_POST['correo'];
$passwordUser = $_POST['passwordActual'];
$newPasswordUser = $_POST['newPassword'];
$ConfirmPassword = $_POST['ConfirmPassword'];

$correoUser = filter_var($correoUser, FILTER_SANITIZE_EMAIL);
if (!filter_var($correoUser, FILTER_VALIDATE_EMAIL)) {
	echo "<script type='text/javascript'>$('#correo').addClass('validar');</script>";
	exit();
} else {
	echo "<script type='text/javascript'>$('#correo').removeClass('validar');</script>";
}



if($passwordUser!=""){

	if($newPasswordUser==""){
		echo "<script type='text/javascript'>$('#newPassword').addClass('validar');</script>";
		exit();
	} else {
		echo "<script type='text/javascript'>$('#newPassword').removeClass('validar');</script>";
	}

	if($newPasswordUser!=$ConfirmPassword){
		echo "<script type='text/javascript'>$('#ConfirmPassword').addClass('validar');</script>";
		exit();
	} else {
		echo "<script type='text/javascript'>$('#ConfirmPassword').removeClass('validar');</script>";
	}

	$usuarioSql = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$sessionIDUsuario'");
	$usuarioRow = $usuarioSql->fetch_assoc();
	
	if(password_verify($passwordUser, $usuarioRow['us_password'])){

		$password_hash = password_hash($newPasswordUser, PASSWORD_DEFAULT);

		$z =$con->query("UPDATE clinicas SET cl_correo = '$correoUser' WHERE IDClinica = '$sessionUsuario'");

		$x =$con->query("UPDATE usuarios SET us_correo = '$correoUser', us_password='$password_hash'  WHERE us_id = '$sessionUsuario'");

		if($z && $x){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }
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
} else {

	if($passwordUser=="" && ($newPasswordUser!="" || $ConfirmPassword!="")){
		echo "<script type='text/javascript'>$('#passwordActual').addClass('validar');</script>";
		exit();
	} else {
		echo "<script type='text/javascript'>$('#passwordActual').removeClass('validar');</script>";
	}


		$z =$con->query("UPDATE clinicas SET cl_correo = '$correoUser' WHERE IDClinica = '$sessionUsuario'");

		$x =$con->query("UPDATE usuarios SET us_correo = '$correoUser' WHERE us_id = '$sessionUsuario'");

		if($z && $x){ $_SESSION['consultoriosExito']=3;	} else { $_SESSION['consultoriosExito']=1; }
?>
		<script type="text/javascript">
			setTimeout("location.href = '<?php echo $_SESSION[concultoriosAntes] ?>'");
		</script>
<?php
}
?>