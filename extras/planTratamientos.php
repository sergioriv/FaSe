<?php include'../config.php';

$faseID = $_POST['faseID'];

?>
			<option value="" selected hidden>-- Seleccionar --</option>			

			<?php 

			if($faseID == 1000){

				$tratamientosSelSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_idFase='$faseID' AND tr_estado='1' ORDER BY tr_nombre");

				while($combos = $tratamientosSelSql->fetch_assoc()){

					$tratamientosCombo = $con->query("SELECT fs_nombre, tr_nombre, IDTratamiento, cbt_precio FROM combotratamientos AS cbt 
						INNER JOIN tratamientos AS tr ON cbt.cbt_idTratamiento = tr.IDTratamiento 
						INNER JOIN fases AS fs ON tr.tr_idFase = fs.IDFase 
						WHERE cbt_idCombo = '$combos[IDTratamiento]' AND tr_estado='1' ORDER BY tr_nombre");

					if( $tratamientosCombo->num_rows > 0 ){
?>
						<optgroup label="<?php echo $combos['tr_nombre'] ?>">

							<?php 

								while($tratamientosComboRow = $tratamientosCombo->fetch_assoc()){

									echo "<option value=".$tratamientosComboRow['IDTratamiento']." data-precio=".$tratamientosComboRow['cbt_precio']." data-combo=".$combos['IDTratamiento'].">".$tratamientosComboRow['fs_nombre'].' - '.$tratamientosComboRow['tr_nombre']."</option>";
								}
							?>

						</optgroup>

<?php 				}
				}
			} else {
?>
				<optgroup label="Tratamientos">
				<?php $tratamientosSelSql = $con->query("SELECT IDTratamiento, tr_nombre, tr_precio FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_idFase='$faseID' AND tr_estado='1' ORDER BY tr_nombre");
					while($tratamientosSelRow = $tratamientosSelSql->fetch_assoc()){
						echo "<option value=".$tratamientosSelRow['IDTratamiento']." data-precio=".$tratamientosSelRow['tr_precio'].">".$tratamientosSelRow['tr_nombre']."</option>";	
					}
				?>
				</optgroup>
<?php
			}
			?>