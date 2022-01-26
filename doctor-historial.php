<?php include'config.php'; $id = $_POST['id'];
$doctorSql = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$id'");
$doctorRow = $doctorSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>
  <div class="consultorioView">
	  <div class="viewImg">
	  		<?php
				if($doctorRow['dc_foto']!=''){ echo "<img src='$doctorRow[dc_foto]'>"; }
			   	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
			?>
			<h3 class="modal-title"><?php echo $doctorRow['dc_nombres'] ?></h3>
	  </div>
	  <table class="tableList">
		<thead>
			<tr>
				<th>Sucursal</th>
				<th>Tratamiento</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
			<?php $citasSql = $con->query("SELECT * FROM citas, sucursales, tratamientos WHERE citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idDoctor = '$id' ORDER BY citas.ct_fechaOrden DESC");

				while($citasRow = $citasSql->fetch_assoc()){
					if($citasRow['ct_terminado']==1){
						$estado = 'Activo';
					} else {
						$estado = 'Terminado '.$citasRow['ct_terminadoFecha'];
					}
			?>
				<tr>
					<td><?php echo $citasRow['sc_nombre'] ?></td>
					<td><?php echo $citasRow['tr_nombre'] ?></td>
					<td align="center"><?php echo $estado ?></td>
				</tr>
			<?php } ?>
		</tbody>
	  </table>
  </div>
</div>