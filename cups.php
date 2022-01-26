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

	$cup = $con->query("SELECT * FROM cups WHERE IDCups = '$_GET[id]'");
	$cupRow = $cup->fetch_assoc();
?>

	<form method="post" action="cups-guardar.php">
		<input type="text" name="id" value="<?php echo $_GET['id'] ?>">
		<input type="text" name="cup" value="<?php echo $cupRow['cup_nombre'] ?>">
		<button>guardar</button>
	</form>

<?php

}


$cups = $con->query("SELECT * FROM cups");
while($cupsRow = $cups->fetch_assoc()){
	echo "<a href='cups.php?id=$cupsRow[IDCups]'>".$cupsRow['IDCups'].' | '.$cupsRow['cup_codigo'].' | '.$cupsRow['cup_nombre'].'</a><br>';
}
?>