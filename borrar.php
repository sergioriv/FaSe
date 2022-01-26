<?php require 'config-lobby.php'; ?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css">
	*{
		font-family: verdana;
		font-weight: bold;
		background: #171717;
		color: #f9f9f9;
		    line-height: 2;
		    font-size: 16px;
		    text-decoration: none;
	}
	form{
		display: flex;
		flex-direction: column;
	}
</style>
<?php

if($_GET['id']){

	$sel = $con->query("SELECT * FROM bancos WHERE IDBanco = '$_GET[id]'");
	$rowsel = $sel->fetch_assoc();
?>

	<form method="post" action="borrar-guardar.php">
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
		<input type="text" name="nombre" value="<?php echo $rowsel['bnc_nombre'] ?>">
		<button>guardar</button>
	</form>

<?php 
} else {

$query = $con->query("SELECT * FROM bancos");
?>
<table>
	<?php while($row = $query->fetch_assoc()){ ?>
	<tr>
		<td><?php echo $row['IDBanco'] ?></td>
		<td>&nbsp&nbsp <a href="borrar.php?id=<?php echo $row['IDBanco'] ?>"><?php echo $row['bnc_nombre'] ?></a></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>