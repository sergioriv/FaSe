<?php include'config.php'; include 'pagination-modal-params.php';

$id = $_POST['id'];
$materialRow = $con->query("SELECT * FROM materiales WHERE IDMaterial = '$id'")->fetch_assoc();

	$semaforoRed = date ( 'Ymd' , strtotime ( '+30days' , strtotime ( $fechaHoy ) ) ) ;
	$semaforoYellow = date ( 'Ymd' , strtotime ( '+90days' , strtotime ( $fechaHoy ) ) ) ;

?>
<form class="form" id="formMaterial" method="post" action="material-guardar.php">
	<div class="modal-header modal-header-form">
	  <div class="titulo tituloSecundario"><?php if($id){ echo 'Item: '.$materialRow['mt_codigo'].' - '.$materialRow['mt_nombre']; } else {echo'Nuevo Material';} ?></div>
	  <button class="boton boton-primario">Guardar</button>
	</div>

	<div class="contenedorTabs">
		<input id="tab-1" type="radio" name="tab-group" checked />
		<label for="tab-1" class="labelTab">Información</label>
	<?php if($id){ ?>
		<input id="tab-2" type="radio" name="tab-group" />
		<label for="tab-2" class="labelTab">Entradas</label>
		<input id="tab-3" type="radio" name="tab-group" />
		<label for="tab-3" class="labelTab">Salidas</label>
	<?php } ?>
		<div class="contenidoTab">
			<div class="divForm" id="content-1">

		        <div class="container3PartForm">
					<input type="text" name="codigo" value="<?php echo $materialRow['mt_codigo'] ?>" class="formulario__input" data-label="Código">
					<span></span>
					<input type="text" name="nombre" value="<?php echo $materialRow['mt_nombre'] ?>" class="formulario__input" data-label="Nombre" required>
				</div>
		        <div class="container3PartForm">
					<input type="text" name="marca" value="<?php echo $materialRow['mt_marca'] ?>" class="formulario__input" data-label="Marca">
					<span></span>
					<input type="text" name="presentacion" value="<?php echo $materialRow['mt_presentacion'] ?>" class="formulario__input" data-label="Presentación">
				</div>
				<div class="container3PartForm">
					<input type="text" name="riesgo" value="<?php echo $materialRow['mt_riesgo'] ?>" class="formulario__input" data-label="Nivel de Riesgo">
					<span></span>
					<input type="text" name="temperatura" value="<?php echo $materialRow['mt_temperatura'] ?>" class="formulario__input" data-label="Temperatura de almacenaje">
				</div>
				<div class="container1Part">
					<input type="text" name="vidautil" value="<?php echo $materialRow['mt_vidaUtil'] ?>" class="formulario__input" data-label="Vida Util">
				</div>
				<div class="contenedorCheckbox SliderSwitch <?php if(!$id){echo"pointer";} ?>">
					<label for="switch">El Item posee fecha de vencimiento
					<input id="switch" type="checkbox" name="vencimiento" value="1" 
					<?php 
						if($materialRow['mt_vencimiento']==1){
							echo"checked disabled";
						}
						if($materialRow['mt_vencimiento']==0){
							echo"disabled";
						}
						if(!$id){
							echo"enable";
						}
					?>>
					<div class="SliderSwitch__container">
						<div class="SliderSwitch__toggle"></div>
					</div>
					</label>
				</div>
			</div>

			<div class="divForm matEntrada" id="content-2">

				<div id="msj-entradaMaterial" class="contenedorAlerta"></div>

			<?php  	$queryEntradasSession = '';
				if($sessionRol==2){
					$queryEntradasSession = "AND IDSucursal = '$sessionUsuario'";
				} else if($sessionRol==4){
					$usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
					$queryEntradasSession = "AND IDSucursal = '$usuarioInventario[ui_idSucursal]'";
				}

				$entradasQuery = "SELECT * FROM materialesentrada AS me 
						INNER JOIN ordenesentrada AS ore ON me.me_idOrdenEntrada = ore.IDOrdenEntrada 
						INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor 
						INNER JOIN sucursales AS sc ON me.me_idSucursal = sc.IDSucursal
						WHERE me_idMaterial = '$id' AND me_estado = '1' $queryEntradasSession ORDER BY IDMatEntrada DESC ";

				$rowEntradasCount = $con->query($entradasQuery)->num_rows;
				$pagConfig = array(
			        'totalRows' => $rowEntradasCount,
			        'perPage' => $numeroResultados,
			        'link_func' => 'paginationMaterialEntradas'
			    );
			    $pagination =  new Pagination($pagConfig);

				$entradasSql = $con->query($entradasQuery." LIMIT $numeroResultados");
		    ?>
		    			<div class="titulo_optional_search form">
							<a class="consultorioDescargar" data-page="material_entradas" data-rango-de="mtEntradaRangoDe" data-rango-hasta="mtEntradaRangoHasta" data-rango-id="<?= $id ?>"><i class="fa fa-download"></i>Descargar</a>
							<input type="date" id="mtEntradaRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationMaterialEntradas();">
							<input type="date" id="mtEntradaRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationMaterialEntradas();">
						</div>

					<div id="showResultsEntradas">
						<table class="tableList">
			        		<thead>
			        			<tr>
			        				<?php if($materialRow['mt_vencimiento'] == 1){ ?>
			        					<th class="estado">&nbsp</th>
			        				<?php } ?>
			        				<th>Fecha</th>
			        				<th>Usuario</th>
			        				<th>Proveedor</th>
			        			<?php if($sessionRol==1){ ?>
			        				<th>Sucursal</th>
			        			<?php } ?>
			        				<th># Orden</th>
			        				<th># Factura</th>
			        				<th># Lote</th>
			        				<th>Reg. Invima</th>
			        				<th>Cant.</th>
			        				<?php if($materialRow['mt_vencimiento']==1){ ?>
			        					<th class="columnaCorta">Vencimiento</th>
			        				<?php } ?>
			        				<th>&nbsp</th>
			        			</tr>
			        		</thead>
			            	<tbody>
			        	<?php while($entradasRow = $entradasSql->fetch_assoc()){

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

			        			if($materialRow['mt_vencimiento'] == 1){

			        				$fechaVencimiento = str_replace("/","", $entradasRow['me_fechaVencimiento']);			        				

			        				if($fechaVencimiento < $fechaHoySinEsp){ $estadoFechaVencimiento = 'estadoNeutro'; }
			        				else if($fechaVencimiento <= $semaforoRed) { $estadoFechaVencimiento = 'semaforoRojo'; }
			        				else if($fechaVencimiento <= $semaforoYellow) { $estadoFechaVencimiento = 'semaforoAmarillo'; }
			        				else { $estadoFechaVencimiento = 'semaforoVerde'; }
			        			}

			        			$cantidadActual = 0;
			        			$cantidadSalidasSql = $con->query("SELECT SUM(ms_cantidad) AS cantSalida FROM materialessalida WHERE ms_idMatEntrada = '$entradasRow[IDMatEntrada]' ")->fetch_assoc();
			        			$cantidadActual = $entradasRow['me_cantidad'] - $cantidadSalidasSql['cantSalida'];
			        	?>
				        		<tr>
				        			<?php if($materialRow['mt_vencimiento'] == 1){ ?>
			        					<th class="estado <?php echo $estadoFechaVencimiento ?>">&nbsp</th>
			        				<?php } ?>
				        			<td><?php echo $entradasRow['me_fechaCreacion'] ?></td>
				        			<td><?php echo $nombreUsuario ?></td>
				        			<td><?php echo $entradasRow['pr_nombre'] ?></td>
			        			<?php if($sessionRol==1){ ?>
			        				<td><?php echo $entradasRow['sc_nombre'] ?></td>
			        			<?php } ?>
				        			<td align="center"><?php echo $entradasRow['ore_numeroOrden'] ?></td>
				        			<td align="center"><?php echo $entradasRow['ore_numeroFactura'] ?></td>
				        			<td align="center"><?php echo $entradasRow['me_numeroLote'] ?></td>
				        			<td align="center"><?php echo $entradasRow['me_invima'] ?></td>
				        			<td align="center" class="cantidadActual" id="<?php echo $entradasRow['IDMatEntrada'] ?>"><?php echo $cantidadActual .' / '. $entradasRow['me_cantidad'] ?></td>
				        			<?php if($materialRow['mt_vencimiento']==1){ ?>
				        				<td class="columnaCorta"><?php echo $entradasRow['me_fechaVencimiento'] ?></td>
				        			<?php } ?>
				        			<td class="tableOption">
				        				<a title="Ver Orden" class="ordenEntradaVer" data-id="<?= $entradasRow['IDOrdenEntrada'] ?>"><i class="fa fa-file-text"></i></a>
				        				<?php if( $cantidadActual > 0){ ?>
					        				<a title="Salida" id="<?php echo $entradasRow['IDMatEntrada'] ?>" class="consultorioSalida"><i class="fa fa-upload" aria-hidden="true"></i></a>
					        			<?php } ?>
				        			</td>
					           	</tr>
			        	<?php } ?>
			        		</tbody>
			        	</table>
			        	<?php echo $pagination->createLinks(); ?>
			        </div>
			</div>



			<div class="divForm" id="content-3">
			<?php  	$querySalidasSession = '';
				if($sessionRol==2){
					$querySalidasSession = "AND me_idSucursal = '$sessionUsuario'";
				} else if($sessionRol==4){
					$usuarioInventario = $con->query("SELECT ui_idSucursal FROM usuariosinventario WHERE IDUserInventario = '$sessionUsuario'")->fetch_assoc();
					$querySalidasSession = "AND me_idSucursal = '$usuarioInventario[ui_idSucursal]'";
				}

				$salidasQuery = "SELECT * FROM materialessalida AS ms 
						INNER JOIN materialesentrada AS me ON ms.ms_idMatEntrada = me.IDMatEntrada
						WHERE ms_estado = '1' AND me_idMaterial = '$id' $querySalidasSession
						ORDER BY IDMatSalida DESC ";
				$rowSalidasCount = $con->query($salidasQuery)->num_rows;
				$pagConfig = array(
			        'totalRows' => $rowSalidasCount,
			        'perPage' => $numeroResultados,
			        'link_func' => 'paginationMaterialSalidas'
			    );
			    $pagination =  new Pagination($pagConfig);

    			$salidasSql = $con->query($salidasQuery." LIMIT $numeroResultados"); 
		    ?>
		    			<div class="titulo_optional_search form">
							<a class="consultorioDescargar" data-page="material_salidas" data-rango-de="mtSalidaRangoDe" data-rango-hasta="mtSalidaRangoHasta" data-rango-id="<?= $id ?>"><i class="fa fa-download"></i>Descargar</a>
							<input type="date" id="mtSalidaRangoDe" class="formulario__input" data-label="Fecha de" onchange="paginationMaterialSalidas();">
							<input type="date" id="mtSalidaRangoHasta" class="formulario__input" data-label="Fecha hasta" onchange="paginationMaterialSalidas();">
						</div>
		    	<div id="showResultsSalidas">
					<table class="tableList">
		        		<thead>
		        			<tr>		        				
		        				<th>Cant.</th>
		        				<th class="columnaCorta">Fecha</th>
		        				<th>Usuario</th>
		        				<th># Lote</th>
		        				<th>Reg. Invima</th>
		        				<th>Descripción</th>
		        			</tr>
		        		</thead>
		            	<tbody>
		        	<?php while($salidasRow = $salidasSql->fetch_assoc()){

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
			        			<td align="center"><?php echo $salidasRow['ms_cantidad'] ?></td>
			        			<td class="columnaCorta"><?php echo $salidasRow['ms_fechaCreacion'] ?></td>
			        			<td><?php echo $nombreUsuario ?></td>
			        			<td align="center"><?php echo $salidasRow['me_numeroLote'] ?></td>
			        			<td align="center"><?php echo $salidasRow['me_invima'] ?></td>
			        			<td class="text-justify"><?php echo $salidasRow['ms_detalles'] ?></td>
				           	</tr>
		        	<?php } ?>
		        		</tbody>
		        	</table>
		        	<?php echo $pagination->createLinks(); ?>
		        </div>
			</div>

	</div>
	   
	<div class="modal-footer">  
		
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<!--<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>-->
		<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label.js"></script>
<script type="text/javascript">
validar('#formMaterial');
<?php if($id){ ?>
	function paginationMaterialEntradas(page_num) {
		var busquedaDe = $('#mtEntradaRangoDe').val();
		var busquedaHasta = $('#mtEntradaRangoHasta').val();
		page_num = page_num?page_num:0;
		$.ajax({
			type: 'POST',
			url: 'get/materialEntradasData.php',
			data:'page='+page_num+'&id='+<?= $id ?>+'&de='+busquedaDe+'&hasta='+busquedaHasta,
			success: function (html) {
				$('#showResultsEntradas').html(html);
			}
		});
	}
	function paginationMaterialSalidas(page_num) {
		var busquedaDe = $('#mtSalidaRangoDe').val();
		var busquedaHasta = $('#mtSalidaRangoHasta').val();
		page_num = page_num?page_num:0;
		$.ajax({
			type: 'POST',
			url: 'get/materialSalidasData.php',
			data:'page='+page_num+'&id='+<?= $id ?>+'&de='+busquedaDe+'&hasta='+busquedaHasta,
			success: function (html) {
				$('#showResultsSalidas').html(html);
			}
		});
	}

<?php } ?>
</script>