<?php include'config.php';

$egresoID = $_POST['id'];

$query = $con->query("UPDATE ordenesabonos SET pra_estado='0' WHERE IDOrdenAbono = '$egresoID'");
	
	if($query){
		 $_SESSION['consultoriosExito']=22; }
	else { $_SESSION['consultoriosExito']=1; }
	
	header("Location:$_SESSION[concultoriosAntes]");

?>
