<?php include'config.php';

$lista = $_POST['lista'];
$busqueda = trim($_POST['buscar']);


// CITAS
/*
if($lista=='citas'){
	if($sessionRol==1){
		$citasQuery = "SELECT * FROM citas, sucursales, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_fechaInicio>='$fechaHoySinEsp' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
	} else if($sessionRol==2){
		$citasQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$sessionUsuario' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' ) ORDER BY citas.ct_fechaOrden ASC";
	} else if($sessionRol==3){
		$citasQuery = "SELECT * FROM citas, sucursales, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_idDoctor = '$sessionUsuario' AND citas.ct_terminado<='3' AND pacientes.pc_estado = '1' AND citas.ct_idSucursal = sucursales.IDSucursal AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) ORDER BY citas.ct_fechaOrden ASC";
	} else if($sessionRol==5){
		$userCitas = $con->query("SELECT * FROM usuarioscitas WHERE IDUserCitas='$sessionUsuario'")->fetch_assoc();
		$citasQuery = "SELECT * FROM citas, doctores, pacientes, tratamientos WHERE citas.ct_idClinica='$sessionClinica' AND citas.ct_terminado<='3' AND citas.ct_idSucursal='$userCitas[uc_idSucursal]' AND pacientes.pc_estado = '1' AND citas.ct_idDoctor = doctores.IDDoctor AND citas.ct_idPaciente = pacientes.IDPaciente	AND citas.ct_idTratamiento = tratamientos.IDTratamiento AND ( pacientes.pc_nombres LIKE '%$busqueda%' OR pacientes.pc_identificacion LIKE '%$busqueda%' OR tratamientos.tr_nombre LIKE '%$busqueda%' OR doctores.dc_nombres LIKE '%$busqueda%' OR CONCAT(citas.ct_anoCita, '/', citas.ct_mesCita, '/', citas.ct_diaCita) LIKE '%$busqueda%' )	ORDER BY citas.ct_fechaOrden ASC";
	}

	$citasSql = $con->query($citasQuery);
?>
			<table class="tableList">
				<thead>
					<tr>
						<th class="estado">&nbsp</th>
						<th class="columnaCorta">Fecha de Cita</th>
						<th colspan="2">Paciente</th>
					<?php if($sessionRol==1||$sessionRol==3){ ?><th>Sucursal</th><?php } ?>
					<?php if($sessionRol!=3){ ?><th colspan="2">Doctor</th><?php } ?>
						<th>Tratamiento</th>
						<th class="columnaTCita">&nbsp</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($citasRow = $citasSql->fetch_assoc()){
						
						if($citasRow['ct_estado']==0){ $estadoCita = 'estadoNeutro'; }
						elseif($citasRow['ct_estado']==1){ $estadoCita = 'estadoConfirmacion'; }
						else { $estadoCita = 'estadoCancelado'; }

						$pacienteUrl = str_replace(" ","-", $citasRow['pc_nombres']);

						$fechaCita = $citasRow['ct_anoCita'].'/'.$citasRow['ct_mesCita'].'/'.$citasRow['ct_diaCita'].' '.$citasRow['ct_horaCita'];

						$estadoEvolucion = '';
						if($citasRow['ct_asistencia']==0){ $estadoEvolucion = 'iconGray';}

						if($citasRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
						} else { $iSC = ''; $cSC = ''; }

						if($citasRow['dc_estado']==0){ $iDC = $iconW; $cDC = 'elementoEliminado';
						} else { $iDC = ''; $cDC = ''; }

						if($citasRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
						} else { $iTR = ''; $cTR = ''; }

						if($citasRow['ct_control']==1){ $tipoCita = '<i class="icon-primercita"></i>'; }
					    else { $tipoCita = '<i class="icon-iconocontrol"></i>'; }

					?>
					<tr>
						<td class="estado <?php echo $estadoCita ?>"></td>
					    <td class="columnaCorta"><?php echo $fechaCita ?></td>
					    <td class="imgUser">
					    	<?php
					    	if($citasRow['pc_foto']!=''){ echo "<img src='$citasRow[pc_foto]'>"; }
					    	else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
					    	?>
					    </td>
					    <td><a id="<?php echo $citasRow['IDPaciente'] ?>" class="consultorioEditarPaciente"><?php echo $citasRow['pc_nombres']; ?></a></td>
					<?php if($sessionRol==1||$sessionRol==3){ ?>
						<td class="<?php echo $cSC ?>"><?php echo $iSC.$citasRow['sc_nombre'] ?></td><?php } ?>
					<?php if($sessionRol!=3){ ?>
						<td class="imgUser <?php echo $cDC ?>">
							<?php
					    	if($citasRow['dc_foto']!=''){ echo "<img src='$citasRow[dc_foto]'>"; }
					    	else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
					    	?>
					    </td>
					    <td class="<?php echo $cDC ?>"><?php echo $iDC.$citasRow['dc_nombres']; ?></td><?php } ?>
					    <td class="<?php echo $cTR ?>"><?php echo $iTR.$citasRow['tr_nombre']; ?></td>
					    <td class="columnaTCita"><?php echo $tipoCita ?></td>
					    <td class="tableOption">
					    <?php if($citasRow['ct_fechaOrden'] > $fechaEvolucionCita){ ?>
					    	<a title="Reasignar Cita" onClick="location.href='cita?cita=<?php echo $citasRow["IDCita"] ?>&id=<?php echo $citasRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-refresh" aria-hidden="true"></i></a>
					    	<a title="Cancelar Cita" id="<?php echo $citasRow['IDCita'] ?>" t="cita" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					    <?php } else { 
					    ?>
					    	<a title="Evolucionar" id="<?php echo $citasRow['IDCita'] ?>" class="consultoriosEvolucion <?php echo $estadoEvolucion ?>"><i class="fa fa-share-alt"></i></a>
					    <?php } ?>
					    </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

<?php

} */
/*
// PACIENTES
if($lista=='pacientes'){ 
	$pacientesSql = $con->query("SELECT * FROM pacientes WHERE pc_idClinica='$sessionClinica' AND pc_estado='1'
		AND ( pc_nombres LIKE '%$busqueda%' OR pc_identificacion LIKE '%$busqueda%' ) ORDER BY pc_nombres");
?>
		<table class="tableList">
			<thead>
				<tr>
					<th colspan="2">Paciente</th>
					<th>Identificación</th>
					<th>Teléfono</th>
					<th>Email</th>
					<th>&nbsp</th>
				</tr>
			</thead>
			<tbody>
				<?php while($pacientesRow = $pacientesSql->fetch_assoc()){ ?>
				<tr>
				    <td class="imgUser">
				    	<?php
					    if($pacientesRow['pc_foto']!=''){ echo "<img src='$pacientesRow[pc_foto]'>"; }
					    else { echo '<i class="fa fa-user " aria-hidden="true"></i>'; }
					    ?>
				    </td>
				    <td><a id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioEditar"><?php echo $pacientesRow['pc_nombres']; ?></a></td>
				    <td><?php echo $pacientesRow['pc_identificacion']; ?></td>
				    <td><?php echo $pacientesRow['pc_telefonoCelular']; ?></td>
				    <td><?php echo $pacientesRow['pc_correo']; ?></td>
				    <td class="tableOption">
					   	<a title="Editar" id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					   	<a title="Nueva Cita" onClick="location.href='cita.php?id=<?php echo $pacientesRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></a>
					   	<!--<a title="Registro Fotográfico" onClick="location.href='registro-fotografico.php?id=<?php echo $pacientesRow["IDPaciente"] ?>&paciente=<?php echo $pacienteUrl ?>'"><i class="fa fa-image"></i></a>-->
					   	<!--<a title="Historial" id="<?php echo $pacientesRow['IDPaciente'] ?>" class="consultorioHistorial"><i class="fa fa-list" aria-hidden="true"></i></a>-->
					   	<a title="Eliminar" id="<?php echo $pacientesRow['IDPaciente'] ?>" t="paciente" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
// DOCTORES
/*
if($lista=='doctores'){
	$doctoresSql = $con->query("SELECT * FROM doctores WHERE dc_idClinica='$sessionClinica' AND dc_estado='1'
		AND ( dc_nombres LIKE '%$busqueda%' OR dc_identificacion LIKE '%$busqueda%' ) ORDER BY dc_nombres");
?>
		<table class="tableList">
			<thead>
				<tr>
					<th colspan="2">Doctor</th>
					<th>Identificación</th>
					<th>Teléfono</th>
					<th>Email</th>
					<th>&nbsp</th>
				</tr>
			</thead>
			<tbody>
				<?php while($doctoresRow = $doctoresSql->fetch_assoc()){ ?>
				<tr>
				    <td class="imgUser">
				    	<?php
					    if($doctoresRow['dc_foto']!=''){ echo "<img src='$doctoresRow[dc_foto]'>"; }
					    else { echo '<i class="fa fa-user-md" aria-hidden="true"></i>'; }
					    ?>
				    </td>
				    <td><a id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioEditar"><?php echo $doctoresRow['dc_nombres']; ?></a></td>
				    <td><?php echo $doctoresRow['dc_telefonoCelular']; ?></td>
				    <td><?php echo $doctoresRow['dc_identificacion']; ?></td>
				    <td><?php echo $doctoresRow['dc_correo']; ?></td>
				    <td class="tableOption">
				    	<a id="<?php echo $doctoresRow['IDDoctor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
				    	<a id="<?php echo $doctoresRow['IDDoctor'] ?>" t="doctor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
				    </td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
//  MATERIALES
/*
if($lista=='materiales'){

	$semaforoRed = date ( 'Y-m-d' , strtotime ( '+30days' , strtotime ( $fechaHoy ) ) ) ;
	$semaforoYellow = date ( 'Y-m-d' , strtotime ( '+90days' , strtotime ( $fechaHoy ) ) ) ;
	$semaforoNeutro = $hoyAno.'-'.$hoyMes.'-'.$hoyDia;

	if($sessionRol==1){
		$materialesQuery = "SELECT * FROM materiales, sucursales WHERE materiales.mt_idSucursal = sucursales.IDSucursal 
		AND sucursales.sc_idClinica = '$sessionClinica' AND materiales.mt_estado='1' AND (materiales.mt_codigo LIKE '%$busqueda%' OR materiales.mt_nombre LIKE '%$busqueda%') ORDER BY materiales.mt_codigo";
	} else if($sessionRol==2){
		$materialesQuery = "SELECT * FROM materiales, sucursales WHERE sucursales.sc_idClinica = '$sessionClinica' AND sucursales.IDSucursal='$sessionUsuario' AND materiales.mt_idSucursal = sucursales.IDSucursal AND materiales.mt_estado='1' AND (materiales.mt_codigo LIKE '%$busqueda%' OR materiales.mt_nombre LIKE '%$busqueda%') ORDER BY materiales.mt_codigo";
	} else if($sessionRol==4){

		$usuarioInventario = $con->query("SELECT * FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();

		$materialesQuery = "SELECT * FROM materiales, sucursales WHERE sucursales.sc_idClinica = '$sessionClinica' AND sucursales.IDSucursal='$usuarioInventario[ui_idSucursal]' AND materiales.mt_idSucursal = sucursales.IDSucursal AND materiales.mt_estado='1' AND (materiales.mt_codigo LIKE '%$busqueda%' OR materiales.mt_nombre LIKE '%$busqueda%') ORDER BY materiales.mt_codigo";
	}


	$materialesSql = $con->query($materialesQuery);
?>
		<table class="tableList">
			<thead>
				<tr>
					<th class="estado"></th>
					<th>Cod.</th>
					<th>Material</th>
					<th>Cant.</th>
				<?php if($sessionRol==1){ ?>
					<th>Sucursal</th>
				<?php } ?>
					<th>Temp.</th>
					<th>&nbsp</th>
				</tr>
			</thead>
			<tbody>
				<?php while($materialesRow = $materialesSql->fetch_assoc()){
					$entradas = 0;
					$salidas = 0;
					$cantidad = 0;
					$entradasSql = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial = '$materialesRow[IDMaterial]'");
					while($entradasRow = $entradasSql->fetch_assoc()){
						$entradas += $entradasRow['me_cantidad'];
					}
					$salidasSql = $con->query("SELECT * FROM materialessalida WHERE ms_idMaterial = '$materialesRow[IDMaterial]'");
					while($salidasRow = $salidasSql->fetch_assoc()){
						$salidas += $salidasRow['ms_cantidad'];
					}
					$cantidad = $entradas - $salidas;

					if($materialesRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
					} else { $iSC = ''; $cSC = ''; }

						if($materialesRow['mt_vencimiento'] == 0) { $estadoVendimiento = 'estadoNeutro'; }
						else {
							$querySemaforoNeutro = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_fechaVencimiento < '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;
							$querySemaforoRed = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_fechaVencimiento <= '$semaforoRed' AND me_fechaVencimiento >= '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;
							$querySemaforoYellow = $con->query("SELECT * FROM materialesentrada WHERE me_idMaterial='$materialesRow[IDMaterial]' AND me_fechaVencimiento <= '$semaforoYellow' AND me_fechaVencimiento >= '$semaforoNeutro' ORDER BY IDMatEntrada DESC")->num_rows;

							if($querySemaforoRed >= 1 ) { $estadoVendimiento = 'semaforoRojo'; }
							else if($querySemaforoYellow >= 1) { $estadoVendimiento = 'semaforoAmarillo'; }
							else if($querySemaforoNeutro >= 1) { $estadoVendimiento = 'estadoNeutro'; }
						}
				?>
				<tr>
					<td class="estado <?php echo $estadoVendimiento ?>">&nbsp</td>
				    <td><?php echo $materialesRow['mt_codigo']; ?></td>
				    <td><a id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEditar"><?php echo $materialesRow['mt_nombre']; ?></a></td>
				    <td class="centro"><?php echo $cantidad; ?></td>
				<?php if($sessionRol==1){ ?>
				    <td class="<?php echo $cSC ?>"><?php echo $iSC.$materialesRow['sc_nombre']; ?></td>
				<?php } ?>
				    <td><?php echo $materialesRow['mt_temperatura']; ?></td>
				    <td class="tableOption">
					   	<a title="Entrada" id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEntrada"><i class="fa fa-download" aria-hidden="true"></i></a>
					<?php if($cantidad>0){ ?>
					  	<a title="Salida" id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioSalida"><i class="fa fa-upload" aria-hidden="true"></i></a>
					<?php } ?>
					   	<!--<a title="Historial" title="Historial" id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioHistorial"><i class="fa fa-list" aria-hidden="true"></i></a>-->
					   	<a title="Editar" id="<?php echo $materialesRow['IDMaterial'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					   	<a title="Eliminar" id="<?php echo $materialesRow['IDMaterial'] ?>" t="material" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
// SUCURSALES
/*
if($lista=='sucursales'){
	$sucursalesSql = $con->query("SELECT * FROM sucursales WHERE sc_idClinica='$sessionClinica' AND sc_estado='1' AND 
		(sc_nombre LIKE '%$busqueda%' OR sc_telefonoFijo LIKE '%$busqueda%' OR sc_correo LIKE '%$busqueda%' OR sc_direccion LIKE '%$busqueda%') ORDER BY sc_nombre");
?>
		<table class="tableList">
			<thead>
				<tr>
					<th>Sucursal</th>
					<th>Teléfono</th>
					<th>Ciudad</th>
					<th>Dirección</th>
					<th>Email</th>
					<th>Horario</th>
					<th>&nbsp</th>
				</tr>
			</thead>
			<tbody>
				<?php while($sucursalesRow = $sucursalesSql->fetch_assoc()){
						$ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$sucursalesRow[sc_idCiudad]'");
						$ciudadRow = $ciudadSql->fetch_assoc();
				?>
				<tr>
				    <td><a id="<?php echo $sucursalesRow['IDSucursal'] ?>" class="consultorioEditar"><?php echo $sucursalesRow['sc_nombre']; ?></a></td>
				    <td><?php echo $sucursalesRow['sc_telefonoFijo']; ?></td>
				    <td><?php echo $ciudadRow['cd_nombre'] ?></td>
				    <td><?php echo $sucursalesRow['sc_direccion']; ?></td>
				    <td><?php echo $sucursalesRow['sc_correo']; ?></td>
					<td><?php echo $sucursalesRow['sc_atencionDe'].' / '.$sucursalesRow['sc_atencionHasta']; ?></td>
				    <td class="tableOption">
				    	<a title="Editar" id="<?php echo $sucursalesRow['IDSucursal'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
				    	<a id="<?php echo $sucursalesRow['IDSucursal'] ?>" t="sucursal" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
				    </td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
// PROVEEDORES
/*
if($lista=='proveedores'){
	$proveedoresSql = $con->query("SELECT * FROM proveedores WHERE pr_idClinica='$sessionClinica' AND pr_estado='1' AND (pr_nombre LIKE '%$busqueda%' OR pr_nit LIKE '%$busqueda%' OR pr_telefonoFijo LIKE '%$busqueda%' OR pr_correo LIKE '%$busqueda%') ORDER BY pr_nombre");

?>
		<table class="tableList">
			<thead>
				<tr>
					<th>Proveedor</th>
					<th>NIT</th>
					<th>Teléfono</th>
					<th>Ciudad</th>
					<th>Dirección</th>
					<th>Email</th>
					<th>&nbsp</th>
				</tr>
			</thead>
			<tbody>
				<?php while($proveedoresRow = $proveedoresSql->fetch_assoc()){
					$ciudadSql = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$proveedoresRow[pr_idCiudad]'");
					$ciudadRow = $ciudadSql->fetch_assoc();
				?>
				<tr>
				    <td><a id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioEditar"><?php echo $proveedoresRow['pr_nombre']; ?></a></td>
				    <td><?php echo $proveedoresRow['pr_nit']; ?></td>
				    <td><?php echo $proveedoresRow['pr_telefonoFijo']; ?></td>
				    <td><?php echo $ciudadRow['cd_nombre'] ?></td>
				    <td><?php echo $proveedoresRow['pr_direccion']; ?></td>
				    <td><?php echo $proveedoresRow['pr_correo']; ?></td>
				    <td class="tableOption">
				    	<a title="Editar" id="<?php echo $proveedoresRow['IDProveedor'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
				    	<a id="<?php echo $proveedoresRow['IDProveedor'] ?>" t="proveedor" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
				    </td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
// EPS
/*
if($lista=='epss'){
	$epssSql = $con->query("SELECT * FROM eps WHERE eps_estado='1' AND (eps_codigo LIKE '%$busqueda%' OR eps_nit LIKE '%$busqueda%' OR eps_nombre LIKE '%$busqueda%') ORDER BY eps_nombre");
?>
		<table class="tableList">
			<thead>
				<tr>
					<th>Cod.</th>
					<th>NIT</th>
					<th>Nombre</th>
				</tr>
			</thead>
			<tbody>
				<?php while($epssRow = $epssSql->fetch_assoc()){ ?>
				<tr>
				    <td><?php echo $epssRow['eps_codigo']; ?></td>
				    <td><?php echo $epssRow['eps_nit']; ?></td>
				    <td><?php echo $epssRow['eps_nombre']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
// CIUDADES
if($lista=='ciudades'){
	$ciudadesSql = $con->query("SELECT * FROM ciudades WHERE cd_idClinica='$sessionClinica' AND cd_estado='1' AND 
		(cd_nombre LIKE '%$busqueda%') ORDER BY cd_nombre");

?>
		<table class="tableList">
			<tbody>
				<?php while($ciudadesRow = $ciudadesSql->fetch_assoc()){ ?>
				<tr>
				    <td><?php echo $ciudadesRow['cd_nombre']; ?></td>
				    <td class="tableOption">
				    	<a id="<?php echo $ciudadesRow['IDCiudad'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
				    	<a id="<?php echo $ciudadesRow['IDCiudad'] ?>" t="ciudad" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
				    </td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }

// TRATAMIENTOS
/*
if($lista=='tratamientos'){
	$tratamientosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica='$sessionClinica' AND tr_estado='1' AND 
		(tr_nombre LIKE '%$busqueda%') ORDER BY tr_nombre");

?>
		<table class="tableList">
			<thead>
				<tr>
					<th>Precio</th>
					<th>CUP</th>
					<th>Tratamiento</th>
					<th>&nbsp</th>
				</tr>
			</thead>
			<tbody>
				<?php while($tratamientosRow = $tratamientosSql->fetch_assoc()){
						$cupsSql = $con->query("SELECT * FROM cups WHERE IDCups = '$tratamientosRow[tr_idCups]'");
						$cupsRow = $cupsSql->fetch_assoc();
				?>
				<tr>
				    <td align="right" class="columnaCorta"><?php echo '$'.number_format($tratamientosRow['tr_precio'], 0, ".", ","); ?></td>
					<td align="center" class="columnaCorta"><?php echo $cupsRow['cup_codigo']; ?></td>
					<td><?php echo $tratamientosRow['tr_nombre']; ?></td>
					<td class="tableOption">
					  	<a id="<?php echo $tratamientosRow['IDTratamiento'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					   	<a id="<?php echo $tratamientosRow['IDTratamiento'] ?>" t="tratamiento" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<?php }
*/
//USUARIO INVENTARIO
/*
if($lista=='usInventario'){

	$userInventarioSql = $con->query("SELECT * FROM usuariosinventario, sucursales WHERE usuariosinventario.ui_idSucursal = sucursales.IDSucursal AND usuariosinventario.ui_idClinica='$sessionClinica' AND usuariosinventario.ui_estado='1' AND (usuariosinventario.ui_nombres LIKE '%$busqueda%' OR usuariosinventario.ui_correo LIKE '%$busqueda%' OR sucursales.sc_nombre LIKE '%$busqueda%') ORDER BY usuariosinventario.ui_nombres");
?>
			<table class="tableList">
				<thead>
					<tr>
						<th>Nombres</th>
						<th>Correo</th>
						<th>Sucursal</th>
					</tr>
				</thead>
				<tbody>
					<?php while($userInventarioRow = $userInventarioSql->fetch_assoc()){

						if($userInventarioRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
						} else { $iSC = ''; $cSC = ''; }
					?>
					<tr>
					    <td><?php echo $userInventarioRow['ui_nombres']; ?></td>
					    <td><?php echo $userInventarioRow['ui_correo']; ?></td>
					    <td class="<?php echo $cSC ?>"><?php echo $iSC.$userInventarioRow['sc_nombre']; ?></td>
					    <td class="tableOption">
					    	<a id="<?php echo $userInventarioRow['IDUserInventario'] ?>" class="consultorioEditar"><?php echo $iconoEditar ?></a>
					    	<a id="<?php echo $userInventarioRow['IDUserInventario'] ?>" t="usInventario" class="consultorioEliminar eliminar"><?php echo $iconoEliminar ?></a>
					    </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
<?php }
*/
//ABONOS
/*
if($lista=='abonos'){
	$abonosSql = $con->query("SELECT * FROM abonos, usuarios, sucursales, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' AND (sucursales.sc_nombre LIKE '%$busqueda%' OR pacientes.pc_nombres LIKE '%$busqueda%')  ORDER BY sucursales.sc_nombre ASC,abonos.IDAbono DESC");
?>
			<table class="tableList">
				<thead>
					<tr>
						<th class="estado">&nbsp</th>
						<th>#</th>
						<th class="columnaCorta">Fecha</th>
						<th>Usuario</th>
						<th>Sucursal</th>
						<th>Paciente</th>
						<th align="right">Valor</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
					<?php while($abonosRow = $abonosSql->fetch_assoc()){

							$nombreUsuarioAbono = '';
					    	$IDusuarioAbono = $abonosRow['us_id'];
					    	if($abonosRow['us_idRol']==1){
					   			$usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")
					   			->fetch_assoc();
					   			$nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
					    	} elseif($abonosRow['us_idRol']==2){
					   			$usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")
					   			->fetch_assoc();
					   			$nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
					    	} elseif($abonosRow['us_idRol']==3){
					   			$usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")
					   			->fetch_assoc();
					   			$nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
					    	}

					    	if($abonosRow['ab_estado']==1){
					    		$estadoAbono = 'estadoNeutro';
					    	} else {
					    		$estadoAbono = 'estadoCancelado';
					    	}

					    	if($abonosRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
							} else { $iUS = ''; $cUS = ''; }
							if($abonosRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
							} else { $iSC = ''; $cSC = ''; }
							if($abonosRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
							} else { $iPC = ''; $cPC = ''; }
					?>
					<tr>
					    <td class="estado <?php echo $estadoAbono ?>"></td>
					    <td align="right"><?php echo $abonosRow['ab_consecutivo'] ?></td>
					    <td align="center"><?php echo $abonosRow['ab_fechaCreacion'] ?></td>
					    <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
					    <td class="<?php echo $cSC ?>"><?php echo $iSC.$abonosRow['sc_nombre'] ?></td>
					    <td class="<?php echo $cPC  ?>"><?php echo $iPC.$abonosRow['pc_nombres'] ?></td>
					    <td align="right"><?php echo '$'.number_format($abonosRow['ab_abono'], 0, ".", ","); ?></td>
					    <td class="tableOption">
					    	<a href="paciente-abono-pdf.php?id=<?php echo $abonosRow[IDAbono] ?>"><i class="fa fa-download"></i></a>
					    	<?php if($abonosRow['ab_estado']==1){ ?>
					    		<a class="consultorioAbonoEditar" id="<?php echo $abonosRow[IDAbono] ?>"><?php echo $iconoEditar ?></a>
						   		<a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosRow[IDAbono] ?>"><?php echo $iconoEliminar ?></a>
						   	<?php } ?>
						   </td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
<?php } 
*/
?>