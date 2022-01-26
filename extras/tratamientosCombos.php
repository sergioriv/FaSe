<?php include'../config.php';

$comboID = $_POST['combo'];

$tratamientosCombo = $con->query("SELECT IDTratamiento, tr_nombre, cbt_precio FROM combotratamientos AS cbt INNER JOIN tratamientos AS tr ON cbt.cbt_idTratamiento = tr.IDTratamiento WHERE cbt_idCombo='$comboID' AND tr_estado=1");
echo '<option selected hidden value="-1">-- Seleccionar --</option>';
while($tratamientosComboRow = $tratamientosCombo->fetch_assoc()){
	echo "<option value=".$tratamientosComboRow['IDTratamiento']." data-valor=".$tratamientosComboRow['cbt_precio'].">".$tratamientosComboRow['tr_nombre'].' - '.'$'.number_format($tratamientosComboRow['cbt_precio'], 2, ",", ".")."</option>";
}


?>