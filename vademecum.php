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

	$vademecum = $con->query("SELECT * FROM vadecum WHERE IDVadecum = '$_GET[id]'");
	$vademecumRow = $vademecum->fetch_assoc();
?>

	<form method="post" action="vademecum-guardar.php">
		<input type="text" name="id" value="<?php echo $_GET['id'] ?>">
		<textarea name="medicamento" rows="3"><?php echo $vademecumRow['vd_medicamento'] ?></textarea>
		<textarea name="presentacion" rows="3"><?php echo $vademecumRow['vd_presentacion'] ?></textarea>
		<button>guardar</button>
	</form>

<?php

} else {

$vademecumSql = $con->query("SELECT * FROM vadecum");

?>
	<table style="width: 100%;">
		<thead>
			<tr>
				<th>Medicamento</th>
				<th>Presentación</th>
			</tr>
		</thead>
		<tbody>
			<?php while($vademecumsRow = $vademecumSql->fetch_assoc()){

				if( strlen($vademecumsRow['vd_medicamento']) > 50  ){
			?>
			<tr>
				<td><a href="vademecum?id=<?php echo $vademecumsRow['IDVadecum'] ?>"><?php echo $vademecumsRow['vd_medicamento'] ?></a></td>
				<td><?php echo $vademecumsRow['vd_presentacion'] ?></td>
			</tr>
			<?php }} ?>
		</tbody>
	</table>
<?php } ?>