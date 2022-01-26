<?php include'config.php'; include'pagination-modal-params.php';

$id = $_POST['id'];

$proveedorSql = $con->query("SELECT * FROM proveedores WHERE IDProveedor = '$id'");
$proveedorRow = $proveedorSql->fetch_assoc();

$ciudadPR = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$proveedorRow[pr_idCiudad]'")->fetch_assoc();
?>

<form class="form" id="formProveedor" method="post" action="proveedor-guardar.php">
	<div class="modal-header modal-header-form">
	  <div class="titulo tituloSecundario">
	  	<?php if($id){ echo 'Proveedor: '.$proveedorRow['pr_nombre']; 
	  	?>
				<a title="Orden de Entrada" id="<?php echo $id ?>" class="consultorioOrdenEntrada"><?php echo $iconoNuevo ?>Nueva Entrada</a>
	  	<?php } else { echo'Nuevo Proveedor'; } ?>
	  </div>
	  <button class="boton boton-primario">Guardar</button>
	</div>

		<div class="contenedorTabs">
			<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Información</label>
		<?php if($id){ ?>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Estado cuenta</label>
		<?php } ?>
			<div class="contenidoTab">
				<div class="divForm" id="content-1">
					<div class="container3PartForm">
						<input type="text" name="nombre" value="<?php echo $proveedorRow['pr_nombre'] ?>" class="formulario__modal__input" data-label="Nombre">
						<span></span>
						<input type="text" name="nit" value="<?php echo $proveedorRow['pr_nit'] ?>" class="formulario__modal__input" data-label="NIT">
					</div>
					<div class="container3PartForm">
						<input type="text" name="telefono" value="<?php echo $proveedorRow['pr_telefonoFijo'] ?>" class="formulario__modal__input" data-label="Teléfono Fijo">
						<span></span>
						<input type="email" name="correo" value="<?php echo $proveedorRow['pr_correo'] ?>" class="formulario__modal__input" data-label="Correo Electrónico">
					</div>
					<div class="container3PartForm contRips">
						<select name="ciudad" id="ciudad" class="formulario__modal__input" data-label="Ciudad">
								<?php
									if($proveedorRow['pr_idCiudad']!=0){
						            	echo "<option value=".$ciudadPR['IDCiudad']." selected>".$ciudadPR['cd_nombre']."</option>";
						            }
						        ?>
						</select>
						<span></span>
						<input type="text" name="direccion" value="<?php echo $proveedorRow['pr_direccion'] ?>" class="formulario__modal__input" data-label="Dirección">
					</div>
				</div>

			<?php if($id){ ?>
				<div class="divForm" id="content-2">
					
					<div id="msj-factura" class="contenedorAlerta"></div>

					<?php $cuentaProveedor = 0;
					$abonosCuenta = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos AS pra
							INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
							WHERE ore_idProveedor = '$id' AND ore_estado = 1 AND pra_estado = 1")->fetch_assoc();

					$facturaCuenta = $con->query("SELECT SUM(ore_facturaValor) AS facturas FROM ordenesentrada WHERE ore_idProveedor = '$id' AND ore_estado = 1")->fetch_assoc();

					$cuentaProveedor = $facturaCuenta['facturas'] - $abonosCuenta['abonos']; 
					?>

					<div class="titulo tituloSecundario tituloCenter">
						Estado de cuenta:
						<span id="estadoCuenta"><?= '$'.number_format($cuentaProveedor, 0, ".", ","); ?></span>
					</div>
					
					<?php	$ordenesProveedorQuery = "SELECT * FROM ordenesentrada WHERE ore_idProveedor = '$id' AND ore_estado = 1 ORDER BY ore_pagada ASC, IDOrdenEntrada DESC ";

						$rowCountOrdenesProveedor = $con->query($ordenesProveedorQuery)->num_rows;
						$pagConfig = array(
					        'totalRows' => $rowCountOrdenesProveedor,
					        'perPage' => $numeroResultados,
					        'link_func' => 'paginationOrdenesProveedor'
					    );
					    $pagination =  new Pagination($pagConfig);
					    $ordenesProveedorSql = $con->query($ordenesProveedorQuery." LIMIT $numeroResultados");
					?>

					<div id="showResultsFacturaProveedor">
						<table class="tableList">
							<thead>
								<tr>
									<th class="estado"></th>
									<th class="columnaCorta">Fecha de Factura</th>
									<th class="columnaCorta">Vto. Factura</th>
									<th># Orden</th>
									<th># Factura</th>
									<th>Saldo</th>
									<th>Abonos</th>
									<th>Valor Factura</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while( $ordenesProveedorRow = $ordenesProveedorSql->fetch_assoc() ){
										
										$cuentaOrden = 0;
										$abonosOrden = $con->query("SELECT SUM(pra_abono) as abonos FROM ordenesabonos WHERE pra_idOrden = '$ordenesProveedorRow[IDOrdenEntrada]'")->fetch_assoc();
										$cuentaOrden = $ordenesProveedorRow['ore_facturaValor'] - $abonosOrden['abonos'];

										$valorSaldo = '$'.number_format($cuentaOrden, 0, ".", ",");
										$valorAbonos = '$'.number_format($abonosOrden['abonos'], 0, ".", ",");
										$valorFactura = '$'.number_format($ordenesProveedorRow['ore_facturaValor'], 0, ".", ",");

										$vencimientoFacturaInt = str_replace('/', '', $ordenesProveedorRow['ore_facturaFechaVencimiento']);

										$estadoOrden = 'estadoNeutro';
										$titleOrden = 'Pendiente';

										if( $ordenesProveedorRow['ore_pagada'] == 1 ){
											$estadoOrden = 'semaforoVerde';
											$titleOrden = 'Pagada';
										}

										if( $ordenesProveedorRow['ore_pagada'] == 0 && $vencimientoFacturaInt < $fechaHoySinEsp ){
                                            $estadoOrden = 'semaforoRojo';
											$titleOrden = 'Vencida';
                                        }
									?>
									<tr>
										<td class="estado <?= $estadoOrden ?>" title="<?= $titleOrden ?>"></td>
										<td align="center"><?= $ordenesProveedorRow['ore_facturaFecha'] ?></td>
										<td align="center"><?= $ordenesProveedorRow['ore_facturaFechaVencimiento'] ?></td>
										<td align="center"><?= $ordenesProveedorRow['ore_numeroOrden'] ?></td>
										<td align="center"><?= $ordenesProveedorRow['ore_numeroFactura'] ?></td>
										<td align="right"><?= $valorSaldo ?></td>
										<td align="right"><?= $valorAbonos ?></td>
										<td align="right"><?= $valorFactura ?></td>
										<td class="tableOption">
											<?php if($ordenesProveedorRow['ore_pagada'] == 0){ ?>
                                                <a title="Nuevo abono" class="facturaAbono" data-id="<?= $ordenesProveedorRow['IDOrdenEntrada'] ?>"><?= $iconoNuevo ?></a>
                                            <?php } ?>
											<a title="Ver abonos" class="ordenAbonosVer" data-id="<?= $ordenesProveedorRow['IDOrdenEntrada'] ?>"><i class="fa fa-usd" aria-hidden="true"></i></a>
											<a title="Ver Orden" class="ordenEntradaVer" data-id="<?= $ordenesProveedorRow['IDOrdenEntrada'] ?>"><i class="fa fa-file-text"></i></a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>

				</div>
			<?php } ?>
			</div>
		</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
validar('#formProveedor');
$('#ciudad').select2({
	placeholder: '-- Seleccionar --',
	ajax: {
		url: 'json-ciudades.php',
		dataType: 'json',
		delay: 250,
		processResults: function (data){
			return {
				results: data.items,
				  "pagination": {
				    "more": data.pag
				  }
			};
		},
		cache: true
	}
});


<?php if($id){ ?>
		function paginationOrdenesProveedor(page_num) {
			page_num = page_num?page_num:0;
		    $.ajax({
		        type: 'POST',
		        url: 'get/ordenesProveedorData.php',
		        data:'page='+page_num+'&proveedorID='+ <?= $id; ?>,
		        success: function (html) {
		            $('#showResultsFacturaProveedor').html(html);
		        }
		    });
		};

		$(document).on('click', '.facturaAbono', function(){  
			var orden = $(this).attr("data-id"); 
		    if(orden != '')
		    {   
		    	$.ajax({
		        	url: "orden-abono-factura.php",
			        method:"POST",
		            data:{orden:orden}, 
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
			    });  
			}         
		});

		$(document).on('click', '.ordenEntradaVer', function(){  
			var ordenID = $(this).attr("data-id");
		    if(ordenID != '')
		    {   
		    	$.ajax({
		        	url: "orden-entrada.php",
			        method:"POST",
		            data:{ver:1,ordenID:ordenID}, 
			        success:function(data){
			        	$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
			    });  
			}         
		});

		$(document).on('click', '.ordenAbonosVer', function(){  
			var ordenID = $(this).attr("data-id");
		    if(ordenID != '')
		    {   
		    	$.ajax({
		        	url: "orden-abonos-ver.php",
			        method:"POST",
		            data:{ordenID:ordenID}, 
			        success:function(data){
			        	$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show');  
					}
			    });  
			}         
		});



<?php } ?>


</script>