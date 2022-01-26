<?php include'config-lobby.php'; ?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css">
	*{
		font-family: verdana;
		font-weight: bold;
		background: #171717;
		color: #f9f9f9;
		    line-height: 2;
		    font-size: 15px;
		    text-decoration: none;
	}
	form{
		display: flex;
		flex-direction: column;
	}
</style>
<?php

if($_GET['id']){

	$rip = $con->query("SELECT * FROM rips WHERE IDRips = '$_GET[id]'");
	$ripRow = $rip->fetch_assoc();
?>

	<form method="post" action="cups-guardar.php">
		<input type="text" name="id" value="<?php echo $_GET['id'] ?>">
		<input type="text" name="rip" value="<?php echo $ripRow['rip_nombre'] ?>">
		<button>guardar</button>
	</form>

<?php

}

$rips = $con->query("SELECT * FROM rips");
while($ripsRow = $rips->fetch_assoc()){
	echo "<a href='rips?id=$ripsRow[IDRips]'>".$ripsRow['IDRips'].' | '.$ripsRow['rip_nombre'].'</a><br>';
}
?>