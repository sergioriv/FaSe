<?php include'config.php'; $id = $_POST['id']; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Material</h4>
</div>

<div class="modal-body">

	<div class="contenedorTabs">
	    <input id="tab-1" type="radio" name="tab-group" checked />
	    <label for="tab-1" class="labelTab">Entradas</label>
	    <input id="tab-2" type="radio" name="tab-group" />
	    <label for="tab-2" class="labelTab">Salidas</label>

	    <div class="contenidoTab">
		        <div class="divForm" id="content-1">
		        	<table class="tableList">
		        		<thead>
		        			<tr>
		        				<th>Usuario</th>
		        				<th>Proveedor</th>
		        				<th># Lote</th>
		        				<th># Factura</th>
		        				<th>Reg. Invima</th>
		        				<th>Cant.</th>
		        				<th class="columnaCorta">Creación</th>
		        			</tr>
		        		</thead>
		            	<tbody>
		        	<?php $entradasSql = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial = '$id' ORDER BY IDMatEntrada DESC");
		        		while($entradasRow = $entradasSql->fetch_assoc()){

		        			$rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$entradasRow[me_idUsuario]'")->fetch_assoc();
		        			$usID = $rol['us_id'];
		        			if($rol['us_idRol']==1){
		        				$usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['cl_nombre'];
		        			} else if($rol['us_idRol']==2){
		        				$usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['sc_nombre'];
		        			} else {
		        				$usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['dc_nombres'];
		        			}

		        			$proveedor = $con->query("SELECT * FROM proveedores WHERE IDProveedor = '$entradasRow[me_idProveedor]'")->fetch_assoc();
		        	?>
			        		<tr>
			        			<td><?php echo $nombreUsuario ?></td>
			        			<td><?php echo $proveedor['pr_nombre'] ?></td>
			        			<td><?php echo $entradasRow['me_numeroLote'] ?></td>
			        			<td><?php echo $entradasRow['me_factura'] ?></td>
			        			<td><?php echo $entradasRow['me_invima'] ?></td>
			        			<td><?php echo $entradasRow['me_cantidad'] ?></td>
			        			<td class="columnaCorta"><?php echo $entradasRow['me_fechaCreacion'] ?></td>
				           	</tr>
		        	<?php } ?>
		        		</tbody>
		        	</table>
		        </div>

		        <div class="divForm" id="content-2">
		        	<table class="tableList">
		        		<thead>
		        			<tr>
		        				<th>Usuario</th>
		        				<th>Cant.</th>
		        				<th>Descripción</th>
		        				<th class="columnaCorta">Creación</th>
		        			</tr>
		        		</thead>
		            	<tbody>
		        	<?php $salidasSql = $con->query("SELECT * FROM materialessalida WHERE ms_idMaterial = '$id' ORDER BY IDMatSalida DESC");
		        		while($salidasRow = $salidasSql->fetch_assoc()){

		        			$rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$salidasRow[ms_idUsuario]'")->fetch_assoc();
		        			$usID = $rol['us_id'];
		        			if($rol['us_idRol']==1){
		        				$usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['cl_nombre'];
		        			} else if($rol['us_idRol']==2){
		        				$usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['sc_nombre'];
		        			} else {
		        				$usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
		        				$nombreUsuario = $usuario['dc_nombres'];
		        			}
		        	?>
			        		<tr>
			        			<td><?php echo $nombreUsuario ?></td>
			        			<td><?php echo $salidasRow['ms_cantidad'] ?></td>
			        			<td><?php echo $salidasRow['ms_detalles'] ?></td>
			        			<td class="columnaCorta"><?php echo $salidasRow['ms_fechaCreacion'] ?></td>
				           	</tr>
		        	<?php } ?>
		        		</tbody>
		        	</table>
		        </div>
		</div>
	</div>



