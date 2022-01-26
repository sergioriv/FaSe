<?php include'../config.php';

$sucursalID = $_POST['sucursalID'];
$query = $con->query("SELECT * FROM unidadesodontologicas WHERE uo_idSucursal = $sucursalID AND uo_estado = 1");
?>
	<option selected hidden value="">-- Seleccionar --</option>
<?php
while($row = $query->fetch_assoc()){
?>
	<option value="<?php echo $row['IDUnidadOdontologica'] ?>"><?php echo $row['uo_nombre'] ?></option>
<?php
}
?>